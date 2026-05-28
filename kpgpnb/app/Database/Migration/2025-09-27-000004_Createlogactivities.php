<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogActivitiesTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'module' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'activity_type' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'description' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('module');
        $this->forge->addKey('activity_type');
        $this->forge->addKey('created_at');
        
        $this->forge->createTable('log_activities');
    }

    public function down()
    {
        $this->forge->dropTable('log_activities');
    }
}