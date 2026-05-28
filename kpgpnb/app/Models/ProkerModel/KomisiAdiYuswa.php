<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiAdiYuswa extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_adi_yuswa';

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'assistant_leader_id',
        'email',
        'phone',
        'meeting_schedule',
        'meeting_location',
        'vision',
        'mission',
        'goals',
        'is_active',
        'established_date',
        'logo',
        'age_range_min',
        'total_members',
        'health_programs',
        'social_activities',
        'caregiver_count'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'health_programs' => 'array',
        'social_activities' => 'array',
        'age_range_min' => 'integer',
        'total_members' => 'integer',
        'caregiver_count' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan program kesehatan
    public function healthPrograms()
    {
        return $this->hasMany(HealthProgram::class, 'komisi_adi_yuswa_id');
    }

    // Relationship dengan pengasuh
    public function caregivers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'komisi_adi_yuswa_caregivers')
                    ->withPivot('role', 'join_date', 'expertise')
                    ->withTimestamps();
    }

    // Relationship dengan kunjungan
    public function visits()
    {
        return $this->hasMany(ElderlyVisit::class, 'komisi_adi_yuswa_id');
    }

    // Get active health programs
    public function activeHealthPrograms()
    {
        return $this->healthPrograms()->where('is_active', true);
    }

    // Get upcoming visits
    public function upcomingVisits()
    {
        return $this->visits()
                    ->where('visit_date', '>=', now())
                    ->orderBy('visit_date')
                    ->limit(5);
    }
}