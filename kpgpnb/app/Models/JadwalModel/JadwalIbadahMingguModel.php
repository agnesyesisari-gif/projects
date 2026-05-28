<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalIbadahMingguModel extends Model
{
    protected $table = 'jadwal_ibadah_minggu';
    protected $primaryKey = 'id_jadwal';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'tanggal',
        'waktu',
        'tempat',
        'nama_ibadah',
        'pembicara',
        'keterangan',
        'jenis_ibadah',
        'kuota_jemaat',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'tanggal' => 'required|valid_date',
        'waktu' => 'required',
        'tempat' => 'required|max_length[100]',
        'nama_ibadah' => 'required|max_length[100]',
        'pemimpin_ibadah' => 'required|max_length[100]',
        'jenis_ibadah' => 'required|max_length[50]',
        'status' => 'required|in_list[aktif,selesai,dibatalkan]'
    ];
    
    protected $validationMessages = [
        'tanggal' => [
            'required' => 'Tanggal ibadah harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'waktu' => [
            'required' => 'Waktu ibadah harus diisi'
        ],
        'tempat' => [
            'required' => 'Tempat ibadah harus diisi',
            'max_length' => 'Tempat ibadah maksimal 100 karakter'
        ],
        'nama_ibadah' => [
            'required' => 'Nama ibadah harus diisi',
            'max_length' => 'Nama ibadah maksimal 100 karakter'
        ],
        'pemimpin_ibadah' => [
            'required' => 'Nama pemimpin ibadah harus diisi',
            'max_length' => 'Nama pemimpin ibadah maksimal 100 karakter'
        ],
        'jenis_ibadah' => [
            'required' => 'Jenis ibadah harus diisi',
            'max_length' => 'Jenis ibadah maksimal 50 karakter'
        ],
        'status' => [
            'required' => 'Status ibadah harus diisi',
            'in_list' => 'Status harus salah satu dari: aktif, selesai, dibatalkan'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all jadwal ibadah minggu with filters
     */
    public function getAllJadwal($filters = [], $limit = null, $offset = 0)
    {
        $builder = $this->builder();
        
        // Apply filters
        if (!empty($filters['tanggal'])) {
            $builder->where('DATE(tanggal)', $filters['tanggal']);
        }
        
        if (!empty($filters['bulan'])) {
            $builder->where('MONTH(tanggal)', $filters['bulan']);
        }
        
        if (!empty($filters['tahun'])) {
            $builder->where('YEAR(tanggal)', $filters['tahun']);
        }
        
        if (!empty($filters['jenis_ibadah'])) {
            $builder->where('jenis_ibadah', $filters['jenis_ibadah']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('nama_ibadah', $filters['search'])
                ->orLike('pemimpin_ibadah', $filters['search'])
                ->orLike('tempat', $filters['search'])
                ->groupEnd();
        }
        
        // Order by tanggal dan waktu
        $builder->orderBy('tanggal', 'ASC');
        $builder->orderBy('waktu', 'ASC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get jadwal aktif (mendatang)
     */
    public function getJadwalAktif($limit = 10)
    {
        $builder = $this->builder();
        $builder->where('status', 'aktif');
        $builder->where('tanggal >=', date('Y-m-d'));
        $builder->orderBy('tanggal', 'ASC');
        $builder->orderBy('waktu', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get jadwal berdasarkan rentang tanggal
     */
    public function getJadwalByDateRange($startDate, $endDate)
    {
        $builder = $this->builder();
        $builder->where('tanggal >=', $startDate);
        $builder->where('tanggal <=', $endDate);
        $builder->orderBy('tanggal', 'ASC');
        $builder->orderBy('waktu', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get jadwal untuk kalender
     */
    public function getJadwalForCalendar($year, $month)
    {
        $builder = $this->builder();
        $builder->select("
            id_jadwal,
            tanggal,
            waktu,
            nama_ibadah,
            pemimpin_ibadah,
            tempat,
            jenis_ibadah,
            status
        ");
        $builder->where('YEAR(tanggal)', $year);
        $builder->where('MONTH(tanggal)', $month);
        $builder->orderBy('tanggal', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get statistics jadwal ibadah
     */
    public function getStatistikJadwal($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }
        
        $builder = $this->builder();
        
        // Total jadwal per bulan
        $builder->select("
            MONTH(tanggal) as bulan,
            COUNT(*) as total,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as aktif,
            SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as dibatalkan
        ");
        $builder->where('YEAR(tanggal)', $year);
        $builder->groupBy('MONTH(tanggal)');
        $builder->orderBy('bulan', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Check if there's schedule conflict
     */
    public function checkScheduleConflict($tanggal, $waktu, $tempat, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('tanggal', $tanggal);
        $builder->where('waktu', $waktu);
        $builder->where('tempat', $tempat);
        $builder->where('status !=', 'dibatalkan');
        
        if ($excludeId) {
            $builder->where('id_jadwal !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Update status jadwal yang sudah lewat
     */
    public function updateStatusJadwalKadaluarsa()
    {
        $builder = $this->builder();
        $builder->set('status', 'selesai');
        $builder->where('status', 'aktif');
        $builder->where('tanggal <', date('Y-m-d'));
        $builder->where("CONCAT(tanggal, ' ', waktu) <", date('Y-m-d H:i:s'));
        
        return $builder->update();
    }
    
    /**
     * Get upcoming jadwal for notification
     */
    public function getJadwalMendatang($days = 3)
    {
        $builder = $this->builder();
        $builder->where('status', 'aktif');
        $builder->where('tanggal >=', date('Y-m-d'));
        $builder->where('tanggal <=', date('Y-m-d', strtotime("+{$days} days")));
        $builder->orderBy('tanggal', 'ASC');
        $builder->orderBy('waktu', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get jadwal by jenis ibadah
     */
    public function getJadwalByJenis($jenisIbadah, $limit = null)
    {
        $builder = $this->builder();
        $builder->where('jenis_ibadah', $jenisIbadah);
        $builder->where('status', 'aktif');
        $builder->where('tanggal >=', date('Y-m-d'));
        $builder->orderBy('tanggal', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}