<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProgramKerja extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'tanggal_mulai', 'tanggal_selesai'];
    protected $casts   = [
        'id' => 'integer',
        'komisi_id' => 'integer',
        'anggaran' => 'float',
        'status' => 'string',
        'persentase' => 'integer'
    ];
    
    protected $attributes = [
        'nama_proker' => null,
        'deskripsi' => null,
        'komisi_id' => null,
        'tanggal_mulai' => null,
        'tanggal_selesai' => null,
        'tempat' => null,
        'anggaran' => 0,
        'sumber_dana' => null,
        'penanggung_jawab' => null,
        'status' => 'draft',
        'dokumen' => null
    ];
    
    public function getStatusLabel()
    {
        $statuses = [
            'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-800'],
            'rencana' => ['label' => 'Rencana', 'class' => 'bg-blue-100 text-blue-800'],
            'berjalan' => ['label' => 'Berjalan', 'class' => 'bg-yellow-100 text-yellow-800'],
            'selesai' => ['label' => 'Selesai', 'class' => 'bg-green-100 text-green-800'],
            'tertunda' => ['label' => 'Tertunda', 'class' => 'bg-red-100 text-red-800']
        ];
        
        return $statuses[$this->attributes['status']] ?? $statuses['draft'];
    }
    
    public function getPersentaseClass()
    {
        if ($this->attributes['persentase'] >= 80) {
            return 'bg-green-600';
        } elseif ($this->attributes['persentase'] >= 50) {
            return 'bg-yellow-500';
        } else {
            return 'bg-red-500';
        }
    }
    
    public function formatAnggaran()
    {
        return 'Rp ' . number_format($this->attributes['anggaran'], 0, ',', '.');
    }
}