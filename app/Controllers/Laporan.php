<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\SaranaModel;
use App\Models\UserModel;
use Dompdf\Dompdf;

class Laporan extends BaseController
{
    protected $peminjamanModel;
    protected $saranaModel;
    protected $userModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->saranaModel = new SaranaModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Default values
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';
        $year = $this->request->getGet('year') ?? date('Y');

        // Get kategori list for filter
        $kategori_list = $this->saranaModel->distinct()->select('kategori')->findAll();
        $kategori_list = array_column($kategori_list, 'kategori');

        // Query peminjaman with filters
        $peminjamanQuery = $this->peminjamanModel
            ->select('peminjaman.*, sarana.nama as nama_sarana, sarana.kategori as kategori_sarana, users.nama as nama_user')
            ->join('sarana', 'sarana.id = peminjaman.sarana_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('tgl_pinjam >=', $start_date)
            ->where('tgl_pinjam <=', $end_date);

        if (!empty($status)) {
            $peminjamanQuery->where('peminjaman.status', $status);
        }

        if (!empty($kategori)) {
            $peminjamanQuery->where('sarana.kategori', $kategori);
        }

        $peminjaman = $peminjamanQuery->orderBy('tgl_pinjam', 'DESC')->paginate(10);
        $pager = $this->peminjamanModel->pager;
        // $total_peminjaman = count($peminjaman);
        $total_peminjaman = $peminjamanQuery->countAllResults(false);

        // Initialize status counts with default values
        $status_counts = [
            'pending' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
            'selesai' => 0
        ];

        // Update counts with actual data
        if (!empty($peminjaman)) {
            $counts = array_count_values(array_column($peminjaman, 'status'));
            foreach ($counts as $status => $count) {
                if (array_key_exists($status, $status_counts)) {
                    $status_counts[$status] = $count;
                }
            }
        }

        // Chart data
        $chart_data = $this->getChartData($year);

        $data = [
            'title' => 'Laporan Peminjaman Sarana',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'selected_kategori' => $kategori,
            'kategori_list' => $kategori_list,
            'peminjaman' => $peminjaman,
            'total_peminjaman' => $total_peminjaman,
            'pager' => $pager,
            'status_counts' => $status_counts,
            'chart_data' => $chart_data,
        ];

        return view('laporan/index', $data);
    }

    private function getChartData($year)
    {
        // Monthly data for selected year
        $monthly_labels = [];
        $monthly_data = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthly_labels[] = date('M', mktime(0, 0, 0, $i, 1));

            $count = $this->peminjamanModel
                ->where("DATE_FORMAT(tgl_pinjam, '%Y-%m')", "$year-$month")
                ->countAllResults();
            $monthly_data[] = $count;
        }

        // Top 5 sarana
        $top_sarana = $this->peminjamanModel
            ->select('sarana.nama, COUNT(peminjaman.id) as total')
            ->join('sarana', 'sarana.id = peminjaman.sarana_id')
            ->groupBy('peminjaman.sarana_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll();

        // Top 5 users
        $top_users = $this->peminjamanModel
            ->select('users.nama, COUNT(peminjaman.id) as total')
            ->join('users', 'users.id = peminjaman.user_id')
            ->groupBy('peminjaman.user_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll();

        return [
            'monthly_labels' => $monthly_labels,
            'monthly_data' => $monthly_data,
            'top_sarana' => $top_sarana,
            'top_users' => $top_users
        ];
    }

    public function exportPDF()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');

        $data = [
            'peminjaman' => $this->getFilteredData($startDate, $endDate, $status),
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('laporan/export_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan-peminjaman.pdf', ['Attachment' => true]);
    }

    public function exportExcel()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');

        $data = $this->getFilteredData($startDate, $endDate, $status);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Peminjam');
        $sheet->setCellValue('C1', 'Sarana');
        $sheet->setCellValue('D1', 'Tanggal Pinjam');
        $sheet->setCellValue('E1', 'Tanggal Kembali');
        $sheet->setCellValue('F1', 'Status');

        // Data
        $row = 2;
        foreach ($data as $key => $item) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $item['nama_user']);
            $sheet->setCellValue('C' . $row, $item['nama_sarana']);
            $sheet->setCellValue('D' . $row, $item['tgl_pinjam']);
            $sheet->setCellValue('E' . $row, $item['tgl_kembali']);
            $sheet->setCellValue('F' . $row, ucfirst($item['status']));
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan-peminjaman.xlsx"');
        $writer->save('php://output');
        exit();
    }

    private function getFilteredData($startDate, $endDate, $status = null)
    {
        return $this->peminjamanModel
            ->select('peminjaman.*, users.nama as nama_user, sarana.nama as nama_sarana')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('sarana', 'sarana.id = peminjaman.sarana_id')
            ->where('peminjaman.tgl_pinjam >=', $startDate)
            ->where('peminjaman.tgl_pinjam <=', $endDate)
            ->when($status, function ($query, $status) {
                return $query->where('peminjaman.status', $status);
            })
            ->orderBy('peminjaman.tgl_pinjam', 'DESC')
            ->findAll();
    }

    // private function getChartData($startDate, $endDate)
    // {
    //     // Data untuk chart bulanan
    //     $monthlyData = $this->peminjamanModel
    //         ->select("DATE_FORMAT(tgl_pinjam, '%Y-%m') as bulan, COUNT(*) as total")
    //         ->where('tgl_pinjam >=', $startDate)
    //         ->where('tgl_pinjam <=', $endDate)
    //         ->groupBy('bulan')
    //         ->orderBy('bulan', 'ASC')
    //         ->findAll();

    //     // Data sarana paling sering dipinjam
    //     $topSarana = $this->peminjamanModel
    //         ->select('sarana.nama, COUNT(*) as total')
    //         ->join('sarana', 'sarana.id = peminjaman.sarana_id')
    //         ->where('tgl_pinjam >=', $startDate)
    //         ->where('tgl_pinjam <=', $endDate)
    //         ->groupBy('sarana.nama')
    //         ->orderBy('total', 'DESC')
    //         ->limit(5)
    //         ->findAll();

    //     return [
    //         'monthly_labels' => array_column($monthlyData, 'bulan'),
    //         'monthly_data' => array_column($monthlyData, 'total'),
    //         'top_sarana' => $topSarana
    //     ];
    // }
}
