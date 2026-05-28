<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPA extends Model
{
    use HasFactory;

    protected $table = 'kelompok_pa';

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'assistant_leader_id',
        'komisi_id',
        'komisi_type',
        'meeting_schedule',
        'meeting_location',
        'study_topic',
        'current_book',
        'total_members',
        'is_active',
        'established_date'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
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
        return $this->belongsToMany(\App\Models\User::class, 'kelompok_pa_members')
                    ->withPivot('join_date', 'is_active')
                    ->withTimestamps();
    }

    // Relationship dengan sesi PA
    public function studySessions()
    {
        return $this->hasMany(StudySession::class, 'kelompok_pa_id');
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

    // Get upcoming study sessions
    public function upcomingStudySessions()
    {
        return $this->studySessions()
                    ->where('session_date', '>=', now())
                    ->orderBy('session_date')
                    ->limit(5);
    }
}