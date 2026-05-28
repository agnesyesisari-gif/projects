<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersekutuanDoa extends Model
{
    use HasFactory;

    protected $table = 'persekutuan_doa';

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'assistant_leader_id',
        'komisi_id',
        'komisi_type',
        'meeting_schedule',
        'meeting_location',
        'prayer_focus',
        'total_members',
        'is_active',
        'established_date',
        'prayer_requests'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'prayer_requests' => 'array',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan leader
    public function leader()
    {
        return $this->belongsTo(\App\Models\User::class, 'leader_id');
    }

    // Relationship dengan asisten leader
    public function assistantLeader()
    {
        return $this->belongsTo(\App\Models\User::class, 'assistant_leader_id');
    }

    // Relationship dengan anggota
    public function members()
    {
        return $this->belongsToMany(\App\Models\User::class, 'persekutuan_doa_members')
                    ->withPivot('join_date', 'is_active')
                    ->withTimestamps();
    }

    // Relationship dengan pertemuan doa
    public function prayerMeetings()
    {
        return $this->hasMany(PrayerMeeting::class, 'persekutuan_doa_id');
    }

    // Polymorphic relationship dengan komisi
    public function komisi()
    {
        return $this->morphTo();
    }

    // Scope aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get active members
    public function activeMembers()
    {
        return $this->members()->wherePivot('is_active', true);
    }

    // Get upcoming prayer meetings
    public function upcomingPrayerMeetings()
    {
        return $this->prayerMeetings()
                    ->where('meeting_date', '>=', now())
                    ->orderBy('meeting_date')
                    ->limit(5);
    }

    // Get recent prayer requests
    public function recentPrayerRequests($limit = 10)
    {
        return $this->prayerMeetings()
                    ->with('prayerRequests')
                    ->get()
                    ->pluck('prayerRequests')
                    ->flatten()
                    ->sortByDesc('created_at')
                    ->take($limit);
    }
}