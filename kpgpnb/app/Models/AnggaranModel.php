<?php
// app/models/Anggaran.php

class Anggaran {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Tambah data anggaran
     */
    public function tambahAnggaran($data) {
        $query = "INSERT INTO anggaran 
                  (id_komisi, nama_anggaran, deskripsi, jumlah, tanggal_mulai, tanggal_selesai, status) 
                  VALUES (:id_komisi, :nama_anggaran, :deskripsi, :jumlah, :tanggal_mulai, :tanggal_selesai, :status)";
        
        $this->db->query($query);
        $this->db->bind(':id_komisi', $data['id_komisi']);
        $this->db->bind(':nama_anggaran', $data['nama_anggaran']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':jumlah', $data['jumlah']);
        $this->db->bind(':tanggal_mulai', $data['tanggal_mulai']);
        $this->db->bind(':tanggal_selesai', $data['tanggal_selesai']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }
    
    /**
     * Get semua anggaran
     */
    public function getAllAnggaran() {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  ORDER BY a.tanggal_dibuat DESC";
        
        $this->db->query($query);
        return $this->db->resultSet();
    }
    
    /**
     * Get anggaran by ID
     */
    public function getAnggaranById($id) {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE a.id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get anggaran by komisi
     */
    public function getAnggaranByKomisi($id_komisi) {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE a.id_komisi = :id_komisi 
                  ORDER BY a.tanggal_dibuat DESC";
        
        $this->db->query($query);
        $this->db->bind(':id_komisi', $id_komisi);
        return $this->db->resultSet();
    }
    
    /**
     * Update anggaran
     */
    public function updateAnggaran($id, $data) {
        $query = "UPDATE anggaran SET 
                  id_komisi = :id_komisi,
                  nama_anggaran = :nama_anggaran,
                  deskripsi = :deskripsi,
                  jumlah = :jumlah,
                  tanggal_mulai = :tanggal_mulai,
                  tanggal_selesai = :tanggal_selesai,
                  status = :status,
                  tanggal_diupdate = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id_komisi', $data['id_komisi']);
        $this->db->bind(':nama_anggaran', $data['nama_anggaran']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':jumlah', $data['jumlah']);
        $this->db->bind(':tanggal_mulai', $data['tanggal_mulai']);
        $this->db->bind(':tanggal_selesai', $data['tanggal_selesai']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Hapus anggaran
     */
    public function deleteAnggaran($id) {
        $query = "DELETE FROM anggaran WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Get anggaran berdasarkan status
     */
    public function getAnggaranByStatus($status) {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE a.status = :status 
                  ORDER BY a.tanggal_dibuat DESC";
        
        $this->db->query($query);
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }
    
    /**
     * Get total anggaran per komisi
     */
    public function getTotalAnggaranPerKomisi() {
        $query = "SELECT k.nama_komisi, SUM(a.jumlah) as total_anggaran, COUNT(a.id) as jumlah_program
                  FROM komisi k 
                  LEFT JOIN anggaran a ON k.id = a.id_komisi 
                  GROUP BY k.id, k.nama_komisi 
                  ORDER BY k.nama_komisi";
        
        $this->db->query($query);
        return $this->db->resultSet();
    }
    
    /**
     * Get anggaran aktif
     */
    public function getAnggaranAktif() {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE a.status = 'aktif' 
                  AND CURDATE() BETWEEN a.tanggal_mulai AND a.tanggal_selesai 
                  ORDER BY a.tanggal_mulai";
        
        $this->db->query($query);
        return $this->db->resultSet();
    }
    
    /**
     * Update status anggaran
     */
    public function updateStatusAnggaran($id, $status) {
        $query = "UPDATE anggaran SET 
                  status = :status,
                  tanggal_diupdate = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Get statistik anggaran
     */
    public function getStatistikAnggaran() {
        $query = "SELECT 
                    COUNT(*) as total_program,
                    SUM(jumlah) as total_anggaran,
                    SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as program_aktif,
                    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as program_selesai,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as program_draft
                  FROM anggaran";
        
        $this->db->query($query);
        return $this->db->single();
    }
    
    /**
     * Get anggaran berdasarkan periode
     */
    public function getAnggaranByPeriode($tahun, $bulan = null) {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE YEAR(a.tanggal_mulai) = :tahun";
        
        if ($bulan) {
            $query .= " AND MONTH(a.tanggal_mulai) = :bulan";
        }
        
        $query .= " ORDER BY a.tanggal_mulai DESC";
        
        $this->db->query($query);
        $this->db->bind(':tahun', $tahun);
        
        if ($bulan) {
            $this->db->bind(':bulan', $bulan);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Search anggaran
     */
    public function searchAnggaran($keyword) {
        $query = "SELECT a.*, k.nama_komisi 
                  FROM anggaran a 
                  LEFT JOIN komisi k ON a.id_komisi = k.id 
                  WHERE a.nama_anggaran LIKE :keyword 
                  OR a.deskripsi LIKE :keyword 
                  OR k.nama_komisi LIKE :keyword 
                  ORDER BY a.tanggal_dibuat DESC";
        
        $this->db->query($query);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->resultSet();
    }
}