<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiVerifikasi extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_verifikasi';

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
        'verification_types',
        'pending_requests',
        'approved_requests',
        'rejected_requests'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'verification_types' => 'array',
        'pending_requests' => 'integer',
        'approved_requests' => 'integer',
        'rejected_requests' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan permintaan verifikasi
    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class, 'komisi_verifikasi_id');
    }

    // Relationship dengan auditor
    public function auditors()
    {
        return $this->belongsToMany(\App\Models\User::class, 'komisi_verifikasi_auditors')
                    ->withPivot('expertise', 'certification', 'join_date')
                    ->withTimestamps();
    }

    // Relationship dengan laporan
    public function reports()
    {
        return $this->hasMany(VerificationReport::class, 'komisi_verifikasi_id');
    }

    // Scope untuk permintaan tertunda
    public function scopeWithPendingRequests($query)
    {
        return $query->where('pending_requests', '>', 0);
    }

    // Get pending verification requests
    public function pendingVerificationRequests()
    {
        return $this->verificationRequests()
                    ->where('status', 'pending')
                    ->orderBy('created_at');
    }

    // Get recent reports
    public function recentReports($limit = 5)
    {
        return $this->reports()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}