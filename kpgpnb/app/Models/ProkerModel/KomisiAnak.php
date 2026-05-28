<?php

namespace App\Models\Komisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomisiAnak extends BaseKomisi
{
    use HasFactory;

    protected $table = 'komisi_anak';

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
        'total_children',
        'teacher_count',
        'curriculum',
        'class_rooms'
    ];

    protected $casts = [
        'meeting_schedule' => 'array',
        'goals' => 'array',
        'class_rooms' => 'array',
        'age_range_min' => 'integer',
        'age_range_max' => 'integer',
        'total_children' => 'integer',
        'teacher_count' => 'integer',
        'is_active' => 'boolean',
        'established_date' => 'date'
    ];

    // Relationship dengan guru
    public function teachers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'komisi_anak_teachers')
                    ->withPivot('class_room', 'subject', 'join_date')
                    ->withTimestamps();
    }

    // Relationship dengan kelas
    public function classes()
    {
        return $this->hasMany(KomisiAnakClass::class, 'komisi_anak_id');
    }

    // Scope untuk usia tertentu
    public function scopeByAgeRange($query, $minAge, $maxAge)
    {
        return $query->where('age_range_min', '>=', $minAge)
                    ->where('age_range_max', '<=', $maxAge);
    }

    // Get total teachers
    public function getTotalTeachersAttribute()
    {
        return $this->teachers()->count();
    }
}