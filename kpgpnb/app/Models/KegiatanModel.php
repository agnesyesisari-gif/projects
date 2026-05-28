<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'nama_kegiatan',
        'jenis_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'penanggung_jawab',
        'status_kegiatan',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'nama_kegiatan' => 'required|min_length[3]|max_length[255]',
        'jenis_kegiatan' => 'required|in_list[ibadah,program_kerja,retreat,pendidikan,evangelisasi,sosial]',
        'deskripsi' => 'permit_empty',
        'tanggal_mulai' => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'waktu_mulai' => 'permit_empty',
        'waktu_selesai' => 'permit_empty',
        'tempat' => 'required',
        'penanggung_jawab' => 'required',
        'status_kegiatan' => 'required|in_list[aktif,selesai,dibatalkan,tertunda]'
    ];
    
    protected $validationMessages = [
        'nama_kegiatan' => [
            'required' => 'Nama kegiatan harus diisi',
            'min_length' => 'Nama kegiatan minimal 3 karakter',
            'max_length' => 'Nama kegiatan maksimal 255 karakter'
        ],
        'jenis_kegiatan' => [
            'required' => 'Jenis kegiatan harus dipilih',
            'in_list' => 'Jenis kegiatan tidak valid'
        ]
    ];
    
    protected $skipValidation = false;
    
    public function getByJenis($jenis)
    {
        return $this->where('jenis_kegiatan', $jenis)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }
    
    public function getKegiatanAktif($limit = null)
    {
        $builder = $this->where('status_kegiatan', 'aktif')
                       ->where('tanggal_mulai >=', date('Y-m-d'))
                       ->orderBy('tanggal_mulai', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    public function getJadwalIbadahMingguIni()
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->where('jenis_kegiatan', 'ibadah')
                   ->where('tanggal_mulai >=', $startOfWeek)
                   ->where('tanggal_mulai <=', $endOfWeek)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }
    
    public function getProgramKerjaBulanIni()
    {
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        return $this->where('jenis_kegiatan', 'program_kerja')
                   ->where('tanggal_mulai >=', $startOfMonth)
                   ->where('tanggal_mulai <=', $endOfMonth)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }
    
    public function searchKegiatan($keyword)
    {
        return $this->like('nama_kegiatan', $keyword)
                   ->orLike('deskripsi', $keyword)
                   ->orLike('tempat', $keyword)
                   ->orLike('penanggung_jawab', $keyword)
                   ->orderBy('tanggal_mulai', 'DESC')
                   ->findAll();
    }
    
    public function getKegiatanByDateRange($startDate, $endDate)
    {
        return $this->where('tanggal_mulai >=', $startDate)
                   ->where('tanggal_mulai <=', $endDate)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->orderBy('waktu_mulai', 'ASC')
                   ->findAll();
    }
    
    public function getByPelayanan($pelayanan)
    {
        return $this->where('penanggung_jawab', $pelayanan)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }
    
    public function updateStatusOtomatis()
    {
        // Update kegiatan yang sudah lewat tanggal selesai menjadi 'selesai'
        $this->where('tanggal_selesai <', date('Y-m-d'))
            ->where('status_kegiatan', 'aktif')
            ->set(['status_kegiatan' => 'selesai', 'updated_at' => date('Y-m-d H:i:s')])
            ->update();
            
        return $this->db->affectedRows();
    }
    
    public function getStatistik()
    {
        $statistik = [
            'total' => $this->countAll(),
            'aktif' => $this->where('status_kegiatan', 'aktif')->countAllResults(),
            'selesai' => $this->where('status_kegiatan', 'selesai')->countAllResults(),
            'ibadah' => $this->where('jenis_kegiatan', 'ibadah')->countAllResults(),
            'program_kerja' => $this->where('jenis_kegiatan', 'program_kerja')->countAllResults()
        ];
        
        return $statistik;
    }
    
    public function getForCalendar($month, $year)
    {
        $startDate = date("$year-$month-01");
        $endDate = date("$year-$month-t", strtotime($startDate));
        
        return $this->select('id_kegiatan, nama_kegiatan, jenis_kegiatan, tanggal_mulai, tanggal_selesai, waktu_mulai, waktu_selesai, lokasi')
                   ->where('tanggal_mulai >=', $startDate)
                   ->where('tanggal_mulai <=', $endDate)
                   ->where('status_kegiatan', 'aktif')
                   ->orderBy('tanggal_mulai', 'ASC')
                   ->findAll();
    }
}