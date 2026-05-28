<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'original_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'file_size' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'uploaded_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME'
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
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('category');
        $this->forge->addKey('uploaded_by');
        $this->forge->addKey('is_active');
        
        $this->forge->createTable('media');
    }

    public function down()
    {
        $this->forge->dropTable('media');
    }
}