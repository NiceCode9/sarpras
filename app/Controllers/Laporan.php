<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\SaranaModel;

class Laporan extends BaseController
{
    protected $peminjamanModel;
    protected $saranaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->saranaModel = new SaranaModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $status = $this->request->getGet('status');

        $data = [
            'title' => 'Laporan Peminjaman',
            'peminjaman' => $this->getFilteredData($startDate, $endDate, $status),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'chart_data' => $this->getChartData($startDate, $endDate)
        ];

        return view('laporan/index', $data);
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

        $dompdf = new \Dompdf\Dompdf();
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

    private function getChartData($startDate, $endDate)
    {
        // Data untuk chart bulanan
        $monthlyData = $this->peminjamanModel
            ->select("DATE_FORMAT(tgl_pinjam, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('tgl_pinjam >=', $startDate)
            ->where('tgl_pinjam <=', $endDate)
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->findAll();

        // Data sarana paling sering dipinjam
        $topSarana = $this->peminjamanModel
            ->select('sarana.nama, COUNT(*) as total')
            ->join('sarana', 'sarana.id = peminjaman.sarana_id')
            ->where('tgl_pinjam >=', $startDate)
            ->where('tgl_pinjam <=', $endDate)
            ->groupBy('sarana.nama')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll();

        return [
            'monthly_labels' => array_column($monthlyData, 'bulan'),
            'monthly_data' => array_column($monthlyData, 'total'),
            'top_sarana' => $topSarana
        ];
    }
}
