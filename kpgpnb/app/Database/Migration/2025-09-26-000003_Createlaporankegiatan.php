<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaporanProkerTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_laporan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_program' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'judul_laporan' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'periode_mulai' => [
                'type' => 'ENUM',
                'constraint' => ['Mingguan', 'Bulanan', 'Triwulan', 'Semester', 'Tahunan', 'Akhir'],
                'default' => 'Bulanan'
            ],
            'periode_selesai' => [
                'type' => 'ENUM',
                'constraint' => ['Mingguan', 'Bulanan', 'Triwulan', 'Semester', 'Tahunan', 'Akhir'],
                'default' => 'Bulanan'
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'pencapaian' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00
            ],
            'hambatan' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'anggaran_rencana' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
            ],
            'anggaran_realisasi' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
            ],
            'evaluasi' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status_laporan' => [
                'type' => 'ENUM',
                'constraint' => ['Draft', 'Dikirim', 'Direview', 'Disetujui', 'Ditolak', 'Revisi'],
                'default' => 'Draft'
            ],
            'tanggal_kirim' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'tanggal_review' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'reviewer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'catatan_review' => [
                'type' => 'TEXT',
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

        $this->forge->addPrimaryKey('id_laporan');
        $this->forge->addKey('id_program');
        $this->forge->addKey('periode_mulai');
        $this->forge->addKey('periode_selesai');
        $this->forge->addKey('status_laporan');
        $this->forge->addKey('tanggal');
        
        // Add foreign key constraint for program
        $this->forge->addForeignKey('id_program', 'program_kerja', 'id_program', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reviewer_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('laporan_proker', true);
    }

    public function down()
    {
        $this->forge->dropTable('laporan_proker', true);
    }
}