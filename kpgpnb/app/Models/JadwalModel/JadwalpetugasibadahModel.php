<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalpetugasibadahModel extends Model
{
    protected $table = 'jadwal_petugas_ibadah';
    protected $primaryKey = 'id_jadwal_petugas';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'id_ibadah',
        'id_jemaat',
        'peran',
        'keterangan',
        'status_konfirmasi',
        'tanggal_konfirmasi',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'id_ibadah' => 'required|integer',
        'id_jemaat' => 'required|integer',
        'peran' => 'required|in_list[pemimpin_ibadah,penyanyi,pemusik,pembaca_alkitab]',
        'status_konfirmasi' => 'required|in_list[menunggu,dikonfirmasi,ditolak,batal]'
    ];
    
    protected $validationMessages = [
        'id_ibadah' => [
            'required' => 'ID Ibadah wajib diisi',
            'integer' => 'ID Ibadah harus berupa angka'
        ],
        'id_jemaat' => [
            'required' => 'Jemaat wajib dipilih',
            'integer' => 'Data jemaat tidak valid'
        ],
        'peran' => [
            'required' => 'Peran wajib dipilih',
            'in_list' => 'Peran tidak valid'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all petugas ibadah with details
     */
    public function getAllPetugasWithDetails($id_ibadah = null, $status = null, $peran = null)
    {
        $builder = $this->db->table($this->table . ' jp');
        $builder->select('jp.*, 
                         i.judul_ibadah,
                         i.tanggal_ibadah,
                         i.waktu_mulai,
                         i.waktu_selesai,
                         i.tempat_ibadah,
                         j.nama as nama_jemaat,
                         j.no_telp,
                         j.email,
                         j.foto_profil');
        
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah', 'left');
        $builder->join('jemaat j', 'j.id_jemaat = jp.id_jemaat', 'left');
        
        if ($id_ibadah) {
            $builder->where('jp.id_ibadah', $id_ibadah);
        }
        
        if ($status) {
            $builder->where('jp.status_konfirmasi', $status);
        }
        
        if ($peran) {
            $builder->where('jp.peran', $peran);
        }
        
        $builder->orderBy('i.tanggal_ibadah', 'DESC');
        $builder->orderBy('i.waktu_mulai', 'ASC');
        $builder->orderBy('jp.peran', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get petugas by ID with details
     */
    public function getPetugasWithDetails($id)
    {
        $builder = $this->db->table($this->table . ' jp');
        $builder->select('jp.*, 
                         i.judul_ibadah,
                         i.tanggal_ibadah,
                         i.waktu_mulai,
                         i.waktu_selesai,
                         i.tempat_ibadah,
                         i.kategori_ibadah,
                         i.keterangan as keterangan_ibadah,
                         j.nama as nama_jemaat,
                         j.nama_panggilan,
                         j.tempat_lahir,
                         j.tanggal_lahir,
                         j.jenis_kelamin,
                         j.no_telp,
                         j.email,
                         j.alamat,
                         j.foto_profil');
        
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah', 'left');
        $builder->join('jemaat j', 'j.id_jemaat = jp.id_jemaat', 'left');
        
        $builder->where('jp.id_jadwal_petugas', $id);
        
        return $builder->get()->getRowArray();
    }
    
    /**
     * Get petugas by ibadah
     */
    public function getPetugasByIbadah($id_ibadah)
    {
        return $this->where('id_ibadah', $id_ibadah)
                    ->orderBy('peran', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get petugas by jemaat
     */
    public function getPetugasByJemaat($id_jemaat, $limit = null, $status = null)
    {
        $builder = $this->where('id_jemaat', $id_jemaat);
        
        if ($status) {
            $builder->where('status_konfirmasi', $status);
        }
        
        $builder->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Get petugas by peran and date range
     */
    public function getPetugasByPeranDateRange($peran, $start_date, $end_date)
    {
        $builder = $this->db->table($this->table . ' jp');
        $builder->select('jp.*, i.judul_ibadah, i.tanggal_ibadah, i.waktu_mulai, j.nama_lengkap');
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah', 'left');
        $builder->join('jemaat j', 'j.id_jemaat = jp.id_jemaat', 'left');
        
        $builder->where('jp.peran', $peran);
        $builder->where('i.tanggal_ibadah >=', $start_date);
        $builder->where('i.tanggal_ibadah <=', $end_date);
        $builder->where('jp.status_konfirmasi', 'dikonfirmasi');
        
        $builder->orderBy('i.tanggal_ibadah', 'ASC');
        $builder->orderBy('i.waktu_mulai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Check if jemaat already assigned in same ibadah
     */
    public function checkJemaatAssignment($id_ibadah, $id_jemaat, $exclude_id = null)
    {
        $builder = $this->where('id_ibadah', $id_ibadah)
                        ->where('id_jemaat', $id_jemaat);
        
        if ($exclude_id) {
            $builder->where('id_jadwal_petugas !=', $exclude_id);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Check peran availability in ibadah
     */
    public function checkPeranAvailability($id_ibadah, $peran, $max_per_role = null, $exclude_id = null)
    {
        $builder = $this->where('id_ibadah', $id_ibadah)
                        ->where('peran', $peran)
                        ->whereIn('status_konfirmasi', ['menunggu', 'dikonfirmasi']);
        
        if ($exclude_id) {
            $builder->where('id_jadwal_petugas !=', $exclude_id);
        }
        
        $count = $builder->countAllResults();
        
        if ($max_per_role) {
            return $count < $max_per_role;
        }
        
        return $count;
    }
    
    /**
     * Update konfirmasi status
     */
    public function updateKonfirmasi($id, $status, $keterangan = null)
    {
        $data = [
            'status_konfirmasi' => $status,
            'tanggal_konfirmasi' => ($status != 'menunggu') ? date('Y-m-d H:i:s') : null,
            'keterangan' => $keterangan,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }
    
    /**
     * Get statistics by peran
     */
    public function getStatistikPeran($tahun = null, $bulan = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->db->table($this->table . ' jp');
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah');
        
        $builder->select('jp.peran, COUNT(*) as total');
        $builder->where('YEAR(i.tanggal_ibadah)', $tahun);
        $builder->where('jp.status_konfirmasi', 'dikonfirmasi');
        
        if ($bulan) {
            $builder->where('MONTH(i.tanggal_ibadah)', $bulan);
        }
        
        $builder->groupBy('jp.peran');
        $builder->orderBy('total', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get top aktif jemaat
     */
    public function getTopAktifJemaat($limit = 10, $tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->db->table($this->table . ' jp');
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah');
        $builder->join('jemaat j', 'j.id_jemaat = jp.id_jemaat');
        
        $builder->select('jp.id_jemaat, j.nama_lengkap, COUNT(*) as total_tugas');
        $builder->where('YEAR(i.tanggal_ibadah)', $tahun);
        $builder->where('jp.status_konfirmasi', 'dikonfirmasi');
        
        $builder->groupBy('jp.id_jemaat');
        $builder->orderBy('total_tugas', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get ibadah schedule for jemaat
     */
    public function getJadwalForJemaat($id_jemaat, $status = null, $limit = null)
    {
        $builder = $this->db->table($this->table . ' jp');
        $builder->select('jp.*, i.judul_ibadah, i.tanggal_ibadah, i.waktu_mulai, i.waktu_selesai, i.tempat_ibadah, i.kategori_ibadah');
        $builder->join('jadwal_ibadah i', 'i.id_ibadah = jp.id_ibadah', 'left');
        
        $builder->where('jp.id_jemaat', $id_jemaat);
        
        if ($status) {
            $builder->where('jp.status_konfirmasi', $status);
        }
        
        $builder->orderBy('i.tanggal_ibadah', 'DESC');
        $builder->orderBy('i.waktu_mulai', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get available jemaat for peran
     */
    public function getAvailableJemaatForPeran($peran, $exclude_jemaat = [], $tanggal_ibadah = null)
    {
        $builder = $this->db->table('jemaat j');
        $builder->select('j.*, k.nama_keluarga');
        $builder->join('keluarga k', 'k.id_keluarga = j.id_keluarga', 'left');
        $builder->where('j.status_jemaat', 'aktif');
        
        // Filter berdasarkan kemampuan/minat jika ada
        $builder->groupStart();
        $builder->like('j.minat_pelayanan', $peran);
        $builder->orLike('j.kemampuan_khusus', $peran);
        $builder->groupEnd();
        
        if (!empty($exclude_jemaat)) {
            $builder->whereNotIn('j.id_jemaat', $exclude_jemaat);
        }
        
        $builder->orderBy('j.nama_lengkap', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate jadwal petugas otomatis
     */
    public function generateAutoSchedule($id_ibadah, $peran_config = [])
    {
        $result = [
            'success' => [],
            'failed' => []
        ];
        
        foreach ($peran_config as $peran => $config) {
            $needed = $config['jumlah'];
            $exclude = $config['exclude'] ?? [];
            
            // Get available jemaat for this peran
            $available_jemaat = $this->getAvailableJemaatForPeran($peran, $exclude);
            
            // Shuffle and pick needed number
            shuffle($available_jemaat);
            $selected = array_slice($available_jemaat, 0, min($needed, count($available_jemaat)));
            
            foreach ($selected as $jemaat) {
                $data = [
                    'id_ibadah' => $id_ibadah,
                    'id_jemaat' => $jemaat['id_jemaat'],
                    'peran' => $peran,
                    'status_konfirmasi' => 'menunggu',
                    'keterangan' => 'Ditugaskan secara otomatis'
                ];
                
                if ($this->insert($data)) {
                    $result['success'][] = [
                        'jemaat' => $jemaat['nama_lengkap'],
                        'peran' => $peran
                    ];
                } else {
                    $result['failed'][] = [
                        'jemaat' => $jemaat['nama_lengkap'],
                        'peran' => $peran
                    ];
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Get peran options with description
     */
    public function getPeranOptions()
    {
        return [
            'pemimpin_ibadah' => [
                'label' => 'Pemimpin Ibadah',
                'description' => 'Memimpin jalannya ibadah',
                'max_per_service' => 1
            ],
            'pemandu_pujian' => [
                'label' => 'Pemandu Pujian',
                'description' => 'Membawakan pujian dan penyembahan',
                'max_per_service' => 5
            ],
            'pemusik' => [
                'label' => 'Pemusik',
                'description' => 'Memainkan alat musik',
                'max_per_service' => 6
            ],
            'penatua' => [
                'label' => 'Penatua',
                'description' => 'Menyambut jemaat di pintu masuk',
                'max_per_service' => 4
            ]
            'diaken' => [
                'label' => 'Pdiaken',
                'description' => 'Menyambut jemaat di pintu masuk',
                'max_per_service' => 4
            ]
        ];
    }
    
    /**
     * Get status options
     */
    public function getStatusOptions()
    {
        return [
            'menunggu' => ['label' => 'Menunggu', 'class' => 'warning'],
            'dikonfirmasi' => ['label' => 'Dikonfirmasi', 'class' => 'success'],
            'ditolak' => ['label' => 'Ditolak', 'class' => 'danger'],
            'batal' => ['label' => 'Batal', 'class' => 'secondary']
        ];
    }
}