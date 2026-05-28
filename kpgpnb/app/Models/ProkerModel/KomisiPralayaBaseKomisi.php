<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiPralaya extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_pralaya';

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
        'service_type',
        'service_schedule',
        'choir_members_count',
        'music_instruments',
        'repertoire_count'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'service_schedule' => 'array',
        'music_instruments' => 'array',
        'choir_members_count' => 'integer',
        'repertoire_count' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan anggota paduan suara
    public function choirMembers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'komisi_pralaya_choir_members')
                    ->withPivot('voice_type', 'join_date', 'is_active')
                    ->withTimestamps();
    }

    // Relationship dengan repertoar
    public function repertoires()
    {
        return $this->hasMany(ChoirRepertoire::class, 'komisi_pralaya_id');
    }

    // Relationship dengan alat musik
    public function instruments()
    {
        return $this->hasMany(MusicInstrument::class, 'komisi_pralaya_id');
    }

    // Scope berdasarkan jenis suara
    public function scopeByVoiceType($query, $voiceType)
    {
        return $query->whereHas('choirMembers', function ($q) use ($voiceType) {
            $q->where('voice_type', $voiceType);
        });
    }

    // Get active choir members
    public function activeChoirMembers()
    {
        return $this->choirMembers()->wherePivot('is_active', true);
    }

    // Get recent repertoires
    public function recentRepertoires($limit = 10)
    {
        return $this->repertoires()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}