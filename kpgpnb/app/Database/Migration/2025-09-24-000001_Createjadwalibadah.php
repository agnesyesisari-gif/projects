<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJadwalIbadahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jadwal' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nama_ibadah' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'jenis_ibadah' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
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
            'pemimpin_ibadah' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'pemusik' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'pemandu_pujian' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'tema' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'bacaan_alkitab' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['Minggu', 'Tukar Mimbar Klasis'],
                'default' => 'Minggu'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Aktif', 'Selesai', 'Dibatalkan'],
                'default' => 'Aktif'
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

        $this->forge->addPrimaryKey('id_jadwal');
        $this->forge->addKey('tanggal');
        $this->forge->addKey('kategori');
        $this->forge->addKey('status');
        $this->forge->createTable('jadwal_ibadah', true);
    }

    public function down()
    {
        $this->forge->dropTable('jadwal_ibadah', true);
    }
}