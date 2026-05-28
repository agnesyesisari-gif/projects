<?php

namespace App\Libraries;

use App\Models\JadwalModel\JadwalIbadahModel;
use App\Models\KegiatanModel;
use App\Libraries\AuthLib;

class CalendarLib
{
    
    protected $eventModel;
    protected $kegiatanModel;
    protected $authLib;

    
    const TYPE_IBADAH = 'ibadah';
    const TYPE_PROGRAM = 'program';
    const TYPE_KEGIATAN = 'kegiatan';
    const TYPE_PELAYANAN = 'pelayanan';
    
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'sedang_berlangsung';
    const STATUS_CANCELLED = 'dibatalkan';
    const STATUS_COMPLETED = 'selesai';

    const RECURRENCE_NONE = 'none';
    const RECURRENCE_DAILY = 'daily';
    const RECURRENCE_WEEKLY = 'weekly';
    const RECURRENCE_MONTHLY = 'monthly';
    const RECURRENCE_YEARLY = 'yearly';
    const RECURRENCE_CUSTOM = 'custom';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    const DAYS_OF_WEEK = [
        'sunday'    => 'Minggu',
        'monday'    => 'Senin',
        'tuesday'   => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday'  => 'Kamis',
        'friday'    => 'Jumat',
        'saturday'  => 'Sabtu'
    ];

