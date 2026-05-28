<?php

use App\Models\JadwalModel\JadwalIbadahModel;
use App\Models\ProkerModel\ProkerModel;
use App\Models\UserModel;

if (!function_exists('format_church_date')) {
    
    function format_church_date($date, $includeDay = true, $includeTime = false)
    {
        if (empty($date)) {
            return '-';
        }
        
        $timestamp = strtotime($date);
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $day = $dayNames[date('w', $timestamp)];
        $dateNum = date('j', $timestamp);
        $month = $monthNames[date('n', $timestamp) - 1];
        $year = date('Y', $timestamp);
        $time = date('H:i', $timestamp);
        
        $formatted = '';
        if ($includeDay) {
            $formatted .= $day . ', ';
        }
        
        $formatted .= $dateNum . ' ' . $month . ' ' . $year;
        
        if ($includeTime) {
            $formatted .= ' ' . $time . ' WIB';
        }
        
        return $formatted;
    }
}

if (!function_exists('format_church_time')) {
    
    function format_church_time($time, $showPeriod = true)
    {
        if (empty($time)) {
            return '-';
        }
        
        $timestamp = strtotime($time);
        $formatted = date('H:i', $timestamp);
        
        if ($showPeriod) {
            $hour = date('H', $timestamp);
            $period = ($hour < 12) ? 'WIB' : (($hour < 15) ? 'Siang' : (($hour < 19) ? 'Sore' : 'Malam'));
            $formatted .= ' ' . $period;
        }
        
        return $formatted;
    }
}

if (!function_exists('calculate_church_period')) {
    
    function calculate_church_period($periodType = 'monthly', $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $currentDate = strtotime($date);
        
        switch ($periodType) {
            case 'weekly':
                // Church week: Monday to Sunday
                $start = strtotime('last monday', $currentDate);
                $end = strtotime('next sunday', $currentDate);
                break;
                
            case 'monthly':
                $start = strtotime(date('Y-m-01', $currentDate));
                $end = strtotime(date('Y-m-t', $currentDate));
                break;
                
            case 'yearly':
                $start = strtotime(date('Y-01-01', $currentDate));
                $end = strtotime(date('Y-12-31', $currentDate));
                break;
                
            case 'quarterly':
                $month = date('n', $currentDate);
                $quarter = ceil($month / 3);
                $startMonth = (($quarter - 1) * 3) + 1;
                $endMonth = $startMonth + 2;
                $start = strtotime(date('Y-' . sprintf('%02d', $startMonth) . '-01', $currentDate));
                $end = strtotime(date('Y-' . sprintf('%02d', $endMonth) . '-t', $currentDate));
                break;
                
            default:
                $start = $currentDate;
                $end = $currentDate;
        }
        
        return [
            'start' => date('Y-m-d', $start),
            'end' => date('Y-m-d', $end),
            'start_formatted' => format_church_date(date('Y-m-d', $start)),
            'end_formatted' => format_church_date(date('Y-m-d', $end)),
            'period_name' => generate_period_name($periodType, $currentDate)
        ];
    }
}

if (!function_exists('generate_period_name')) {
    
    function generate_period_name($periodType, $timestamp)
    {
        $monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        switch ($periodType) {
            case 'weekly':
                $weekStart = date('j', strtotime('last monday', $timestamp));
                $weekEnd = date('j', strtotime('next sunday', $timestamp));
                $month = $monthNames[date('n', $timestamp) - 1];
                $year = date('Y', $timestamp);
                return "Minggu {$weekStart} - {$weekEnd} {$month} {$year}";
                
            case 'monthly':
                $month = $monthNames[date('n', $timestamp) - 1];
                $year = date('Y', $timestamp);
                return "Bulan {$month} {$year}";
                
            case 'yearly':
                $year = date('Y', $timestamp);
                return "Tahun {$year}";
                
            case 'quarterly':
                $month = date('n', $timestamp);
                $quarter = ceil($month / 3);
                $year = date('Y', $timestamp);
                return "Triwulan {$quarter} {$year}";
                
            default:
                return format_church_date(date('Y-m-d', $timestamp));
        }
    }
}

