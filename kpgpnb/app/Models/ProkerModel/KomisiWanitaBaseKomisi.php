<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiWanita extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_wanita';

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
        'total_members',
        'department_count',
        'social_activities',
        'prayer_groups'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'social_activities' => 'array',
        'prayer_groups' => 'array',
        'total_members' => 'integer',
        'department_count' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan departemen
    public function departments()
    {
        return $this->hasMany(KomisiWanitaDepartment::class, 'komisi_wanita_id');
    }

    // Relationship dengan kegiatan sosial
    public function socialActivities()
    {
        return $this->hasMany(SocialActivity::class, 'komisi_wanita_id');
    }

    // Relationship dengan kelompok doa
    public function prayerGroups()
    {
        return $this->hasMany(PrayerGroup::class, 'komisi_wanita_id');
    }

    // Get active prayer groups
    public function activePrayerGroups()
    {
        return $this->prayerGroups()->where('is_active', true);
    }

    // Get upcoming social activities
    public function upcomingSocialActivities()
    {
        return $this->socialActivities()
                    ->where('activity_date', '>=', now())
                    ->orderBy('activity_date')
                    ->limit(5);
    }
}