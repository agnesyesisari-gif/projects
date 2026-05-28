<?php

namespace App\Models;

use CodeIgniter\Model;

class PelayananModel extends Model
{
    protected $table      = 'pelayanan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nama_pelayanan', 'deskripsi', 'id_komisi', 'status'
    ];
}