if (!function_exists('validate_church_data')) {
    
    function validate_church_data($data, $type = 'service')
    {
        $errors = [];
        
        switch ($type) {
            case 'service':
                $errors = validate_service_data($data);
                break;
                
            case 'program':
                $errors = validate_program_data($data);
                break;
                
            case 'member':
                $errors = validate_member_data($data);
                break;
                
            case 'officer':
                $errors = validate_officer_data($data);
                break;
                
            default:
                $errors = validate_general_data($data);
        }
        
        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

if (!function_exists('validate_service_data')) {
    
    function validate_service_data($data)
    {
        $errors = [];
        
        // Required fields
        $required = ['date', 'time', 'theme', 'speaker'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus diisi';
            }
        }
        
        // Date validation
        if (!empty($data['date'])) {
            if (!strtotime($data['date'])) {
                $errors['date'] = 'Format tanggal tidak valid';
            } elseif (strtotime($data['date']) < strtotime('today')) {
                // Allow past dates for historical data
                // Remove this check if you want to allow only future dates
            }
        }
        
        // Time validation
        if (!empty($data['time'])) {
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['time'])) {
                $errors['time'] = 'Format waktu tidak valid (HH:MM)';
            }
        }
        
        // Max length validations
        $maxLengths = [
            'theme' => 200,
            'speaker' => 100,
            'location' => 150
        ];
        
        foreach ($maxLengths as $field => $maxLength) {
            if (!empty($data[$field]) && strlen($data[$field]) > $maxLength) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . 
                                 " maksimal {$maxLength} karakter";
            }
        }
        
        return $errors;
    }
}

if (!function_exists('validate_program_data')) {
    
    function validate_program_data($data)
    {
        $errors = [];
        
        // Required fields
        $required = ['name', 'department', 'start_date', 'budget'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus diisi';
            }
        }
        
        // Date validation
        if (!empty($data['start_date'])) {
            if (!strtotime($data['start_date'])) {
                $errors['start_date'] = 'Format tanggal mulai tidak valid';
            }
        }
        
        if (!empty($data['end_date'])) {
            if (!strtotime($data['end_date'])) {
                $errors['end_date'] = 'Format tanggal selesai tidak valid';
            } elseif (!empty($data['start_date']) && 
                     strtotime($data['end_date']) < strtotime($data['start_date'])) {
                $errors['end_date'] = 'Tanggal selesai harus setelah tanggal mulai';
            }
        }
        
        // Budget validation
        if (!empty($data['budget'])) {
            if (!is_numeric($data['budget']) || $data['budget'] < 0) {
                $errors['budget'] = 'Anggaran harus berupa angka positif';
            }
        }
        
        // Status validation
        if (!empty($data['status'])) {
            $validStatuses = ['planned', 'ongoing', 'completed', 'cancelled'];
            if (!in_array($data['status'], $validStatuses)) {
                $errors['status'] = 'Status tidak valid';
            }
        }
        
        return $errors;
    }
}

if (!function_exists('validate_member_data')) {
    
    function validate_member_data($data)
    {
        $errors = [];
        
        // Required fields
        $required = ['full_name', 'phone', 'email'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus diisi';
            }
        }
        
        // Email validation
        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format email tidak valid';
            }
        }
        
        // Phone validation (Indonesian format)
        if (!empty($data['phone'])) {
            if (!preg_match('/^(\+62|0)[0-9]{9,12}$/', $data['phone'])) {
                $errors['phone'] = 'Format nomor telepon tidak valid (contoh: 081234567890)';
            }
        }
        
        // Date of birth validation
        if (!empty($data['date_of_birth'])) {
            if (!strtotime($data['date_of_birth'])) {
                $errors['date_of_birth'] = 'Format tanggal lahir tidak valid';
            } elseif (strtotime($data['date_of_birth']) > strtotime('today')) {
                $errors['date_of_birth'] = 'Tanggal lahir tidak boleh di masa depan';
            }
        }
        
        return $errors;
    }
}

if (!function_exists('group_services_by_month')) {
    
    function group_services_by_month($services)
    {
        $grouped = [];
        
        foreach ($services as $service) {
            $monthYear = date('Y-m', strtotime($service['date']));
            
            if (!isset($grouped[$monthYear])) {
                $grouped[$monthYear] = [
                    'month' => date('n', strtotime($service['date'])),
                    'year' => date('Y', strtotime($service['date'])),
                    'month_name' => get_indonesian_month(date('n', strtotime($service['date']))),
                    'services' => []
                ];
            }
            
            $grouped[$monthYear]['services'][] = $service;
        }
        
        // Sort by date descending
        krsort($grouped);
        
        return $grouped;
    }
}

if (!function_exists('get_indonesian_month')) {
    
    function get_indonesian_month($monthNumber)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$monthNumber] ?? '';
    }
}

