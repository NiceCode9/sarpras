<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJumlahPinjamToPeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'jumlah_pinjam' => [
                'type' => 'integer',
                'default' => 0,
                'after' => 'tgl_kembali'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', 'jumlah_pinjam');
    }
}
