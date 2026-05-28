<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalIbadahModel extends Model
{
    protected $table = 'jadwal_ibadah';
    protected $primaryKey = 'id_jadwal';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'nama_ibadah',
        'jenis_ibadah',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'pemimpin_ibadah',
        'operator_LCD',
        'operator_sound',
        'khotbah',
        'bacaan_alkitab',
        'status',
        'keterangan',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'nama_ibadah' => 'required|min_length[3]|max_length[255]',
        'jenis_ibadah' => 'required',
        'tanggal' => 'required|valid_date',
        'waktu_mulai' => 'required',
        'tempat' => 'required|min_length[3]|max_length[255]',
        'pemimpin_ibadah' => 'required|min_length[3]|max_length[255]',
        'status' => 'required|in_list[terjadwal,berlangsung,selesai,dibatalkan]'
    ];
    
    protected $validationMessages = [
        'nama_ibadah' => [
            'required' => 'Nama ibadah harus diisi',
            'min_length' => 'Nama ibadah minimal 3 karakter',
            'max_length' => 'Nama ibadah maksimal 255 karakter'
        ],
        'jenis_ibadah' => [
            'required' => 'Jenis ibadah harus dipilih'
        ],
        'tanggal' => [
            'required' => 'Tanggal ibadah harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'waktu_mulai' => [
            'required' => 'Waktu mulai harus diisi'
        ],
        'tempat' => [
            'required' => 'Tempat ibadah harus diisi',
            'min_length' => 'Tempat minimal 3 karakter',
            'max_length' => 'Tempat maksimal 255 karakter'
        ],
        'pemimpin_ibadah' => [
            'required' => 'Pemimpin ibadah harus diisi',
            'min_length' => 'Nama pemimpin minimal 3 karakter',
            'max_length' => 'Nama pemimpin maksimal 255 karakter'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status tidak valid'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Mendapatkan semua jadwal ibadah dengan filter optional
     */
    public function getAllJadwal($params = [])
    {
        $builder = $this->builder();
        
        // Filter berdasarkan bulan dan tahun
        if (!empty($params['bulan']) && !empty($params['tahun'])) {
            $builder->where('MONTH(tanggal)', $params['bulan'])
                   ->where('YEAR(tanggal)', $params['tahun']);
        }
        
        // Filter berdasarkan jenis ibadah
        if (!empty($params['jenis_ibadah'])) {
            $builder->where('jenis_ibadah', $params['jenis_ibadah']);
        }
        
        // Filter berdasarkan status
        if (!empty($params['status'])) {
            $builder->where('status', $params['status']);
        }
        
        // Filter berdasarkan rentang tanggal
        if (!empty($params['start_date']) && !empty($params['end_date'])) {
            $builder->where('tanggal >=', $params['start_date'])
                   ->where('tanggal <=', $params['end_date']);
        }
        
        // Order by tanggal dan waktu
        $builder->orderBy('tanggal', 'ASC')
               ->orderBy('waktu_mulai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Mendapatkan jadwal ibadah yang akan datang
     */
    public function getUpcomingJadwal($limit = 5)
    {
        $today = date('Y-m-d');
        
        return $this->where('tanggal >=', $today)
                   ->where('status', 'terjadwal')
                   ->orderBy('tanggal', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Mendapatkan jadwal ibadah berdasarkan tanggal
     */
    public function getJadwalByDate($date)
    {
        return $this->where('tanggal', $date)
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }
    
    /**
     * Mendapatkan jadwal ibadah untuk bulan tertentu
     */
    public function getJadwalByMonth($month, $year)
    {
        return $this->where('MONTH(tanggal)', $month)
                   ->where('YEAR(tanggal)', $year)
                   ->orderBy('tanggal', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }
    
    /**
     * Mendapatkan statistik jadwal ibadah
     */
    public function getStatistik($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->builder();
        $builder->select("
            jenis_ibadah,
            COUNT(*) as total,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status = 'terjadwal' THEN 1 ELSE 0 END) as terjadwal,
            SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as dibatalkan
        ");
        $builder->where('YEAR(tanggal)', $tahun);
        $builder->groupBy('jenis_ibadah');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Update status jadwal otomatis berdasarkan tanggal
     */
    public function updateStatusOtomatis()
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');
        
        // Update jadwal yang sudah lewat menjadi selesai
        $this->where('tanggal <', $today)
            ->where('status', 'terjadwal')
            ->set(['status' => 'selesai'])
            ->update();
            
        // Update jadwal hari ini yang sudah lewat waktunya
        $this->where('tanggal', $today)
            ->where('waktu_selesai <', $now)
            ->where('status', 'berlangsung')
            ->set(['status' => 'selesai'])
            ->update();
            
        // Update jadwal hari ini yang sedang berlangsung
        $this->where('tanggal', $today)
            ->where('waktu_mulai <=', $now)
            ->where('waktu_selesai >=', $now)
            ->where('status', 'terjadwal')
            ->set(['status' => 'berlangsung'])
            ->update();
    }
    
    /**
     * Mendapatkan jadwal untuk kalender
     */
    public function getJadwalForCalendar($start, $end)
    {
        $builder = $this->builder();
        $builder->select("
            id_jadwal,
            nama_ibadah,
            jenis_ibadah,
            tanggal as start,
            CONCAT(tanggal, 'T', waktu_selesai) as end,
            tempat,
            pemimpin_ibadah,
            status,
            waktu_mulai,
            waktu_selesai
        ");
        $builder->where('tanggal >=', $start);
        $builder->where('tanggal <=', $end);
        $builder->orderBy('tanggal', 'ASC');
        
        $results = $builder->get()->getResultArray();
        
        // Format untuk fullcalendar
        $formatted = [];
        foreach ($results as $row) {
            $formatted[] = [
                'id' => $row['id_jadwal'],
                'title' => $row['nama_ibadah'] . ' - ' . $row['tempat'],
                'start' => $row['start'] . 'T' . $row['waktu_mulai'],
                'end' => $row['end'],
                'extendedProps' => [
                    'jenis_ibadah' => $row['jenis_ibadah'],
                    'pemimpin' => $row['pemimpin_ibadah'],
                    'status' => $row['status'],
                    'tempat' => $row['tempat']
                ],
                'className' => 'jadwal-' . $row['jenis_ibadah'] . ' status-' . $row['status']
            ];
        }
        
        return $formatted;
    }
    
    /**
     * Cek apakah ada jadwal bentrok di waktu dan tempat yang sama
     */
    public function checkJadwalBentrok($tanggal, $waktu_mulai, $waktu_selesai, $tempat, $exclude_id = null)
    {
        $builder = $this->builder();
        $builder->where('tanggal', $tanggal);
        $builder->where('tempat', $tempat);
        $builder->where('status !=', 'dibatalkan');
        
        // Logika cek waktu bentrok
        $builder->groupStart();
        $builder->where("(
            (waktu_mulai <= '{$waktu_mulai}' AND waktu_selesai > '{$waktu_mulai}') OR
            (waktu_mulai < '{$waktu_selesai}' AND waktu_selesai >= '{$waktu_selesai}') OR
            (waktu_mulai >= '{$waktu_mulai}' AND waktu_selesai <= '{$waktu_selesai}')
        )");
        $builder->groupEnd();
        
        if ($exclude_id) {
            $builder->where('id_jadwal !=', $exclude_id);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Mendapatkan jenis-jenis ibadah yang tersedia
     */
    public function getJenisIbadahOptions()
    {
        return [
            'minggu_pagi' => 'Ibadah Minggu Pagi',
            'minggu_sore' => 'Ibadah Minggu Sore',
            'doa_senin' => 'Persekutuan Doa Senin',
            'anak' => 'Ibadah Anak',
            'pemuda' => 'Ibadah Pemuda',
            'wanita' => 'Persekutuan Wanita',
            'lainnya' => 'Lainnya'
        ];
    }
    
    /**
     * Mendapatkan jumlah kehadiran jemaat per jadwal
     * Note: Method ini berasumsi ada tabel kehadiran terpisah
     */
    public function getJumlahKehadiran($id_jadwal)
    {
        // Contoh jika ada model KehadiranModel
        // $kehadiranModel = new \App\Models\KehadiranModel();
        // return $kehadiranModel->where('id_jadwal', $id_jadwal)->countAllResults();
        
        return 0; // Placeholder
    }
}