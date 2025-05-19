<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDendaToPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'denda' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'after' => 'status'
            ],
            'denda_lain' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'after' => 'denda'
            ],
            'tgl_dikembalikan' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'tgl_kembali'
            ],
            'keterangan_denda' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'denda'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', 'denda');
        $this->forge->dropColumn('peminjaman', 'tgl_dikembalikan');
        $this->forge->dropColumn('peminjaman', 'keterangan_denda');
    }
}
