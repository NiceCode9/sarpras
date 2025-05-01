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
        // Hitung statistik
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));

        $data = [
            'total_sarana' => $this->saranaModel->countAll(),
            'sarana_rusak' => $this->saranaModel->where('status', 'rusak')->countAllResults(),
            'total_peminjaman' => $this->peminjamanModel->countAll(),
            'total_users' => $this->userModel->countAll(),

            // Statistik peminjaman bulan ini vs bulan lalu
            'current_month_peminjaman' => $this->peminjamanModel
                ->where("DATE_FORMAT(tgl_pinjam, '%Y-%m')", $currentMonth)
                ->countAllResults(),
            'previous_month_peminjaman' => $this->peminjamanModel
                ->where("DATE_FORMAT(tgl_pinjam, '%Y-%m')", $previousMonth)
                ->countAllResults(),

            // Peminjaman terbaru
            'peminjaman_terbaru' => $this->peminjamanModel
                ->select('peminjaman.*, sarana.nama as nama_sarana, users.nama as nama_user')
                ->join('sarana', 'sarana.id = peminjaman.sarana_id')
                ->join('users', 'users.id = peminjaman.user_id')
                ->orderBy('peminjaman.created_at', 'DESC')
                ->findAll(5),

            // Data untuk chart
            'chart_data' => $this->getChartData()
        ];

        return view('dashboard', $data);
    }

    private function getChartData()
    {
        // Data 6 bulan terakhir
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M Y', strtotime($month . '-01'));

            $count = $this->peminjamanModel
                ->where("DATE_FORMAT(tgl_pinjam, '%Y-%m')", $month)
                ->countAllResults();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
