<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'last_login', 'email_verified_at', 'tgl_lahir'];
    protected $casts   = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
        'must_change_password' => 'boolean',
        'login_attempts' => 'integer',
        'jemaat_id' => 'integer'
    ];
    
    protected $attributes = [
        'username' => null,
        'email' => null,
        'password' => null,
        'nama' => null,
        'jenis_kelamin' => null,
        'tempat_lahir' => null,
        'tgl_lahir' => null,
        'alamat' => null,
        'telepon' => null,
        'foto' => 'default.jpg',
        'role' => 'jemaat',
        'jemaat_id' => null,
        'komisi_id' => null,
        'sektor_id' => null,
        'is_active' => true,
        'is_admin' => false,
        'must_change_password' => false,
        'login_attempts' => 0,
        'email_verified_at' => null,
    ];
    
    // Enkripsi password otomatis saat di-set
    public function setPassword(string $password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    
    // Verifikasi password
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['password']);
    }
    
    
    // Getter untuk role label
    public function getRoleLabel(): string
    {
        $roles = [
            'admin' => 'Administrator',
            'pendeta' => 'Pendeta',
            'majelis' => 'Majelis',
            'komisi' => 'Komisi',
            'jemaat' => 'Jemaat'
        ];
        
        return $roles[$this->attributes['role']] ?? 'Jemaat';
    }
    
    // Getter untuk status aktif
    public function getStatusLabel(): string
    {
        return $this->attributes['is_active'] ? 'Aktif' : 'Non-Aktif';
    }
    
    // Getter untuk badge warna status
    public function getStatusBadge(): string
    {
        return $this->attributes['is_active'] 
            ? '<span class="badge bg-success">Aktif</span>' 
            : '<span class="badge bg-danger">Non-Aktif</span>';
    }
    
    // Getter untuk badge warna role
    public function getRoleBadge(): string
    {
        $colors = [
            'admin' => 'bg-red-100 text-red-800',
            'pendeta' => 'bg-blue-100 text-blue-800',
            'majelis' => 'bg-indigo-100 text-indigo-800',
            'komisi' => 'bg-green-100 text-green-800',
            'jemaat' => 'bg-gray-100 text-gray-800'
        ];
        
        $color = $colors[$this->attributes['role']] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $color . '">' . $this->getRoleLabel() . '</span>';
    }
    
    // Getter untuk format tanggal lahir
    public function getTglLahirFormatted(): string
    {
        if (!$this->attributes['tgl_lahir']) {
            return '-';
        }
        
        return date('d F Y', strtotime($this->attributes['tgl_lahir']));
    }
    
    // Getter untuk foto profil URL
    public function getFotoUrl(): string
    {
        if ($this->attributes['foto'] && file_exists(FCPATH . 'uploads/users/' . $this->attributes['foto'])) {
            return base_url('uploads/users/' . $this->attributes['foto']);
        }
        
        return base_url('assets/images/default-avatar.jpg');
    }
    
    // Getter untuk inisial nama
    public function getInitials(): string
    {
        $name = $this->attributes['nama_lengkap'] ?? $this->attributes['nama_panggilan'] ?? '';
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }
    
    // Cek apakah user memiliki role tertentu
    public function hasRole(string $role): bool
    {
        if ($this->attributes['is_superadmin']) {
            return true;
        }
        
        return $this->attributes['role'] === $role;
    }
    
    // Cek apakah user memiliki salah satu dari roles
    public function hasAnyRole(array $roles): bool
    {
        if ($this->attributes['is_superadmin']) {
            return true;
        }
        
        return in_array($this->attributes['role'], $roles);
    }
    
    // Cek apakah user bisa mengakses modul tertentu
    public function can(string $permission): bool
    {
        $permissions = $this->getPermissions();
        return in_array($permission, $permissions) || $this->attributes['is_superadmin'];
    }
    
    // Getter untuk permissions berdasarkan role
    public function getPermissions(): array
    {
        $permissions = [
            'admin' => [
                'manage_users', 'manage_jemaat', 'manage_komisi', 'manage_program', 
                'manage_ibadah', 'manage_keuangan', 'manage_dokumen', 'view_reports'
            ],
            'pendeta' => [
                'manage_ibadah', 'manage_khotbah', 'manage_sakramen', 'view_jemaat',
                'view_reports'
            ],
            'majelis' => [
                'manage_program', 'manage_komisi', 'view_keuangan', 'view_jemaat',
                'view_reports'
            ],
            'komisi' => [
                'manage_komisi_program', 'view_komisi_anggota', 'view_komisi_reports'
            ],
            'jemaat' => [
                'view_profile', 'edit_profile', 'view_ibadah', 'view_program'
            ]
        ];
        
        return $permissions[$this->attributes['role']] ?? [];
    }
    
    // Increment login attempts
    public function incrementLoginAttempts(): void
    {
        $this->attributes['login_attempts'] = ($this->attributes['login_attempts'] ?? 0) + 1;
    }
    
    // Reset login attempts
    public function resetLoginAttempts(): void
    {
        $this->attributes['login_attempts'] = 0;
    }
    
    // Cek apakah akun terkunci
    public function isLocked(): bool
    {
        return $this->attributes['login_attempts'] >= 5;
    }
    
    // Verifikasi email
    public function verifyEmail(): void
    {
        $this->attributes['email_verified_at'] = date('Y-m-d H:i:s');
        $this->attributes['verification_token'] = null;
    }
    
    // Cek apakah email terverifikasi
    public function isEmailVerified(): bool
    {
        return !is_null($this->attributes['email_verified_at']);
    }
    
    // Getter untuk preferences sebagai array
    public function getPreferences(): array
    {
        if (empty($this->attributes['preferences'])) {
            return [];
        }
        
        return json_decode($this->attributes['preferences'], true) ?? [];
    }
    
    // Setter untuk preferences
    public function setPreferences(array $preferences): void
    {
        $this->attributes['preferences'] = json_encode($preferences);
    }
    
    // Update single preference
    public function setPreference(string $key, $value): void
    {
        $preferences = $this->getPreferences();
        $preferences[$key] = $value;
        $this->setPreferences($preferences);
    }
    
    // Get single preference
    public function getPreference(string $key, $default = null)
    {
        $preferences = $this->getPreferences();
        return $preferences[$key] ?? $default;
    }
    
    // Get nama untuk panggilan
    public function getNamaPanggilan(): string
    {
        return $this->attributes['nama_panggilan'] ?? $this->attributes['nama_lengkap'] ?? 'Pengguna';
    }
    
    // Get keterangan komisi
    public function getKomisiInfo(): ?string
    {
        if (!$this->attributes['komisi_id']) {
            return null;
        }
        
        // Dalam implementasi sebenarnya, ini akan query ke model Komisi
        $komisiNames = [
            4 => 'Kelompok PA, dan Persekutuan Doa',
            1 => 'Komisi Anak',
            2 => 'Komisi Pemuda dan Remaja',
            3 => 'Komisi Wanita Jemaat',
            4 => 'Komisi Adiyuswa',
            4 => 'Komisi Pralaya',
            4 => 'Komisi Kehartaan',
            4 => 'Komisi Verifikasi',
        ];
        
        return $komisiNames[$this->attributes['komisi_id']] ?? null;
    }
    
    // Cek apakah user bagian dari komisi tertentu
    public function isInKomisi(int $komisiId): bool
    {
        return $this->attributes['komisi_id'] == $komisiId;
    }
    
    // Get data untuk dropdown
    public function getForDropdown(): array
    {
        return [
            'id' => $this->attributes['id'],
            'text' => $this->attributes['nama_lengkap'] . ' (' . $this->getRoleLabel() . ')'
        ];
    }
    
    // Cast ke array dengan format khusus
    public function toArray(bool $onlyChanged = false, bool $cast = true, bool $recursive = true): array
    {
        $array = parent::toArray($onlyChanged, $cast, $recursive);
        
        // Tambahkan computed properties
        $array['role_label'] = $this->getRoleLabel();
        $array['status_label'] = $this->getStatusLabel();
        $array['umur'] = $this->getUmur();
        $array['tgl_lahir_formatted'] = $this->getTglLahirFormatted();
        $array['foto_url'] = $this->getFotoUrl();
        $array['initials'] = $this->getInitials();
        $array['is_email_verified'] = $this->isEmailVerified();
        $array['is_locked'] = $this->isLocked();
        
        // Hapus sensitive data jika diperlukan
        unset($array['password']);
        unset($array['reset_token']);
        unset($array['reset_expires']);
        unset($array['verification_token']);
        
        return $array;
    }
}