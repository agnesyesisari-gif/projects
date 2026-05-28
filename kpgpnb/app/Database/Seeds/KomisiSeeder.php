<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KomisiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_komisi' => 'Komisi Pemuda dan Remaja',
                'slug' => 'pemuda-remaja',
                'deskripsi' => 'Mengelola kegiatan rohani dan pengembangan karakter untuk pemuda dan remaja',
                'ketua_id' => 14,
                'wakil_ketua_id' => 15,
                'sekretaris_id' => 16,
                'bendahara_id' => 17,
                'warna' => '#F39C12', // Orange
                'icon' => 'fas fa-users',
                'tujuan' => 'Membentuk generasi muda yang kuat dalam iman dan karakter',
                'visi' => 'Pemuda yang menjadi garam dan terang di dunia',
                'misi' => json_encode([
                    'Mengadakan ibadah pemuda',
                    'Kegiatan pembinaan karakter',
                    'Retret dan kunjungan',
                    'Pengembangan talenta muda'
                ]),
                'jumlah_anggota' => 30,
                'status' => 'active',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'nama_komisi' => 'Komisi Anak dan Sekolah Minggu',
                'slug' => 'anak-sekolah-minggu',
                'deskripsi' => 'Mengelola pelayanan anak-anak dan sekolah minggu',
                'ketua_id' => 18,
                'wakil_ketua_id' => 19,
                'sekretaris_id' => 20,
                'bendahara_id' => 21,
                'warna' => '#9B59B6', // Ungu
                'icon' => 'fas fa-child',
                'tujuan' => 'Mengajarkan nilai-nilai Kristiani sejak dini kepada anak-anak',
                'visi' => 'Anak-anak yang tumbuh dalam takut akan Tuhan',
                'misi' => json_encode([
                    'Mengajar sekolah minggu',
                    'Mengadakan kegiatan anak',
                    'Melatih guru sekolah minggu',
                    'Menyediakan materi pengajaran'
                ]),
                'jumlah_anggota' => 18,
                'status' => 'active',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
        ];

        // Using Query Builder to insert data
        $this->db->table('komisi')->insertBatch($data);

        // Insert sub-komisi untuk struktur yang lebih detail
        $subKomisiData = [
            // Sub-komisi untuk Komisi Pelayanan Ibadah
            [
                'komisi_id' => 1,
                'nama_sub_komisi' => 'Tim Liturgi',
                'deskripsi' => 'Bertanggung jawab atas tata ibadah dan pembacaan firman',
                'penanggung_jawab_id' => 42,
                'created_at' => Time::now()
            ],
            [
                'komisi_id' => 1,
                'nama_sub_komisi' => 'Tim Doa',
                'deskripsi' => 'Memimpin doa dalam ibadah dan pertemuan',
                'penanggung_jawab_id' => 43,
                'created_at' => Time::now()
            ],
            [
                'komisi_id' => 1,
                'nama_sub_komisi' => 'Tim Kolekte',
                'deskripsi' => 'Mengelola persembahan dan kolekte',
                'penanggung_jawab_id' => 44,
                'created_at' => Time::now()
            ],

            // Sub-komisi untuk Komisi Musik dan Pujian
            [
                'komisi_id' => 2,
                'nama_sub_komisi' => 'Tim Vocal',
                'deskripsi' => 'Penyanyi dan paduan suara',
                'penanggung_jawab_id' => 45,
                'created_at' => Time::now()
            ],
            [
                'komisi_id' => 2,
                'nama_sub_komisi' => 'Tim Musik',
                'deskripsi' => 'Pemain alat musik',
                'penanggung_jawab_id' => 46,
                'created_at' => Time::now()
            ],
            [
                'komisi_id' => 2,
                'nama_sub_komisi' => 'Tim Sound System',
                'deskripsi' => 'Pengelola sound system dan audio',
                'penanggung_jawab_id' => 47,
                'created_at' => Time::now()
            ],

            // Sub-komisi untuk Komisi Diakonia
            [
                'komisi_id' => 3,
                'nama_sub_komisi' => 'Tim Visitasi',
                'deskripsi' => 'Mengunjungi jemaat yang sakit',
                'penanggung_jawab_id' => 48,
                'created_at' => Time::now()
            ],
            [
                'komisi_id' => 3,
                'nama_sub_komisi' => 'Tim Bantuan Sosial',
                'deskripsi' => 'Distribusi bantuan kepada yang membutuhkan',
                'penanggung_jawab_id' => 49,
                'created_at' => Time::now()
            ]
        ];

        // Jika tabel sub_komisi ada
        if ($this->db->tableExists('sub_komisi')) {
            $this->db->table('sub_komisi')->insertBatch($subKomisiData);
        }

        echo "Seeder Komisi berhasil dijalankan!\n";
        echo "Total komisi yang ditambahkan: " . count($data) . "\n";
        
        if (isset($subKomisiData)) {
            echo "Total sub-komisi yang ditambahkan: " . count($subKomisiData) . "\n";
        }
    }
}