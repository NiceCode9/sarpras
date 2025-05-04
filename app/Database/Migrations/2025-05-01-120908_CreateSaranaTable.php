<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSaranaTable extends Migration
{
    // app/Database/Migrations/2023-01-01-000001_CreateSaranaTable.php
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'kategori' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['baik', 'rusak', 'pemeliharaan'],
                'default' => 'baik'
            ],
            'jumlah' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('sarana');
    }

    public function down()
    {
        $this->forge->dropTable('sarana');
    }
}
