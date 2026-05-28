<?php

namespace App\Models;

use CodeIgniter\Model;

class ProkerModel extends Model
{
    protected $table = 'program_kerja';
    protected $primaryKey = 'id_program';
    protected $allowedFields = [
        'kode_program',
        'nama_program',
        'deskripsi',
        'id_komisi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'anggaran',
        'sumber_dana',
        'status',
        'id_penanggungjawab',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'kode_program' => 'required|is_unique[program_kerja.kode_program,id_program,{id_program}]',
        'nama_program' => 'required|min_length[3]',
        'id_komisi' => 'required',
        'tanggal_mulai' => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'anggaran' => 'permit_empty|numeric'
    ];
    
    protected $validationMessages = [
        'kode_program' => [
            'required' => 'Kode program harus diisi',
            'is_unique' => 'Kode program sudah digunakan'
        ],
        'nama_program' => [
            'required' => 'Nama program harus diisi',
            'min_length' => 'Nama program minimal 3 karakter'
        ],
        'id_komisi' => [
            'required' => 'Komisi harus dipilih'
        ]
    ];
    
    /**
     * Generate kode program otomatis
     */
    public function generateKodeProgram($idDepartemen)
    {
        $departemenModel = new DepartemenModel();
        $departemen = $departemenModel->find($idDepartemen);
        
        if (!$departemen) {
            return 'PROG-' . date('Ym') . '-001';
        }
        
        $prefix = 'PROG-' . strtoupper(substr($departemen['kode_departemen'], 0, 3)) . '-' . date('Ym') . '-';
        
        $this->like('kode_program', $prefix);
        $count = $this->countAllResults();
        
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . $sequence;
    }
    
