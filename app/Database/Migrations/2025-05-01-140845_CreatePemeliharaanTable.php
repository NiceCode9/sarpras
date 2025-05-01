<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemeliharaanTable extends Migration
{
    public function up()
    {
        // Membuat struktur tabel 'pemeliharaan'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'sarana_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'tgl_mulai' => [
                'type' => 'DATE',
            ],
            'tgl_selesai' => [
                'type' => 'DATE',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary Key
        $this->forge->addForeignKey('sarana_id', 'sarana', 'id', 'CASCADE', 'CASCADE'); // Foreign Key
        $this->forge->createTable('pemeliharaan');
    }

    public function down()
    {
        $this->forge->dropTable('pemeliharaan');
    }
}
