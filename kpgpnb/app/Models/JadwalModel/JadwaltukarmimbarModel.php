<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwaltukarmimbarklasisModel extends Model
{
    protected $table = 'jadwal_tukar_mimbar_klasis';
    protected $primaryKey = 'id_jadwal';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'tanggal',
        'jam',
        'gereja_pengirim',
        'pendeta_pengirim',
        'gereja_penerima',
        'pendeta_penerima',
        'tema_khotbah',
        'bacaan_alkitab',
        'keterangan',
        'status',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'tanggal' => 'required|valid_date[Y-m-d]',
        'jam' => 'required',
        'gereja_pengirim' => 'required|integer',
        'pendeta_pengirim' => 'required|integer',
        'gereja_penerima' => 'required|integer',
        'pendeta_penerima' => 'required|integer',
        'tema_khotbah' => 'required|min_length[5]',
        'status' => 'required|in_list[terjadwal,selesai,dibatalkan]'
    ];
    
    protected $validationMessages = [
        'tanggal' => [
            'required' => 'Tanggal wajib diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'gereja_pengirim' => [
            'required' => 'Gereja pengirim wajib dipilih',
            'integer' => 'Data gereja tidak valid'
        ],
        'tema_khotbah' => [
            'required' => 'Tema khotbah wajib diisi',
            'min_length' => 'Tema khotbah minimal 5 karakter'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all jadwal tukar mimbar with gereja and pendeta details
     */
    public function getAllJadwalWithDetails($status = null, $bulan = null, $tahun = null)
    {
        $builder = $this->db->table($this->table . ' j');
        $builder->select('j.*, 
                         gp.nama_gereja as nama_gereja_pengirim,
                         pp.nama_pendeta as nama_pendeta_pengirim,
                         gr.nama_gereja as nama_gereja_penerima,
                         pr.nama_pendeta as nama_pendeta_penerima');
        
        $builder->join('gereja gp', 'gp.id_gereja = j.gereja_pengirim', 'left');
        $builder->join('pendeta pp', 'pp.id_pendeta = j.pendeta_pengirim', 'left');
        $builder->join('gereja gr', 'gr.id_gereja = j.gereja_penerima', 'left');
        $builder->join('pendeta pr', 'pr.id_pendeta = j.pendeta_penerima', 'left');
        
        if ($status) {
            $builder->where('j.status', $status);
        }
        
        if ($bulan && $tahun) {
            $builder->where('MONTH(j.tanggal)', $bulan);
            $builder->where('YEAR(j.tanggal)', $tahun);
        }
        
        $builder->orderBy('j.tanggal', 'ASC');
        $builder->orderBy('j.jam', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get jadwal by ID with details
     */
    public function getJadwalWithDetails($id)
    {
        $builder = $this->db->table($this->table . ' j');
        $builder->select('j.*, 
                         gp.nama_gereja as nama_gereja_pengirim,
                         gp.alamat as alamat_gereja_pengirim,
                         pp.nama_pendeta as nama_pendeta_pengirim,
                         pp.telp as telp_pendeta_pengirim,
                         gr.nama_gereja as nama_gereja_penerima,
                         gr.alamat as alamat_gereja_penerima,
                         pr.nama_pendeta as nama_pendeta_penerima,
                         pr.telp as telp_pendeta_penerima');
        
        $builder->join('gereja gp', 'gp.id_gereja = j.gereja_pengirim', 'left');
        $builder->join('pendeta pp', 'pp.id_pendeta = j.pendeta_pengirim', 'left');
        $builder->join('gereja gr', 'gr.id_gereja = j.gereja_penerima', 'left');
        $builder->join('pendeta pr', 'pr.id_pendeta = j.pendeta_penerima', 'left');
        
        $builder->where('j.id_jadwal', $id);
        
        return $builder->get()->getRowArray();
    }
    
    /**
     * Get jadwal by bulan and tahun
     */
    public function getJadwalByBulanTahun($bulan, $tahun)
    {
        return $this->where('MONTH(tanggal)', $bulan)
                    ->where('YEAR(tanggal)', $tahun)
                    ->orderBy('tanggal', 'ASC')
                    ->orderBy('jam', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get jadwal by gereja
     */
    public function getJadwalByGereja($id_gereja, $role = 'pengirim')
    {
        if ($role == 'pengirim') {
            return $this->where('gereja_pengirim', $id_gereja)
                        ->orderBy('tanggal', 'DESC')
                        ->findAll();
        } else {
            return $this->where('gereja_penerima', $id_gereja)
                        ->orderBy('tanggal', 'DESC')
                        ->findAll();
        }
    }
    
    /**
     * Get jadwal by pendeta
     */
    public function getJadwalByPendeta($id_pendeta, $role = 'pengirim')
    {
        if ($role == 'pengirim') {
            return $this->where('pendeta_pengirim', $id_pendeta)
                        ->orderBy('tanggal', 'DESC')
                        ->findAll();
        } else {
            return $this->where('pendeta_penerima', $id_pendeta)
                        ->orderBy('tanggal', 'DESC')
                        ->findAll();
        }
    }
    
    /**
     * Get statistics
     */
    public function getStatistik($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $builder = $this->db->table($this->table);
        
        // Total jadwal per bulan
        $builder->select("MONTH(tanggal) as bulan, COUNT(*) as total");
        $builder->where('YEAR(tanggal)', $tahun);
        $builder->groupBy('MONTH(tanggal)');
        $statistikBulanan = $builder->get()->getResultArray();
        
        // Status summary
        $builder->select("status, COUNT(*) as total");
        $builder->where('YEAR(tanggal)', $tahun);
        $builder->groupBy('status');
        $statistikStatus = $builder->get()->getResultArray();
        
        return [
            'bulanan' => $statistikBulanan,
            'status' => $statistikStatus
        ];
    }
    
    /**
     * Update status jadwal
     */
    public function updateStatus($id, $status)
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }
    
    /**
     * Check for schedule conflicts
     */
    public function checkConflict($pendeta_id, $tanggal, $jam, $exclude_id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where("(pendeta_pengirim = $pendeta_id OR pendeta_penerima = $pendeta_id)");
        $builder->where('tanggal', $tanggal);
        $builder->where('jam', $jam);
        $builder->where('status !=', 'dibatalkan');
        
        if ($exclude_id) {
            $builder->where('id_jadwal !=', $exclude_id);
        }
        
        return $builder->countAllResults() > 0;
    }
}