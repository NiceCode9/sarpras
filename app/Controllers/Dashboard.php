<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\SaranaModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $saranaModel;
    protected $peminjamanModel;
    protected $userModel;

    public function __construct()
    {
        $this->saranaModel = new SaranaModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'total_sarana' => $this->saranaModel->countAll(),
            'sarana_rusak' => $this->saranaModel->where('status', 'rusak')->countAllResults(),
            'total_peminjaman' => $this->peminjamanModel->countAll(),
            'total_users' => $this->userModel->countAll(),
            'peminjaman_terbaru' => $this->peminjamanModel->orderBy('created_at', 'DESC')->findAll(5)
        ];

        return view('dashboard', $data);
    }
}
