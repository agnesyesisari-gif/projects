<?php

namespace App\Models;

use CodeIgniter\Model;

class AktivitasJemaatModel extends Model
{
    protected $table      = 'aktivitas_jemaat';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'jenis_aktivitas', 'deskripsi', 'tanggal', 'status'
    ];
}
