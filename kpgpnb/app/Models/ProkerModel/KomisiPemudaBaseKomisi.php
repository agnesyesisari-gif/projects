<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiPemuda extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_pemuda';

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
        'age_range_max',
        'total_members',
        'division_count',
        'social_media_links'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'social_media_links' => 'array',
        'age_range_min' => 'integer',
        'age_range_max' => 'integer',
        'total_members' => 'integer',
        'division_count' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan divisi
    public function divisions()
    {
        return $this->hasMany(KomisiPemudaDivision::class, 'komisi_pemuda_id');
    }

    // Relationship dengan event khusus pemuda
    public function youthEvents()
    {
        return $this->hasMany(YouthEvent::class, 'komisi_pemuda_id');
    }

    // Scope untuk rentang usia
    public function scopeByAgeGroup($query, $ageGroup)
    {
        return $query->where('age_range_min', '>=', $ageGroup['min'])
                    ->where('age_range_max', '<=', $ageGroup['max']);
    }

    // Get upcoming youth events
    public function upcomingYouthEvents()
    {
        return $this->youthEvents()
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date')
                    ->limit(5);
    }
}