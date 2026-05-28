<?php

namespace App\Models;

use CodeIgniter\Model;

class KehadiranModel extends Model
{
    protected $table = 'kehadiran';
    protected $primaryKey = 'id_kehadiran';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id_jadwal_ibadah',
        'id_jemaat',
        'status_kehadiran',
        'waktu_hadir',
        'keterangan',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'id_jadwal_ibadah' => 'required|integer',
        'id_jemaat' => 'permit_empty|integer',
        'status_kehadiran' => 'required|in_list[H,T,A,S]', // Hadir, Terlambat, Absen, Sakit
        'waktu_hadir' => 'permit_empty|valid_date',
        'keterangan' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    public function getKehadiranByJadwal($id_jadwal_ibadah)
    {
        return $this->select('kehadiran.*, jemaat.nama_lengkap, jemaat.no_anggota, keluarga.nama_keluarga')
                    ->join('jemaat', 'jemaat.id_jemaat = kehadiran.id_jemaat', 'left')
                    ->where('kehadiran.id_jadwal_ibadah', $id_jadwal_ibadah)
                    ->orderBy('kehadiran.created_at', 'DESC')
                    ->findAll();
    }
    
    public function getKehadiranByJemaat($id_jemaat, $bulan = null, $tahun = null)
    {
        $builder = $this->select('kehadiran.*, jadwal_ibadah.nama_ibadah, jadwal_ibadah.tanggal, jadwal_ibadah.waktu_mulai, jadwal_ibadah.jenis_ibadah')
                        ->join('jadwal_ibadah', 'jadwal_ibadah.id_jadwal_ibadah = kehadiran.id_jadwal_ibadah')
                        ->where('kehadiran.id_jemaat', $id_jemaat);
        
        if ($bulan && $tahun) {
            $builder->where("DATE_FORMAT(jadwal_ibadah.tanggal, '%Y-%m') = ", $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT));
        }
        
        return $builder->orderBy('jadwal_ibadah.tanggal', 'DESC')->findAll();
    }
    
    public function getStatistikKehadiran($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        // Statistics by month
        $statistik = $this->select("DATE_FORMAT(jadwal_ibadah.tanggal, '%Y-%m') as bulan, 
                                    COUNT(*) as total_hadir,
                                    SUM(CASE WHEN kehadiran.status_kehadiran = 'H' THEN 1 ELSE 0 END) as hadir,
                                    SUM(CASE WHEN kehadiran.status_kehadiran = 'T' THEN 1 ELSE 0 END) as terlambat,
                                    SUM(CASE WHEN kehadiran.status_kehadiran = 'A' THEN 1 ELSE 0 END) as absen,
                                    SUM(CASE WHEN kehadiran.status_kehadiran = 'S' THEN 1 ELSE 0 END) as sakit")
                          ->join('jadwal_ibadah', 'jadwal_ibadah.id_jadwal_ibadah = kehadiran.id_jadwal_ibadah')
                          ->where("YEAR(jadwal_ibadah.tanggal)", $tahun)
                          ->groupBy("DATE_FORMAT(jadwal_ibadah.tanggal, '%Y-%m')")
                          ->orderBy('bulan', 'ASC')
                          ->findAll();
        
        return $statistik;
    }
    
    public function getKehadiranHariIni()
    {
        $today = date('Y-m-d');
        
        return $this->select('kehadiran.*, jemaat.nama_lengkap, jadwal_ibadah.nama_ibadah')
                    ->join('jemaat', 'jemaat.id_jemaat = kehadiran.id_jemaat', 'left')
                    ->join('jadwal_ibadah', 'jadwal_ibadah.id_jadwal_ibadah = kehadiran.id_jadwal_ibadah')
                    ->where('DATE(kehadiran.created_at)', $today)
                    ->orderBy('kehadiran.created_at', 'DESC')
                    ->findAll();
    }
    
    public function sudahHadir($id_jemaat, $id_jadwal_ibadah)
    {
        return $this->where('id_jemaat', $id_jemaat)
                    ->where('id_jadwal_ibadah', $id_jadwal_ibadah)
                    ->countAllResults() > 0;
    }
    
    public function inputKehadiranBatch($data)
    {
        try {
            $this->db->transStart();
            
            foreach ($data as $kehadiran) {
                // Check if attendance already exists
                $existing = $this->where('id_jemaat', $kehadiran['id_jemaat'])
                                ->where('id_jadwal_ibadah', $kehadiran['id_jadwal_ibadah'])
                                ->first();
                
                if ($existing) {
                    // Update existing attendance
                    $this->update($existing['id_kehadiran'], $kehadiran);
                } else {
                    // Insert new attendance
                    $this->insert($kehadiran);
                }
            }
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error in inputKehadiranBatch: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getRingkasanKehadiran($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = date('Y-m-01'); // First day of current month
        }
        if (!$endDate) {
            $endDate = date('Y-m-t'); // Last day of current month
        }
        
        return $this->select("status_kehadiran, COUNT(*) as jumlah")
                    ->join('jadwal_ibadah', 'jadwal_ibadah.id_jadwal_ibadah = kehadiran.id_jadwal_ibadah')
                    ->where('jadwal_ibadah.tanggal >=', $startDate)
                    ->where('jadwal_ibadah.tanggal <=', $endDate)
                    ->groupBy('status_kehadiran')
                    ->findAll();
    }

    public function exportKehadiran($startDate, $endDate)
    {
        return $this->select('kehadiran.*, jemaat.nama_lengkap, jemaat.no_anggota, 
                             jadwal_ibadah.nama_ibadah, jadwal_ibadah.tanggal, 
                             jadwal_ibadah.waktu_mulai, keluarga.nama_keluarga')
                    ->join('jemaat', 'jemaat.id_jemaat = kehadiran.id_jemaat', 'left')
                    ->join('jadwal_ibadah', 'jadwal_ibadah.id_jadwal_ibadah = kehadiran.id_jadwal_ibadah')
                    ->where('jadwal_ibadah.tanggal >=', $startDate)
                    ->where('jadwal_ibadah.tanggal <=', $endDate)
                    ->orderBy('jadwal_ibadah.tanggal', 'ASC')
                    ->orderBy('jemaat.nama_lengkap', 'ASC')
                    ->findAll();
    }
}