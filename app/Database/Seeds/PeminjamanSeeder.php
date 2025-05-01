<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'      => 1,
                'sarana_id'    => 1,
                'tgl_pinjam'   => '2025-04-01',
                'tgl_kembali'  => '2025-04-05',
                'alasan'       => 'Digunakan untuk kegiatan pelatihan teknis.',
                'status'       => 'pending',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s')
            ],
        ];

        // Insert data ke tabel peminjaman
        $this->db->table('peminjaman')->insertBatch($data);
    }
}
