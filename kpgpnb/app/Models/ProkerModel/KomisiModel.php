<?php

namespace App\Models;

use CodeIgniter\Model;

class KomisiModel extends Model
{
    protected $table = 'komisi';
    protected $primaryKey = 'id_komisi';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nama_komisi',
        'deskripsi',
        'ketua',
        'wakil_ketua',
        'sekretaris',
        'bendahara',
        'anggota',
        'target_tahunan',
        'status',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'nama_komisi' => 'required|min_length[3]|max_length[100]|is_unique[komisi.nama_komisi,id_komisi,{id_komisi}]',
        'deskripsi' => 'permit_empty|min_length[10]',
        'ketua' => 'permit_empty|max_length[100]',
        'status' => 'required|in_list[aktif,nonaktif]'
    ];
    
    protected $validationMessages = [
        'nama_komisi' => [
            'required' => 'Nama komisi harus diisi',
            'min_length' => 'Nama komisi minimal 3 karakter',
            'max_length' => 'Nama komisi maksimal 100 karakter',
            'is_unique' => 'Nama komisi sudah digunakan'
        ],
        'status' => [
            'required' => 'Status komisi harus dipilih',
            'in_list' => 'Status harus salah satu: aktif, nonaktif'
        ]
    ];
    
    protected $skipValidation = false;
    
    public function getAllKomisi($filters = [], $limit = null, $offset = 0)
    {
        $builder = $this->builder();
        
        // Filter berdasarkan status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $builder->where('status', $filters['status']);
        }
        
        // Filter berdasarkan keyword pencarian
        if (isset($filters['search']) && $filters['search'] !== '') {
            $builder->groupStart()
                    ->like('nama_komisi', $filters['search'])
                    ->orLike('deskripsi', $filters['search'])
                    ->orLike('ketua', $filters['search'])
                    ->groupEnd();
        }
        
        // Sorting
        $sortField = isset($filters['sort_field']) ? $filters['sort_field'] : 'nama_komisi';
        $sortOrder = isset($filters['sort_order']) ? $filters['sort_order'] : 'asc';
        $builder->orderBy($sortField, $sortOrder);
        
        // Limit dan offset untuk pagination
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getKomisiAktif()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('nama_komisi', 'asc')
                    ->findAll();
    }
    
    public function getKomisiWithProgramCount()
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT k.*, 
                       COUNT(p.id_program) as jumlah_program,
                       SUM(CASE WHEN p.status_program = 'selesai' THEN 1 ELSE 0 END) as program_selesai,
                       SUM(CASE WHEN p.status_program = 'berjalan' THEN 1 ELSE 0 END) as program_berjalan,
                       SUM(CASE WHEN p.status_program = 'rencana' THEN 1 ELSE 0 END) as program_rencana
                FROM komisi k
                LEFT JOIN program_kerja p ON k.id_komisi = p.id_komisi
                GROUP BY k.id_komisi
                ORDER BY k.nama_komisi";
        
        return $db->query($sql)->getResultArray();
    }
    
    public function getStatistikKomisi()
    {
        $db = \Config\Database::connect();
        
        $statistik = [
            'total_komisi' => $this->countAll(),
            'komisi_aktif' => $this->where('status', 'aktif')->countAllResults(),
            'komisi_nonaktif' => $this->where('status', 'nonaktif')->countAllResults()
        ];
        
        return $statistik;
    }
    
    /**
     * Export data komisi ke PDF
     */
    public function exportData($filters = [])
    {
        $builder = $this->builder();
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $builder->where('status', $filters['status']);
        }
        
        $builder->orderBy('nama_komisi', 'asc');
        
        return $builder->get()->getResultArray();
    }
}