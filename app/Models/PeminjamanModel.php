<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'sarana_id', 'tgl_pinjam', 'tgl_kembali', 'jumlah_pinjam', 'alasan', 'status', 'catatan'];
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
}
