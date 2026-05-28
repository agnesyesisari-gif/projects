<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Agnes Sitorus',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1995-05-20',
                'jenis_kel' => 'Perempuan',
                'alamat' => 'Jl.Teladan No. 12',
                'no_telp' => '08123456789',
                'email' => 'agnes@gmail.com',
                'status' => 'aktif',
                'tgl_bergabung' => '2023-01-10',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        // Insert data
        $this->db->table('anggota')->insertBatch($data);

        echo "Seeder anggota berhasil dijalankan!\n";
    }
}
