<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\IbadahNotification;

class Ibadah extends Entity
{

    protected $datamap = [
        'id' => 'ibadah_id',
        'tanggal' => 'ibadah_tanggal',
        'waktu' => 'ibadah_waktu',
        'nama' => 'nama_ibadah',
        'jenis' => 'jenis_ibadah',
        'tempat' => 'ibadah_tempat',
        'pemimpin_ibadah' => 'pemimpin_ibadah',
        'pemusik' => 'pemusik_ibadah',
        'pemandu_pujian' => 'pemandu_ibadah',
        'operator_LCD' => 'operator_LCD',
        'operator_sound' => 'operator_sound',
        'penatua' => 'penatua_ibadah',
        'diaken' => 'diaken_ibadah',
        'tema' => 'tema_ibadah',
        'bacaan' => 'bacaan_ibadah',
        'status' => 'status_ibadah',
        'keterangan' => 'keterangan_ibadah',
    ];
    
    protected $dates = [
        'created_at', 
        'updated_at', 
        'deleted_at', 
        'ibadah_tanggal'
    ];
    
    protected $casts = [
        'ibadah_id' => 'integer',
        'pendeta_id' => 'integer',
        'tukar_mimbar_id' => 'integer',
        'jumlah_hadir' => 'integer',
        'jumlah_pelayan' => 'integer',
        'is_tukar_mimbar' => 'boolean',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
        'metadata' => 'json-array'
    ];
    
    protected $castHandlers = [
        'json-array' => \CodeIgniter\Entity\Cast\JsonCast::class
    ];
    
    const KLASIFIKASI_MINGGU = 'minggu';
    const KLASIFIKASI_KHOTBAH_KLASIS = 'khotbah_klasis';
    
    
    const STATUS_DRAFT = 'draft';
    const STATUS_TERJADWAL = 'terjadwal';
    const STATUS_BERLANGSUNG = 'berlangsungng';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';
    
    const HARI_MINGGU = 'Minggu';
    const HARI_SENIN = 'Senin';
    const HARI_SELASA = 'Selasa';
    const HARI_RABU = 'Rabu';
    const HARI_KAMIS = 'Kamis';
    const HARI_JUMAT = 'Jumat';
    const HARI_SABTU = 'Sabtu';
    
