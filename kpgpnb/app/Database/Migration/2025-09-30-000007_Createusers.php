<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'jabatan' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'id_komisi' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'komisi', 'pengurus', 'jemaat'],
                'default' => 'jemaat'
            ],
            'telepon' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'foto_profil' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'status_akun' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default' => 'aktif'
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'last_ip' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true
            ],
            'login_attempts' => [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 0
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role');
        $this->forge->addKey('status_akun');
        $this->forge->addKey('id_komisi');
        
        // Add foreign key constraint for komisi
        $this->forge->addForeignKey('id_komisi', 'komisi', 'id_komisi', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'SET NULL');
        
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}