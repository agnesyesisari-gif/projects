<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgramKerjaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_program' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nama_program' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'id_komisi' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'id_kategori' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'tahun' => [
                'type' => 'YEAR',
                'constraint' => 4
            ],
            'periode' => [
                'type' => 'ENUM',
                'constraint' => ['Januari-Desember', 'Juli-Juni', 'Triwulan', 'Semester', 'Lainnya'],
                'default' => 'Januari-Desember'
            ],
            'tanggal_mulai' => [
                'type' => 'DATE'
            ],
            'tanggal_selesai' => [
                'type' => 'DATE'
            ],
            'tempat' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'anggaran' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
            ],
            'sumber_dana' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'penanggungjawab' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'status_program' => [
                'type' => 'ENUM',
                'constraint' => ['Rencana', 'Berjalan', 'Tertunda', 'Selesai', 'Dibatalkan'],
                'default' => 'Rencana'
            ],
            'progress' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00
            ],
            'dokumen_perencanaan' => [
                'type' => 'JSON',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
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

        $this->forge->addPrimaryKey('id_program');
        $this->forge->addKey('nama_program');
        $this->forge->addKey('id_komisi');
        $this->forge->addKey('id_kategori');
        $this->forge->addKey('tahun');
        $this->forge->addKey('status_program');
        $this->forge->addKey('tanggal_mulai');
        $this->forge->addKey('tanggal_selesai');
        
        // Add foreign key constraints
        $this->forge->addForeignKey('id_komisi', 'komisi', 'id_komisi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_kategori', 'kategori_kegiatan', 'id_kategori', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('program_kerja', true);
    }

    public function down()
    {
        $this->forge->dropTable('program_kerja', true);
    }
}