    public function getTanggalFormatted(string $format = 'd F Y'): ?string
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return null;
        }
        
        $date = $this->mutateDate($this->attributes['ibadah_tanggal']);
        return $date->format($format);
    }
    
    public function getHari(): string
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return '';
        }
        
        $date = $this->mutateDate($this->attributes['ibadah_tanggal']);
        $dayNumber = (int) $date->format('w');
        
        $days = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 
            'Kamis', 'Jumat', 'Sabtu'
        ];
        
        return $days[$dayNumber] ?? '';
    }
    
    public function getWaktuLengkap(): string
    {
        $waktu = $this->attributes['ibadah_waktu'] ?? '09:00';
        $timezone = $this->attributes['timezone'] ?? 'WITA';
        
        return $waktu . ' ' . $timezone;
    }
    
    public function getTanggalWaktuLengkap(): string
    {
        $tanggal = $this->getTanggalFormatted();
        $waktu = $this->getWaktuLengkap();
        
        return $tanggal . ', ' . $waktu;
    }
    
    public function isPast(): bool
    {
        if (!$this->attributes['ibadah_tanggal'] || !$this->attributes['ibadah_waktu']) {
            return false;
        }
        
        $ibadahDateTime = $this->attributes['ibadah_tanggal'] . ' ' . $this->attributes['ibadah_waktu'];
        $today = date('Y-m-d H:i:s');
        
        return strtotime($ibadahDateTime) < strtotime($today);
    }
    
    public function isOngoing(int $toleranceMenit = 30): bool
    {
        if (!$this->attributes['ibadah_tanggal'] || !$this->attributes['ibadah_waktu']) {
            return false;
        }
        
        $ibadahDateTime = $this->attributes['ibadah_tanggal'] . ' ' . $this->attributes['ibadah_waktu'];
        $now = time();
        $ibadahTime = strtotime($ibadahDateTime);
        $toleranceSeconds = $toleranceMenit * 60;
        
        $startTime = $ibadahTime - $toleranceSeconds;
        $endTime = $ibadahTime + (3 * 3600); // Asumsi ibadah maksimal 3 jam
        
        return $now >= $startTime && $now <= $endTime;
    }
    
    public function isThisWeek(): bool
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return false;
        }
        
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->attributes['ibadah_tanggal'] >= $startOfWeek && 
               $this->attributes['ibadah_tanggal'] <= $endOfWeek;
    }
    
    public function isThisMonth(): bool
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return false;
        }
        
        $currentMonth = date('Y-m');
        $ibadahMonth = date('Y-m', strtotime($this->attributes['ibadah_tanggal']));
        
        return $ibadahMonth === $currentMonth;
    }
    
    public function isToday(): bool
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return false;
        }
        
        $today = date('Y-m-d');
        return $this->attributes['ibadah_tanggal'] === $today;
    }
    
    public function isTomorrow(): bool
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return false;
        }
        
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        return $this->attributes['ibadah_tanggal'] === $tomorrow;
    }
    
    public function getStatusInfo(): array
    {
        $status = $this->attributes['status_ibadah'] ?? self::STATUS_DRAFT;
        
        $statusInfo = [
            self::STATUS_DRAFT => [
                'text' => 'Draft',
                'color' => 'secondary',
                'badge' => 'badge-secondary',
                'icon' => 'draft'
            ],
            self::STATUS_TERJADWAL => [
                'text' => 'Terjadwal',
                'color' => 'info',
                'badge' => 'badge-info',
                'icon' => 'event'
            ],
            self::STATUS_BERLANGSUNG => [
                'text' => 'Berlangsung',
                'color' => 'warning',
                'badge' => 'badge-warning',
                'icon' => 'schedule'
            ],
            self::STATUS_SELESAI => [
                'text' => 'Selesai',
                'color' => 'success',
                'badge' => 'badge-success',
                'icon' => 'done_all'
            ],
            self::STATUS_DIBATALKAN => [
                'text' => 'Dibatalkan',
                'color' => 'danger',
                'badge' => 'badge-danger',
                'icon' => 'cancel'
            ]
        ];
        
        return $statusInfo[$status] ?? $statusInfo[self::STATUS_DRAFT];
    }
    
    public function getKlasifikasiText(): string
    {
        $klasifikasi = $this->attributes['klasifikasi'] ?? self::KLASIFIKASI_MINGGU_BIASA;
        
        $klasifikasiText = [
            self::KLASIFIKASI_MINGGU => 'Ibadah Minggu',
            self::KLASIFIKASI_KHOTBAH_KLASIS => 'Ibadah Tukar Mimbar Klasis',
        ];
        
        return $klasifikasiText[$klasifikasi] ?? 'Ibadah';
    }
    
    public function isTukarMimbar(): bool
    {
        return (bool) ($this->attributes['is_tukar_mimbar'] ?? false);
    }
    
    public function getTukarMimbarInfo(): ?array
    {
        if (!$this->isTukarMimbar()) {
            return null;
        }
        
        $tukarId = $this->attributes['tukar_mimbar_id'] ?? null;
        if (!$tukarId) {
            return null;
        }
        
        // Load model tukar mimbar
        $tukarModel = model('TukarMimbarModel');
        $tukarData = $tukarModel->find($tukarId);
        
        if (!$tukarData) {
            return null;
        }
        
        return [
            'id' => $tukarData->tukar_id,
            'pendeta_id' => $tukarData->pendeta_id,
            'gereja_tujuan_id' => $tukarData->gereja_tujuan_id,
            'status' => $tukarData->status,
        ];
    }
    
    public function generateKodeIbadah(): string
    {
        $tanggal = $this->attributes['ibadah_tanggal'] ?? date('Y-m-d');
        $waktu = $this->attributes['ibadah_waktu'] ?? '09:00';
        $gerejaId = $this->attributes['gereja_id'] ?? '000';
        
        $datePart = str_replace('-', '', $tanggal);
        $timePart = str_replace(':', '', $waktu);
        
        return 'IBD-' . $datePart . '-' . $timePart . '-' . str_pad($gerejaId, 3, '0', STR_PAD_LEFT);
    }
    
    public function getWaktuSelesai(): ?string
    {
        if (!$this->attributes['ibadah_tanggal'] || !$this->attributes['ibadah_waktu']) {
            return null;
        }
        
        $startTime = strtotime($this->attributes['ibadah_tanggal'] . ' ' . $this->attributes['ibadah_waktu']);
        $duration = $this->getDurasiMenit() * 60; // Convert ke detik
        $endTime = $startTime + $duration;
        
        return date('H:i', $endTime);
    }
    
    public function canBeModified(int $minimalHari = 1): bool
    {
        if (!$this->attributes['ibadah_tanggal']) {
            return true;
        }
        
        $ibadahDate = strtotime($this->attributes['ibadah_tanggal']);
        $today = strtotime(date('Y-m-d'));
        $diffDays = ($ibadahDate - $today) / (60 * 60 * 24);
        
        return $diffDays >= $minimalHari;
    }
    
    public function canBeCancelled(): bool
    {
        if ($this->attributes['status_ibadah'] === self::STATUS_COMPLETED ||
            $this->attributes['status_ibadah'] === self::STATUS_CANCELLED) {
            return false;
        }
        
        return $this->canBeModified(0); // Bisa dibatalkan hari H juga
    }
    
    public function getNotificationData(string $jenis = 'reminder'): array
    {
        $data = [
            'ibadah_id' => $this->attributes['ibadah_id'] ?? null,
            'tanggal' => $this->getTanggalFormatted(),
            'waktu' => $this->getWaktuLengkap(),
            'nama_ibadah' => $this->generateNamaIbadah(),
            'jenis_ibadah' => $this->generateJenisIbadah(),
            'tempat' => $this->attributes['ibadah_tempat'] ?? '',
            'pemimpin_ibadah' => $this->generatePemimpinIbadah(),
            'pemusik' => $this->generatePemusikIbadah(),
            'pemandu_pujian' => $this->generatePemanduPujianIbadah(),
            'operator_LCD' => $this->generateOperatorLCD(),
            'operator_sound' => $this->generateOperatorSound(),
            'penatua' => $this->generatePenatuaIbadah(),
            'diaken' => $this->generateDiakenIbadah(),
            'tema' => $this->attributes['tema'] ?? '',
            'bacaan' => $this->generateBacaanIbadah(),
            'status' => $this->generateStatusIbadah(),
            'keterangan' => $this->generateKeteranganIbadah()
        ];
        
        // Tambahkan info tukar mimbar jika ada
        if ($this->isTukarMimbar()) {
            $tukarInfo = $this->getTukarMimbarInfo();
            if ($tukarInfo) {
                $data['tukar_mimbar'] = $tukarInfo;
            }
        }
        
        return $data;
    }

    public function getNamaPendeta(): string
    {
        $pendetaId = $this->attributes['pendeta_id'] ?? null;
        if (!$pendetaId) {
            return 'Belum ditentukan';
        }
        
        // Load model pelayan
        $pelayanModel = model('PelayanModel');
        $pendeta = $pelayanModel->where('pelayan_id', $pendetaId)
                                ->where('jabatan', 'pendeta')
                                ->first();
        
        return $pendeta ? $pendeta->pelayan_nama : 'Belum ditentukan';
    }
    
    public function getPelayanList(): array
    {
        $ibadahId = $this->attributes['ibadah_id'] ?? null;
        if (!$ibadahId) {
            return [];
        }
        
        // Load model jadwal pelayan
        $jadwalModel = model('JadwalPelayanModel');
        $pelayanList = $jadwalModel->getPelayanByIbadah($ibadahId);
        
        $result = [];
        foreach ($pelayanList as $pelayan) {
            $result[] = [
                'id' => $pelayan->pelayan_id,
                'nama' => $pelayan->pelayan_nama,
                'jabatan' => $pelayan->jabatan_pelayanan,
                'is_confirmed' => $pelayan->is_confirmed ?? false
            ];
        }
        
        return $result;
    }
    
    public function validate(): array
    {
        $errors = [];
        
        // Validasi tanggal
        if (empty($this->attributes['ibadah_tanggal'])) {
            $errors[] = 'Tanggal ibadah harus diisi';
        } elseif (strtotime($this->attributes['ibadah_tanggal']) < strtotime(date('Y-m-d'))) {
            $errors[] = 'Tanggal ibadah tidak boleh di masa lalu';
        }
        
        // Validasi waktu
        if (empty($this->attributes['ibadah_waktu'])) {
            $errors[] = 'Waktu ibadah harus diisi';
        }
        
        // Validasi tempat
        if (empty($this->attributes['ibadah_tempat'])) {
            $errors[] = 'Tempat ibadah harus diisi';
        }
        
        // Validasi tema untuk ibadah khusus
        if (in_array($this->attributes['klasifikasi'] ?? '', [
            self::KLASIFIKASI_KHOTBAH_KLASIS
        ]) && empty($this->attributes['tema'])) {
            $errors[] = 'Tema ibadah harus diisi untuk ibadah khusus';
        }
        
        return $errors;
    }
    
    public function toArray(bool $includeDetails = false): array
    {
        $data = parent::toArray();
        
        // Tambahkan field terhitung
        $data['tanggal_formatted'] = $this->getTanggalFormatted();
        $data['waktu_lengkap'] = $this->getWaktuLengkap();
        $data['status_info'] = $this->getStatusInfo();
        $data['klasifikasi_text'] = $this->getKlasifikasiText();
        $data['tempat'] = $this->getTempatIbada();
        $data['pemimpin_ibadah'] = $this->getPemimpinIbadah();
        $data['pemusik'] = $this->getPemusik();
        $data['pemandu_pujian'] = $this->getPemanduPujian();
        $data['operator_LCD'] = $this->getOperatorLCD();
        $data['operator_sound'] = $this->getOperatorSound();
        $data['penatua'] = $this->getPenatua();
        $data['diaken'] = $this->getDiaken();
        $data['tema'] = $this->getTema();
        $data['bacaan_alkitab'] = $this->getBacaanAlkitab();
        
        if ($includeDetails) {
            $data['pelayan_list'] = $this->getPelayanList();
            $data['statistik_kehadiran'] = $this->getStatistikKehadiran();
            
            if ($this->isTukarMimbar()) {
                $data['tukar_mimbar_info'] = $this->getTukarMimbarInfo();
            }
        }
        
        return $data;
    }
    
    public function __toString(): string
    {
        return sprintf(
            'Ibadah [%s] - %s %s di %s',
            $this->attributes['ibadah_id'] ?? 'NEW',
            $this->getTanggalFormatted(),
            $this->getWaktuLengkap(),
            $this->attributes['ibadah_tempat'] ?? 'Tempat belum ditentukan'
        );
    }
}