<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriKegiatanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kegiatan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nama_kegiatan' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'jenis_kegiatan' => [
                'type' => 'ENUM',
                'constraint' => ['Ibadah', 'Program', 'Pelayanan', 'Pendidikan', 'Sosial', 'Administrasi', 'Lainnya'],
                'default' => 'Ibadah'
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'waktu' => [
                'type' => 'TIME'
            ]
            'tempat' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'penanggung_jawab' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'warna_kategori' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => '#3498db'
            ],
            'icon_kategori' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'urutan' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0
            ],
            'status_aktif' => [
                'type' => 'ENUM',
                'constraint' => ['Ya', 'Tidak'],
                'default' => 'Ya'
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id_kategori');
        $this->forge->addUniqueKey('kode_kategori');
        $this->forge->addKey('jenis_kegiatan');
        $this->forge->addKey('status_aktif');
        $this->forge->addKey('id_parent');
        $this->forge->addKey('urutan');
        $this->forge->createTable('kategori_kegiatan', true);

        // Add foreign key constraint for parent-child relationship
        $this->forge->addForeignKey('id_parent', 'kategori_kegiatan', 'id_kategori', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_kegiatan', true);
    }
}