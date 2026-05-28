<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table = 'anggota_komisi';
    protected $primaryKey = 'id_anggota';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'id_komisi',
        'id_jemaat',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'jabatan_komisi',
        'status_anggota',
        'keterangan',
        'foto',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation rules
    protected $validationRules = [
        'id_komisi' => 'required|integer',
        'nama' => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'no_telepon' => 'permit_empty|max_length[20]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'jabatan_komisi' => 'required|in_list[ketua,wakil_ketua,sekretaris,bendahara,anggota]',
        'status_anggota' => 'required|in_list[aktif,nonaktif,cuti]'
    ];
    
    protected $validationMessages = [
        'id_komisi' => [
            'required' => 'Komisi harus dipilih',
            'integer' => 'ID Komisi harus berupa angka'
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus dipilih',
            'in_list' => 'Jenis kelamin harus L atau P'
        ],
        'jabatan_komisi' => [
            'required' => 'Jabatan dalam komisi harus dipilih',
            'in_list' => 'Jabatan tidak valid'
        ],
        'status_anggota' => [
            'required' => 'Status anggota harus dipilih',
            'in_list' => 'Status anggota tidak valid'
        ]
    ];
    
    protected $skipValidation = false;
    
    public function getAnggotaWithKomisi($filters = [], $limit = null, $offset = 0)
    {
        $builder = $this->builder();
        $builder->select('anggota_komisi.*, komisi.nama_komisi, komisi.deskripsi as deskripsi_komisi');
        $builder->join('komisi', 'komisi.id_komisi = anggota_komisi.id_komisi');
        
        // Filter berdasarkan komisi
        if (isset($filters['id_komisi']) && $filters['id_komisi'] !== '') {
            $builder->where('anggota_komisi.id_komisi', $filters['id_komisi']);
        }
        
        // Filter berdasarkan status anggota
        if (isset($filters['status_anggota']) && $filters['status_anggota'] !== '') {
            $builder->where('anggota_komisi.status_anggota', $filters['status_anggota']);
        }
        
        // Filter berdasarkan jabatan
        if (isset($filters['jabatan_komisi']) && $filters['jabatan_komisi'] !== '') {
            $builder->where('anggota_komisi.jabatan_komisi', $filters['jabatan_komisi']);
        }
        
        // Filter berdasarkan keyword pencarian
        if (isset($filters['search']) && $filters['search'] !== '') {
            $builder->groupStart()
                    ->orLike('anggota_komisi.nama', $filters['search'])
                    ->orLike('anggota_komisi.email', $filters['search'])
                    ->orLike('anggota_komisi.no_telepon', $filters['search'])
                    ->orLike('komisi.nama_komisi', $filters['search'])
                    ->groupEnd();
        }
        
        // Filter untuk hanya menampilkan data yang tidak dihapus
        $builder->where('anggota_komisi.deleted_at', null);
        
        // Sorting
        $sortField = isset($filters['sort_field']) ? $filters['sort_field'] : 'anggota_komisi.nama_lengkap';
        $sortOrder = isset($filters['sort_order']) ? $filters['sort_order'] : 'asc';
        $builder->orderBy($sortField, $sortOrder);
        
        // Limit dan offset untuk pagination
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getDetailAnggota($id_anggota)
    {
        $builder = $this->builder();
        $builder->select('anggota_komisi.*, komisi.*');
        $builder->join('komisi', 'komisi.id_komisi = anggota_komisi.id_komisi');
        $builder->where('anggota_komisi.id_anggota', $id_anggota);
        $builder->where('anggota_komisi.deleted_at', null);
        
        $query = $builder->get();
        return $query->getRowArray();
    }
    
    public function getAnggotaAktifByKomisi($id_komisi)
    {
        return $this->where('id_komisi', $id_komisi)
                    ->where('status_anggota', 'aktif')
                    ->where('deleted_at', null)
                    ->orderBy('jabatan_komisi', 'asc')
                    ->orderBy('nama_lengkap', 'asc')
                    ->findAll();
    }
   
    public function getStrukturPengurus($id_komisi)
    {
        $jabatan_prioritas = ['ketua', 'wakil_ketua', 'sekretaris', 'bendahara'];
        
        $builder = $this->builder();
        $builder->where('id_komisi', $id_komisi);
        $builder->where('status_anggota', 'aktif');
        $builder->whereIn('jabatan_komisi', $jabatan_prioritas);
        $builder->where('deleted_at', null);
        
        // Urutkan berdasarkan prioritas jabatan
        $builder->orderBy("FIELD(jabatan_komisi, '" . implode("','", $jabatan_prioritas) . "')");
        $builder->orderBy('nama_lengkap', 'asc');
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
   
    public function getStatistikAnggotaByKomisi($id_komisi = null)
    {
        $builder = $this->builder();
        
        $builder->select('
            COUNT(*) as total_anggota,
            SUM(CASE WHEN status_anggota = "aktif" THEN 1 ELSE 0 END) as anggota_aktif,
            SUM(CASE WHEN status_anggota = "nonaktif" THEN 1 ELSE 0 END) as anggota_nonaktif,
            SUM(CASE WHEN jenis_kelamin = "L" THEN 1 ELSE 0 END) as pria,
            SUM(CASE WHEN jenis_kelamin = "P" THEN 1 ELSE 0 END) as wanita
        ');
        
        if ($id_komisi !== null) {
            $builder->where('id_komisi', $id_komisi);
        }
        
        $builder->where('deleted_at', null);
        
        return $builder->get()->getRowArray();
    }
    
    public function updateStatusAnggota($id_anggota, $status, $tanggal_keluar = null, $alasan_keluar = null)
    {
        $data = [
            'status_anggota' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($status === 'nonaktif' && $tanggal_keluar !== null) {
            $data['tanggal_keluar'] = $tanggal_keluar;
            $data['alasan_keluar'] = $alasan_keluar;
        }
        
        return $this->update($id_anggota, $data);
    }
    
    public function isAnggotaExist($id_jemaat, $id_komisi = null)
    {
        $builder = $this->builder();
        $builder->where('id_jemaat', $id_jemaat);
        $builder->where('status_anggota', 'aktif');
        $builder->where('deleted_at', null);
        
        if ($id_komisi !== null) {
            $builder->where('id_komisi', $id_komisi);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    public function getRiwayatKepengurusan($id_jemaat)
    {
        $builder = $this->builder();
        $builder->select('anggota_komisi.*, komisi.nama_komisi, komisi.deskripsi');
        $builder->join('komisi', 'komisi.id_komisi = anggota_komisi.id_komisi');
        $builder->where('anggota_komisi.id_jemaat', $id_jemaat);
        $builder->where('anggota_komisi.deleted_at', null);
        $builder->orderBy('anggota_komisi.tanggal_masuk', 'desc');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Export data anggota ke PDF
     */
    public function exportDataAnggota($filters = [])
    {
        $builder = $this->builder();
        $builder->select('
            anggota_komisi.*,
            komisi.nama_komisi
        ');
        $builder->join('komisi', 'komisi.id_komisi = anggota_komisi.id_komisi');
        $builder->where('anggota_komisi.deleted_at', null);
        
        if (isset($filters['id_komisi']) && $filters['id_komisi'] !== '') {
            $builder->where('anggota_komisi.id_komisi', $filters['id_komisi']);
        }
        
        if (isset($filters['status_anggota']) && $filters['status_anggota'] !== '') {
            $builder->where('anggota_komisi.status_anggota', $filters['status_anggota']);
        }
        
        $builder->orderBy('komisi.nama_komisi', 'asc');
        $builder->orderBy('anggota_komisi.jabatan_komisi', 'asc');
        $builder->orderBy('anggota_komisi.nama_lengkap', 'asc');
        
        return $builder->get()->getResultArray();
    }
}