    /**
     * Get all programs with komisi info
     */
    public function getAllPrograms($filters = [])
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, 
            k.nama_komisi, 
            k.kode_komisi,
            per.nama_periode,
            u.nama as nama_penanggungjawab,
            COUNT(k.id_kegiatan) as total_kegiatan,
            SUM(CASE WHEN k.status = "selesai" THEN 1 ELSE 0 END) as kegiatan_selesai,
            SUM(k.anggaran) as total_anggaran_kegiatan,
            AVG(k.persentase_selesai) as rata_progress'
        );
        
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi', 'left');
        $builder->join('periode_program per', 'pk.id_periode = per.id_periode', 'left');
        $builder->join('users u', 'pk.id_penanggungjawab = u.id', 'left');
        $builder->join('kegiatan k', 'pk.id_program = k.id_program', 'left');
        
        // Apply filters
        if (!empty($filters['id_komisi'])) {
            $builder->where('pk.id_komisi', $filters['id_komisi']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('pk.status', $filters['status']);
        }
        
        if (!empty($filters['tahun'])) {
            $builder->where('YEAR(pk.tanggal_mulai)', $filters['tahun']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('pk.nama_program', $filters['search'])
                ->orLike('pk.kode_program', $filters['search'])
                ->orLike('pk.deskripsi', $filters['search'])
                ->groupEnd();
        }
        
        $builder->groupBy('pk.id_program');
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get program by ID with complete information
     */
    public function getProgramById($idProgram)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, 
            k.nama_komisi, 
            k.kode_komisi,
            per.nama_periode,
            per.tahun,
            u.nama as nama_penanggungjawab,
            u.email as email_penanggungjawab,
            u.telepon as telepon_penanggungjawab'
        );
        
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->join('periode_program per', 'pk.id_periode = per.id_periode', 'left');
        $builder->join('users u', 'pk.id_penanggungjawab = u.id', 'left');
        $builder->where('pk.id_program', $idProgram);
        
        return $builder->get()->getRowArray();
    }
    
    /**
     * Get programs by komisi
     */
    public function getProgramsByKomisi($idKomisi, $status = null)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, 
            COUNT(k.id_kegiatan) as total_kegiatan,
            SUM(CASE WHEN k.status = "selesai" THEN 1 ELSE 0 END) as kegiatan_selesai,
            SUM(k.anggaran) as total_anggaran_kegiatan'
        );
        
        $builder->join('kegiatan k', 'pk.id_program = k.id_program', 'left');
        $builder->where('pk.id_komisi', $idKomisi);
        
        if ($status) {
            $builder->where('pk.status', $status);
        }
        
        $builder->groupBy('pk.id_program');
        $builder->orderBy('pk.prioritas', 'DESC');
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get program statistics by komisi
     */
    public function getStatistikByKomisi($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->db->table('program_kerja pk');
        $builder->select('
            k.id_komisi,
            k.nama_komisi,
            COUNT(pk.id_program) as total_program,
            SUM(CASE WHEN pk.status = "selesai" THEN 1 ELSE 0 END) as program_selesai,
            SUM(CASE WHEN pk.status = "berjalan" THEN 1 ELSE 0 END) as program_berjalan,
            SUM(CASE WHEN pk.status = "direncanakan" THEN 1 ELSE 0 END) as program_direncanakan,
            SUM(pk.anggaran) as total_anggaran,
        );
        
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->where('YEAR(pk.tanggal_mulai)', $tahun);
        $builder->orWhere('YEAR(pk.tanggal_selesai)', $tahun);
        $builder->groupBy('k.id_komisi');
        $builder->orderBy('k.nama_komisi', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get program timeline
     */
    public function getTimelinePrograms($limit = 10)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, k.nama_komisi, k.warna_komisi');
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->where('pk.status', 'berjalan');
        $builder->orWhere('pk.status', 'direncanakan');
        $builder->orderBy('pk.prioritas', 'DESC');
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Update program progress
     */
    public function updateProgress($idProgram)
    {
        // Hitung progress berdasarkan kegiatan terkait
        $kegiatanModel = new KegiatanModel();
        $kegiatan = $kegiatanModel->where('id_program', $idProgram)->findAll();
        
        if (empty($kegiatan)) {
            $progress = 0;
        } else {
            $totalProgress = 0;
            foreach ($kegiatan as $keg) {
                $totalProgress += $keg['persentase_selesai'];
            }
            $progress = round($totalProgress / count($kegiatan));
        }
        
        // Update status berdasarkan progress
        $status = 'direncanakan';
        if ($progress > 0 && $progress < 100) {
            $status = 'berjalan';
        } elseif ($progress >= 100) {
            $status = 'selesai';
        }
        
        $data = [
            'persentase_selesai' => $progress,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session()->get('id') ?? 1
        ];
        
        return $this->update($idProgram, $data);
    }
    
    /**
     * Get program dengan anggaran detail
     */
    public function getProgramWithAnggaranDetail($idProgram)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, 
            k.nama_komisi,
            SUM(tk.jumlah) as realisasi_anggaran,
            SUM(CASE WHEN tk.jenis_transaksi = "pemasukan" THEN tk.jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN tk.jenis_transaksi = "pengeluaran" THEN tk.jumlah ELSE 0 END) as total_pengeluaran'
        );
        
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->join('transaksi_keuangan tk', 'pk.id_program = tk.id_program', 'left');
        $builder->where('pk.id_program', $idProgram);
        $builder->groupBy('pk.id_program');
        
        $program = $builder->get()->getRowArray();
        
        if ($program) {
            $program['sisa_anggaran'] = $program['anggaran'] - $program['total_pengeluaran'];
            $program['persentase_penggunaan'] = $program['anggaran'] > 0 ? 
                round(($program['total_pengeluaran'] / $program['anggaran']) * 100, 2) : 0;
        }
        
        return $program;
    }
    
    /**
     * Get program evaluation
     */
    public function getEvaluasiProgram($idProgram)
    {
        $builder = $this->db->table('evaluasi_program ep');
        $builder->select('ep.*, u.nama as evaluator, u.jabatan');
        $builder->join('users u', 'ep.id_evaluator = u.id');
        $builder->where('ep.id_program', $idProgram);
        $builder->orderBy('ep.tanggal_evaluasi', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get program documents
     */
    public function getDokumenProgram($idProgram)
    {
        $builder = $this->db->table('dokumen_program');
        $builder->where('id_program', $idProgram);
        $builder->orderBy('created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get program team members
     */
    public function getTimProgram($idProgram)
    {
        $builder = $this->db->table('tim_program tp');
        $builder->select('tp.*, u.nama, u.email, u.telepon, r.nama_role');
        $builder->join('users u', 'tp.id_user = u.id');
        $builder->join('roles r', 'tp.id_role = r.id_role', 'left');
        $builder->where('tp.id_program', $idProgram);
        $builder->orderBy('tp.id_role', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Add team member to program
     */
    public function addTimMember($data)
    {
        $builder = $this->db->table('tim_program');
        return $builder->insert($data);
    }
    
    /**
     * Remove team member from program
     */
    public function removeTimMember($idTim)
    {
        $builder = $this->db->table('tim_program');
        return $builder->delete(['id_tim' => $idTim]);
    }
    
    /**
     * Get yearly program summary
     */
    public function getRingkasanTahunan($tahun)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select("
            MONTH(pk.tanggal_mulai) as bulan,
            COUNT(pk.id_program) as total_program,
            SUM(pk.anggaran) as total_anggaran,
            AVG(pk.persentase_selesai) as rata_progress,
            d.nama_departemen,
            GROUP_CONCAT(pk.nama_program SEPARATOR '; ') as daftar_program
        ");
        
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->where('YEAR(pk.tanggal_mulai)', $tahun);
        $builder->orWhere('YEAR(pk.tanggal_selesai)', $tahun);
        $builder->groupBy('MONTH(pk.tanggal_mulai), d.id_departemen');
        $builder->orderBy('bulan', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get overdue programs
     */
    public function getProgramTerlambat()
    {
        $today = date('Y-m-d');
        
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, k.nama_komisi, DATEDIFF(pk.tanggal_selesai, CURDATE()) as hari_terlambat');
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->where('pk.status', 'berjalan');
        $builder->where('pk.tanggal_selesai <', $today);
        $builder->where('pk.persentase_selesai <', 100);
        $builder->orderBy('pk.tanggal_selesai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get upcoming programs
     */
    public function getProgramAkanDatang($hari = 30)
    {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+$hari days"));
        
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, k.nama_komisi, DATEDIFF(pk.tanggal_mulai, CURDATE()) as hari_menuju');
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi');
        $builder->where('pk.status', 'direncanakan');
        $builder->where('pk.tanggal_mulai >=', $today);
        $builder->where('pk.tanggal_mulai <=', $futureDate);
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}