<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaModel extends Model
{
    protected $table = 'sarana';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'kategori', 'lokasi', 'status', 'jumlah', 'deskripsi'];
    protected $useTimestamps = true;

    public function search($keyword)
    {
        return $this->table('sarana')
            ->like('nama', $keyword)
            ->orLike('kategori', $keyword, 'both')
            ->orLike('lokasi', $keyword, 'both');
    }
}
