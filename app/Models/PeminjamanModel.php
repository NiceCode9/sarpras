<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'sarana_id', 'tgl_pinjam', 'tgl_kembali', 'denda', 'tgl_dikembalikan', 'jumlah_pinjam', 'alasan', 'status', 'catatan'];
    protected $useTimestamps = true;

    // Join dengan tabel sarana dan users
    public function getPeminjamanWithDetails()
    {
        return $this->select('peminjaman.*, sarana.nama as nama_sarana, users.nama as nama_user')
            ->join('sarana', 'sarana.id = peminjaman.sarana_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->orderBy('peminjaman.created_at', 'DESC');
    }

    // Untuk user tertentu
    public function getByUserId($userId)
    {
        return $this->getPeminjamanWithDetails()
            ->where('peminjaman.user_id', $userId);
    }

    public function getPeminjamanAkanBerakhir()
    {
        return $this->where('status', 'disetujui')
            ->where('tgl_kembali >=', date('Y-m-d'))
            ->where('tgl_kembali <=', date('Y-m-d', strtotime('+3 days')))
            ->findAll();
    }

    public function calculateDenda($peminjaman_id)
    {
        $peminjaman = $this->find($peminjaman_id);

        if ($peminjaman && $peminjaman['status'] == 'disetujui' && empty($peminjaman['tgl_dikembalikan'])) {
            $tgl_kembali = strtotime($peminjaman['tgl_kembali']);
            $today = time();

            if ($today > $tgl_kembali) {
                $diff = $today - $tgl_kembali;
                $days_late = floor($diff / (60 * 60 * 24));

                // Denda Rp 10.000 per hari keterlambatan
                $denda = $days_late * 10000;

                $this->update($peminjaman_id, ['denda' => $denda]);
                return $denda;
            }
        }

        return 0;
    }

    public function getTotalDenda($start_date = null, $end_date = null)
    {
        $builder = $this->selectSum('denda');

        if ($start_date) {
            $builder->where('tgl_pinjam >=', $start_date);
        }

        if ($end_date) {
            $builder->where('tgl_pinjam <=', $end_date);
        }

        return $builder->get()->getRow()->denda ?? 0;
    }
}