    const INDONESIAN_MONTHS = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];


    const IBADAH_TYPES = [
        'ibadah_minggu'         => 'Ibadah Minggu',
        'ibadah_tukar_mimbar_klasis'        => 'Ibadah Tukar Mimbar Klasis '
    ];

    const PROGRAM_TYPES = [
        'pelayanan_sosial'     => 'Pelayanan Sosial',
        'pengajaran'           => 'Pengajaran',
        'evangelisasi'         => 'Evangelisasi',
        'kunjungan'            => 'Kunjungan',
        'retret'               => 'Retret',
        'seminar'              => 'Seminar',
        'pelatihan'            => 'Pelatihan',
        'bakti_sosial'         => 'Bakti Sosial',
        'konseling'            => 'Konseling'
    ];

    public function __construct()
    {
        $this->eventModel    = new JadwalIbadahModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->authLib       = new AuthLib();
    }

    public function getEventsByDateRange(string $startDate, string $endDate, array $filters = []): array
    {
        $builder = $this->eventModel->where('start_date >=', $startDate)
                                   ->where('end_date <=', $endDate)
                                   ->where('status', self::STATUS_PUBLISHED);

        // Apply filters
        if (!empty($filters['type'])) {
            $builder->where('type', $filters['type']);
        }

        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['location'])) {
            $builder->like('location', $filters['location']);
        }

        if (!empty($filters['created_by'])) {
            $builder->where('created_by', $filters['created_by']);
        }

        // Check user permissions
        if (!$this->authLib->hasAnyRole([AuthLib::ROLE_SUPER_ADMIN, AuthLib::ROLE_ADMIN, AuthLib::ROLE_PENDETA])) {
            $builder->where('is_private', 0);
        }

        $events = $builder->orderBy('start_date', 'ASC')
                         ->orderBy('start_time', 'ASC')
                         ->findAll();

        // Process recurring events
        $processedEvents = [];
        foreach ($events as $event) {
            if ($event->recurrence_pattern !== self::RECURRENCE_NONE) {
                $recurringEvents = $this->generateRecurringEvents($event, $startDate, $endDate);
                $processedEvents = array_merge($processedEvents, $recurringEvents);
            } else {
                $processedEvents[] = $this->formatEvent($event);
            }
        }

        return $processedEvents;
    }

    public function getMonthlyEvents(int $year, int $month, array $filters = []): array
    {
        $startDate = date('Y-m-01', strtotime("$year-$month-01"));
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));

        return $this->getEventsByDateRange($startDate, $endDate, $filters);
    }

    public function getWeeklyEvents(string $date, array $filters = []): array
    {
        $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));
        $weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($date)));

        $events = $this->getEventsByDateRange($weekStart, $weekEnd, $filters);

        // Group events by day
        $weeklyEvents = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDate = date('Y-m-d', strtotime("$weekStart +$i days"));
            $weeklyEvents[$currentDate] = [];
        }

        foreach ($events as $event) {
            $eventDate = date('Y-m-d', strtotime($event['start_date']));
            if (isset($weeklyEvents[$eventDate])) {
                $weeklyEvents[$eventDate][] = $event;
            }
        }

        return $weeklyEvents;
    }

    public function getDailyEvents(string $date, array $filters = []): array
    {
        return $this->getEventsByDateRange($date, $date, $filters);
    }

    public function getTodaysEvents(): array
    {
        $today = date('Y-m-d');
        return $this->getDailyEvents($today);
    }

    public function getUpcomingEvents(int $limit = 10): array
    {
        $today = date('Y-m-d');

        $events = $this->eventModel->where('start_date >=', $today)
                                  ->where('status', self::STATUS_PUBLISHED)
                                  ->orderBy('start_date', 'ASC')
                                  ->orderBy('start_time', 'ASC')
                                  ->limit($limit)
                                  ->findAll();

        return array_map([$this, 'formatEvent'], $events);
    }

    public function getWorshipSchedules(string $date, ?string $type = null): array
    {
        $filters = ['type' => self::TYPE_IBADAH];
        
        if ($type) {
            $filters['sub_type'] = $type;
        }

        return $this->getDailyEvents($date, $filters);
    }

    public function getChurchPrograms(string $startDate, string $endDate): array
    {
        $filters = ['type' => self::TYPE_PROGRAM];
        return $this->getEventsByDateRange($startDate, $endDate, $filters);
    }

    public function getChurchActivities(string $startDate, string $endDate): array
    {
        $filters = ['type' => self::TYPE_KEGIATAN];
        return $this->getEventsByDateRange($startDate, $endDate, $filters);
    }

    public function createEvent(array $data)
    {
        // Set default values
        $defaults = [
            'status' => self::STATUS_DRAFT,
            'is_private' => 0,
            'recurrence_pattern' => self::RECURRENCE_NONE,
            'priority' => self::PRIORITY_MEDIUM,
            'created_by' => $this->authLib->getUserId(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $eventData = array_merge($defaults, $data);

        try {
            $eventId = $this->eventModel->insert($eventData);
            
            if ($eventId) {
                // Log activity
                $this->logActivity('event_created', "Event created: {$eventData['title']}", $eventId);
                
                // Handle attachments if any
                if (isset($data['attachments'])) {
                    $this->saveAttachments($eventId, $data['attachments']);
                }
                
                // Handle participants if any
                if (isset($data['participants'])) {
                    $this->saveParticipants($eventId, $data['participants']);
                }
                
                return $eventId;
            }
        } catch (\Exception $e) {
            log_message('error', 'Event creation failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function updateEvent(int $eventId, array $data): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        // Check permission
        if (!$this->canEditEvent($event)) {
            return false;
        }
        
        try {
            $updated = $this->eventModel->update($eventId, $data);
            
            if ($updated) {
                // Handle attachments if any
                if (isset($data['attachments'])) {
                    $this->saveAttachments($eventId, $data['attachments']);
                }
                
                // Handle participants if any
                if (isset($data['participants'])) {
                    $this->saveParticipants($eventId, $data['participants']);
                }
                
                $this->logActivity('event_updated', "Event updated: {$event->title}", $eventId);
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'Event update failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function deleteEvent(int $eventId): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        // Check permission
        if (!$this->canDeleteEvent($event)) {
            return false;
        }
        
        try {
            // Delete related records first
            $this->participantModel->where('event_id', $eventId)->delete();
            $this->attachmentModel->where('event_id', $eventId)->delete();
            
            // Delete event
            $deleted = $this->eventModel->delete($eventId);
            
            if ($deleted) {
                $this->logActivity('event_deleted', "Event deleted: {$event->title}", $eventId);
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'Event deletion failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function publishEvent(int $eventId): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        // Check permission
        if (!$this->canPublishEvent($event)) {
            return false;
        }
        
        $updated = $this->eventModel->update($eventId, [
            'status' => self::STATUS_PUBLISHED,
            'published_at' => date('Y-m-d H:i:s'),
            'published_by' => $this->authLib->getUserId()
        ]);
        
        if ($updated) {
            $this->logActivity('event_published', "Event published: {$event->title}", $eventId);
            return true;
        }
        
        return false;
    }

    public function cancelEvent(int $eventId, string $reason = ''): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        // Check permission
        if (!$this->canCancelEvent($event)) {
            return false;
        }
        
        $updated = $this->eventModel->update($eventId, [
            'status' => self::STATUS_CANCELLED,
            'cancellation_reason' => $reason,
            'cancelled_at' => date('Y-m-d H:i:s'),
            'cancelled_by' => $this->authLib->getUserId()
        ]);
        
        if ($updated) {
            $this->logActivity('event_cancelled', "Event cancelled: {$event->title}. Reason: {$reason}", $eventId);
            return true;
        }
        
        return false;
    }

    public function completeEvent(int $eventId, array $completionData = []): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $updateData = [
            'status' => self::STATUS_COMPLETED,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($completionData)) {
            $updateData = array_merge($updateData, $completionData);
        }
        
        $updated = $this->eventModel->update($eventId, $updateData);
        
        if ($updated) {
            $this->logActivity('event_completed', "Event completed: {$event->title}", $eventId);
            return true;
        }
        
        return false;
    }

    public function addParticipant(int $eventId, int $userId, string $role = 'participant', array $data = []): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $participantData = [
            'event_id' => $eventId,
            'user_id' => $userId,
            'role' => $role,
            'status' => 'confirmed',
            'joined_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($data)) {
            $participantData = array_merge($participantData, $data);
        }
        
        try {
            $participantId = $this->participantModel->insert($participantData);
            
            if ($participantId) {
                $this->logActivity('participant_added', "Participant added to event: {$event->title}", $eventId);
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'Participant addition failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function removeParticipant(int $eventId, int $userId): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $deleted = $this->participantModel->where('event_id', $eventId)
                                         ->where('user_id', $userId)
                                         ->delete();
        
        if ($deleted) {
            $this->logActivity('participant_removed', "Participant removed from event: {$event->title}", $eventId);
            return true;
        }
        
        return false;
    }

    public function getEventParticipants(int $eventId): array
    {
        return $this->participantModel->where('event_id', $eventId)
                                     ->join('users', 'users.id = event_participants.user_id')
                                     ->select('event_participants.*, users.nama, users.email, users.role as user_role')
                                     ->findAll();
    }

    public function addAttachment(int $eventId, array $fileData): bool
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $attachmentData = [
            'event_id' => $eventId,
            'file_name' => $fileData['file_name'] ?? '',
            'file_path' => $fileData['file_path'] ?? '',
            'file_type' => $fileData['file_type'] ?? '',
            'file_size' => $fileData['file_size'] ?? 0,
            'uploaded_by' => $this->authLib->getUserId(),
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $attachmentId = $this->attachmentModel->insert($attachmentData);
            
            if ($attachmentId) {
                $this->logActivity('attachment_added', "Attachment added to event: {$event->title}", $eventId);
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'Attachment addition failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function getEventAttachments(int $eventId): array
    {
        return $this->attachmentModel->where('event_id', $eventId)->findAll();
    }

    public function generateCalendar(array $events, string $format = 'html')
    {
        switch ($format) {
            case 'json':
                return $this->generateJsonCalendar($events);
            case 'ical':
                return $this->generateICalCalendar($events);
            case 'pdf':
                return $this->generatePdfCalendar($events);
            default:
                return $this->generateHtmlCalendar($events);
        }
    }

    private function generateHtmlCalendar(array $events): string
    {
        $calendar = '<div class="church-calendar">';
        
        // Group events by date
        $eventsByDate = [];
        foreach ($events as $event) {
            $date = date('Y-m-d', strtotime($event['start_date']));
            $eventsByDate[$date][] = $event;
        }
        
        // Generate calendar grid
        ksort($eventsByDate);
        
        foreach ($eventsByDate as $date => $dateEvents) {
            $calendar .= '<div class="calendar-day">';
            $calendar .= '<h4>' . $this->formatIndonesianDate($date) . '</h4>';
            
            foreach ($dateEvents as $event) {
                $calendar .= $this->generateEventCard($event);
            }
            
            $calendar .= '</div>';
        }
        
        $calendar .= '</div>';
        
        return $calendar;
    }

    private function generateJsonCalendar(array $events): string
    {
        return json_encode([
            'success' => true,
            'data' => $events,
            'generated_at' => date('Y-m-d H:i:s'),
            'total_events' => count($events)
        ]);
    }

    private function generateICalCalendar(array $events): string
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Church Calendar//IDN//EN\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        
        foreach ($events as $event) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . uniqid() . "@church-calendar\r\n";
            $ical .= "SUMMARY:" . $event['title'] . "\r\n";
            $ical .= "DESCRIPTION:" . ($event['description'] ?? '') . "\r\n";
            $ical .= "PLACE:" . ($event['place'] ?? '') . "\r\n";
            $ical .= "DTSTART:" . date('Ymd\THis', strtotime($event['start_date'] . ' ' . $event['start_time'])) . "\r\n";
            $ical .= "DTEND:" . date('Ymd\THis', strtotime($event['end_date'] . ' ' . $event['end_time'])) . "\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR\r\n";
        
        return $ical;
    }

    private function generateRecurringEvents(CalendarEvent $event, string $startDate, string $endDate): array
    {
        $recurringEvents = [];
        $currentDate = $event->start_date;
        
        while ($currentDate <= $endDate) {
            if ($currentDate >= $startDate) {
                $eventCopy = clone $event;
                $eventCopy->start_date = $currentDate;
                $eventCopy->end_date = $currentDate;
                
                $recurringEvents[] = $this->formatEvent($eventCopy);
            }
            
            // Calculate next occurrence
            $currentDate = $this->calculateNextOccurrence($currentDate, $event);
        }
        
        return $recurringEvents;
    }

    private function calculateNextOccurrence(string $currentDate, CalendarEvent $event): string
    {
        switch ($event->recurrence_pattern) {
            case self::RECURRENCE_DAILY:
                return date('Y-m-d', strtotime("$currentDate +1 day"));
                
            case self::RECURRENCE_WEEKLY:
                return date('Y-m-d', strtotime("$currentDate +1 week"));
                
            case self::RECURRENCE_MONTHLY:
                return date('Y-m-d', strtotime("$currentDate +1 month"));
                
            case self::RECURRENCE_YEARLY:
                return date('Y-m-d', strtotime("$currentDate +1 year"));
                
            case self::RECURRENCE_CUSTOM:
                if ($event->recurrence_interval) {
                    return date('Y-m-d', strtotime("$currentDate +{$event->recurrence_interval} days"));
                }
                break;
        }
        
        return $currentDate;
    }

    private function formatEvent(CalendarEvent $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'type' => $event->type,
            'category_id' => $event->category_id,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
            'place' => $event->place,
            'leader' => $event->leader,
            'contact_person' => $event->contact_person,
            'phone' => $event->phone,
            'email' => $event->email,
            'status' => $event->status,
            'priority' => $event->priority,
            'is_private' => (bool)$event->is_private,
            'max_participants' => $event->max_participants,
            'current_participants' => $event->current_participants,
            'budget' => $event->budget,
            'color' => $this->getEventColor($event),
            'icon' => $this->getEventIcon($event),
            'formatted_date' => $this->formatEventDate($event),
            'formatted_time' => $this->formatEventTime($event),
            'recurrence' => $event->recurrence_pattern !== self::RECURRENCE_NONE,
            'recurrence_pattern' => $event->recurrence_pattern,
            'can_edit' => $this->canEditEvent($event),
            'can_delete' => $this->canDeleteEvent($event)
        ];
    }

    private function generateEventCard(array $event): string
    {
        $cardClass = 'calendar-event';
        $cardClass .= ' event-' . $event['type'];
        $cardClass .= ' priority-' . $event['priority'];
        
        if ($event['is_private']) {
            $cardClass .= ' event-private';
        }
        
        $html = '<div class="' . $cardClass . '" style="border-left-color: ' . $event['color'] . '">';
        $html .= '<div class="event-header">';
        $html .= '<span class="event-icon">' . $event['icon'] . '</span>';
        $html .= '<h5 class="event-title">' . $event['title'] . '</h5>';
        $html .= '<span class="event-time">' . $event['formatted_time'] . '</span>';
        $html .= '</div>';
        $html .= '<div class="event-body">';
        
        if ($event['location']) {
            $html .= '<p class="event-location"><i class="fas fa-map-marker-alt"></i> ' . $event['location'] . '</p>';
        }
        
        if ($event['leader']) {
            $html .= '<p class="event-leader"><i class="fas fa-user"></i> ' . $event['leader'] . '</p>';
        }
        
        if ($event['description']) {
            $html .= '<p class="event-description">' . $event['description'] . '</p>';
        }
        
        $html .= '</div>';
        $html .= '<div class="event-footer">';
        $html .= '<span class="event-status badge badge-' . $this->getStatusBadgeClass($event['status']) . '">';
        $html .= $this->getStatusText($event['status']);
        $html .= '</span>';
        
        if ($event['max_participants'] > 0) {
            $html .= '<span class="event-participants">';
            $html .= '<i class="fas fa-users"></i> ' . $event['current_participants'] . '/' . $event['max_participants'];
            $html .= '</span>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    private function getEventColor(CalendarEvent $event): string
    {
        $colors = [
            self::TYPE_IBADAH => '#3498db',     // Blue
            self::TYPE_PROGRAM => '#2ecc71',    // Green
            self::TYPE_KEGIATAN => '#e74c3c',   // Red
        ];
        
        return $colors[$event->type] ?? '#95a5a6';
    }

    private function getEventIcon(CalendarEvent $event): string
    {
        $icons = [
            self::TYPE_IBADAH => 'fas fa-church',
            self::TYPE_PROGRAM => 'fas fa-calendar-check',
            self::TYPE_KEGIATAN => 'fas fa-tasks',
        ];
        
        return $icons[$event->type] ?? 'fas fa-calendar';
    }

    private function formatEventDate(CalendarEvent $event): string
    {
        return $this->formatIndonesianDate($event->start_date);
    }

    public function formatIndonesianDate(string $date): string
    {
        $timestamp = strtotime($date);
        $day = date('N', $timestamp); // 1 (Monday) through 7 (Sunday)
        
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        $dayName = $dayNames[$day] ?? '';
        $dateNum = date('j', $timestamp);
        $month = self::INDONESIAN_MONTHS[date('n', $timestamp)] ?? '';
        $year = date('Y', $timestamp);
        
        return "$dayName, $dateNum $month $year";
    }

    private function formatEventTime(CalendarEvent $event): string
    {
        $startTime = $event->start_time ? date('H:i', strtotime($event->start_time)) : '';
        $endTime = $event->end_time ? date('H:i', strtotime($event->end_time)) : '';
        
        if ($startTime && $endTime) {
            return "$startTime - $endTime";
        } elseif ($startTime) {
            return "Mulai $startTime";
        }
        
        return '';
    }
}