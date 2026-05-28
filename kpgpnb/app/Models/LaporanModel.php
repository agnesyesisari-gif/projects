<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'laporan_kegiatan';
    protected $primaryKey = 'id_laporan';
    protected $allowedFields = [
        'judul_laporan',
        'jenis_laporan',
        'id_kegiatan',
        'id_ibadah',
        'periode_awal',
        'periode_akhir',
        'file_lampiran',
        'created_by',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Generate laporan jadwal ibadah
     */
    public function generateLaporanIbadah($startDate, $endDate, $jenisIbadah = null)
    {
        $builder = $this->db->table('jadwal_ibadah ji');
        $builder->select('ji.*, p.nama_pendeta as pelayan, r.nama_ruangan');
        $builder->join('pendeta p', 'ji.id_pendeta = p.id_pendeta', 'left');
        $builder->join('ruangan r', 'ji.id_ruangan = r.id_ruangan', 'left');
        $builder->where('ji.tanggal_ibadah >=', $startDate);
        $builder->where('ji.tanggal_ibadah <=', $endDate);
        
        if ($jenisIbadah) {
            $builder->where('ji.jenis_ibadah', $jenisIbadah);
        }
        
        $builder->orderBy('ji.tanggal_ibadah', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan program kerja
     */
    public function generateLaporanProgramKerja($startDate, $endDate, $status = null)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, 
            d.nama_komisi,
            COUNT(k.id_kegiatan) as total_kegiatan,
            SUM(k.anggaran) as total_anggaran,
            SUM(CASE WHEN k.status = "selesai" THEN 1 ELSE 0 END) as kegiatan_selesai'
        );
        $builder->join('komisi k', 'pk.id_komisi = k.id_komisi', 'left');
        $builder->join('kegiatan k', 'pk.id_program = k.id_program', 'left');
        $builder->where('pk.tanggal_mulai >=', $startDate);
        $builder->where('pk.tanggal_selesai <=', $endDate);
        
        if ($status) {
            $builder->where('pk.status', $status);
        }
        
        $builder->groupBy('pk.id_program');
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan kehadiran ibadah
     */
    public function generateLaporanKehadiran($startDate, $endDate)
    {
        $builder = $this->db->table('kehadiran_ibadah ki');
        $builder->select('
            ji.tanggal_ibadah,
            ji.jenis_ibadah,
            ji.waktu_mulai,
            COUNT(ki.id_kehadiran) as total_hadir,
            COUNT(DISTINCT ki.id_jemaat) as jumlah_jemaat,
            AVG(ki.rating) as rata_rating
        ');
        $builder->join('jadwal_ibadah ji', 'ki.id_ibadah = ji.id_ibadah');
        $builder->where('ji.tanggal_ibadah >=', $startDate);
        $builder->where('ji.tanggal_ibadah <=', $endDate);
        $builder->groupBy('ji.id_ibadah');
        $builder->orderBy('ji.tanggal_ibadah', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan keuangan kegiatan
     */
    public function generateLaporanKeuangan($startDate, $endDate, $idDepartemen = null)
    {
        $builder = $this->db->table('transaksi_keuangan tk');
        $builder->select('
            tk.*,
            k.judul_kegiatan,
            d.nama_departemen,
            jk.jenis_kategori,
            CASE 
                WHEN tk.jenis_transaksi = "pemasukan" THEN tk.jumlah
                ELSE 0 
            END as pemasukan,
            CASE 
                WHEN tk.jenis_transaksi = "pengeluaran" THEN tk.jumlah
                ELSE 0 
            END as pengeluaran
        ');
        $builder->join('kegiatan k', 'tk.id_kegiatan = k.id_kegiatan', 'left');
        $builder->join('komisi k', 'tk.id_komisi = k.id_komisi', 'left');
        $builder->join('jenis_kategori jk', 'tk.id_kategori = jk.id_kategori', 'left');
        $builder->where('tk.tanggal_transaksi >=', $startDate);
        $builder->where('tk.tanggal_transaksi <=', $endDate);
        
        if ($idDepartemen) {
            $builder->where('tk.id_departemen', $idDepartemen);
        }
        
        $builder->orderBy('tk.tanggal_transaksi', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan pelayanan
     */
    public function generateLaporanPelayanan($startDate, $endDate)
    {
        $builder = $this->db->table('pelayanan p');
        $builder->select('
            p.*,
            j.nama_lengkap as nama_jemaat,
            pt.nama_pelayanan as jenis_pelayanan,
            k.nama_komisi,
            u.nama as penanggungjawab
        ');
        $builder->join('jemaat j', 'p.id_jemaat = j.id_jemaat', 'left');
        $builder->join('jenis_pelayanan pt', 'p.id_jenis_pelayanan = pt.id_jenis_pelayanan', 'left');
        $builder->join('komisi k', 'p.id_komisi = k.id_komisi', 'left');
        $builder->join('users u', 'p.created_by = u.id', 'left');
        $builder->where('p.tanggal_pelayanan >=', $startDate);
        $builder->where('p.tanggal_pelayanan <=', $endDate);
        $builder->orderBy('p.tanggal_pelayanan', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan statistik kegiatan
     */
    public function getStatistikKegiatan($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->db->table('kegiatan k');
        $builder->select("
            MONTH(k.tanggal_kegiatan) as bulan,
            COUNT(*) as total_kegiatan,
            SUM(CASE WHEN k.status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN k.status = 'berjalan' THEN 1 ELSE 0 END) as berjalan,
            SUM(CASE WHEN k.status = 'tertunda' THEN 1 ELSE 0 END) as tertunda,
            SUM(k.anggaran) as total_anggaran,
        ");
        $builder->where('YEAR(k.tanggal_kegiatan)', $tahun);
        $builder->groupBy('MONTH(k.tanggal_kegiatan)');
        $builder->orderBy('bulan', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Generate laporan kinerja komisi
     */
    public function getLaporanKinerjaDepartemen($startDate, $endDate)
    {
        $builder = $this->db->table('departemen d');
        $builder->select('
            d.*,
            COUNT(pk.id_program) as total_program,
            COUNT(k.id_kegiatan) as total_kegiatan,
            SUM(CASE WHEN k.status = "selesai" THEN 1 ELSE 0 END) as kegiatan_selesai,
            SUM(k.anggaran) as total_anggaran,
            AVG(k.persentase_selesai) as rata_kinerja
        ');
        $builder->join('program_kerja pk', 'k.id_komisi = pk.id_komisi', 'left');
        $builder->join('kegiatan k', 'pk.id_program = k.id_program', 'left');
        $builder->where('pk.tanggal_mulai >=', $startDate);
        $builder->where('pk.tanggal_selesai <=', $endDate);
        $builder->orWhere('k.tanggal_kegiatan >=', $startDate);
        $builder->where('k.tanggal_kegiatan <=', $endDate);
        $builder->groupBy('d.id_departemen');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Save generated report
     */
    public function simpanLaporan($data)
    {
        $this->save($data);
        return $this->insertID();
    }
    
    /**
     * Get all saved reports
     */
    public function getLaporanTersimpan($jenis = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('l.*, u.nama as pembuat');
        $builder->join('users u', 'l.created_by = u.id', 'left');
        
        if ($jenis) {
            $builder->where('l.jenis_laporan', $jenis);
        }
        
        $builder->orderBy('l.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
}