if (!function_exists('generate_church_calendar')) {
    
    function generate_church_calendar($month = null, $year = null, $events = [])
    {
        if (!$month) $month = date('n');
        if (!$year) $year = date('Y');
        
        // First day of the month
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = date('t', $firstDay);
        $dayOfWeek = date('w', $firstDay); // 0=Sunday, 6=Saturday
        
        // Adjust for Monday as first day (1=Monday, 7=Sunday)
        $dayOfWeek = $dayOfWeek == 0 ? 6 : $dayOfWeek - 1;
        
        $calendar = [];
        $week = [];
        
        // Add empty days for first week
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $week[] = [
                'day' => '',
                'date' => null,
                'events' => []
            ];
        }
        
        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
            $isToday = ($currentDate == date('Y-m-d'));
            
            // Get events for this day
            $dayEvents = [];
            foreach ($events as $event) {
                if ($event['date'] == $currentDate) {
                    $dayEvents[] = $event;
                }
            }
            
            $week[] = [
                'day' => $day,
                'date' => $currentDate,
                'is_today' => $isToday,
                'events' => $dayEvents,
                'has_events' => !empty($dayEvents)
            ];
            
            // Start new week
            if (count($week) == 7) {
                $calendar[] = $week;
                $week = [];
            }
        }
        
        // Add empty days for last week
        if (!empty($week)) {
            while (count($week) < 7) {
                $week[] = [
                    'day' => '',
                    'date' => null,
                    'events' => []
                ];
            }
            $calendar[] = $week;
        }
        
        return [
            'calendar' => $calendar,
            'month' => $month,
            'year' => $year,
            'month_name' => get_indonesian_month($month),
            'days_of_week' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
        ];
    }
}

if (!function_exists('get_service_types')) {
    
    function get_service_types()
    {
        return [
            'sunday_morning' => 'Ibadah Minggu Pagi',
            'classical' => 'Ibadah Tukar Mimbar Klasis ',
        ];
    }
}

if (!function_exists('get_program_categories')) {
    
    function get_program_categories()
    {
        return [
            'worship' => 'Ibadah & Liturgi',
            'education' => 'Pendidikan & Katekisasi',
            'youth' => 'Pemuda & Remaja',
            'children' => 'Anak & Sekolah Minggu',
            'administration' => 'Administrasi & Keuangan',
            'facility' => 'Fasilitas & Pemeliharaan',
        ];
    }
}

if (!function_exists('get_departments')) {
    
    function get_departments()
    {
        return [
            'pastoral' => 'Pastoral',
            'worship' => 'Bidang Ibadah',
            'children' => 'Komisi Anak',
            'youth' => 'Komisi Pemuda, dan Remaja',
            'womens' => 'Komisi Wanita Jemaat',
            'elderely' => 'Komisi Adiyuswa',
            'death' => 'Komisi Pralaya',
            'wealth' => 'Komisi Kehartaan',
            'verification' => 'Komisi Verifikasi',
        ];
    }
}

if (!function_exists('get_officer_roles')) {
    /**
     * Get church officer roles
     * 
     * @return array
     */
    function get_officer_roles()
    {
        return [
            'pastor' => 'Pendeta',
            'elder' => 'Penatua',
            'deacon' => 'Diaken',
            'musician' => 'Pemusik',
            'worship_leader' => 'Pemandu Pujian',
            'reader' => 'Pembaca Alkitab',
        ];
    }
}

if (!function_exists('filter_upcoming_events')) {
    
    function filter_upcoming_events($events, $daysAhead = 30)
    {
        $upcoming = [];
        $today = date('Y-m-d');
        $cutoffDate = date('Y-m-d', strtotime("+{$daysAhead} days"));
        
        foreach ($events as $event) {
            if (!empty($event['date']) && 
                $event['date'] >= $today && 
                $event['date'] <= $cutoffDate) {
                $upcoming[] = $event;
            }
        }
        
        // Sort by date
        usort($upcoming, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        return $upcoming;
    }
}

if (!function_exists('generate_monthly_report_data')) {
    
    function generate_monthly_report_data($month = null, $year = null)
    {
        if (!$month) $month = date('n');
        if (!$year) $year = date('Y');
        
        return [
            'periode' => [
                'month'      => $month,
                'year'       => $year,
                'month_name' => get_indonesian_month($month)
            ]
        ];
    }
}

if (!function_exists('export_church_data')) {
    
    function export_church_data($data, $format = 'csv', $type = 'services')
    {
        switch ($format) {
            case 'csv':
                return export_to_csv($data, $type);
                
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            case 'xml':
                return export_to_xml($data, $type);
                
            default:
                return $data;
        }
    }
}

if (!function_exists('export_to_csv')) {
    
    function export_to_csv($data, $type)
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fwrite($output, "\xEF\xBB\xBF");
        
        // Write headers
        if (!empty($data[0])) {
            fputcsv($output, array_keys($data[0]));
        }
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}

if (!function_exists('generate_church_summary')) {
    
    function generate_church_summary($periodType = 'monthly', $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $period = calculate_church_period($periodType, $date);
        
        $jadwalModel = new \App\Models\JadwalModel\JadwalIbadahModel();
        $prokerModel = new \App\Models\ProkerModel\ProkerModel();
        
        $jadwal = $jadwalModel->where('tanggal >=', $period['start'])
                              ->where('tanggal <=', $period['end'])
                              ->findAll();
        
        $proker = $prokerModel->findAll();
        
        return [
            'period'  => $period,
            'jadwal'  => $jadwal,
            'proker'  => $proker,
        ];
    }
}