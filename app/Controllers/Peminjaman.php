<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\SaranaModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $saranaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->saranaModel = new SaranaModel();
    }

    // Tampilan untuk peminjam
    public function index()
    {
        $data = [
            'title' => 'Peminjaman Sarana',
            'saranaTersedia' => $this->saranaModel->where('jumlah !=', 0)->findAll(),
            'riwayat' => $this->peminjamanModel->getByUserId(session()->get('id'))->findAll()
        ];

        return view('peminjaman/index', $data);
    }

    // Tampilan untuk admin
    public function admin()
    {
        $status = $this->request->getGet('status');

        $data = [
            'title' => 'Manajemen Peminjaman',
            'peminjaman' => $this->peminjamanModel
                ->getPeminjamanWithDetails()
                ->when($status, function ($query, $status) {
                    return $query->where('peminjaman.status', $status);
                })
                ->findAll()
        ];

        return view('peminjaman/admin', $data);
    }

    // Proses peminjaman
    public function create()
    {
        $rules = [
            'sarana_id' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
        ];

        if ($this->validate($rules)) {
            $tglPinjam = strtotime($this->request->getPost('tgl_pinjam'));
            $tglKembali = strtotime($this->request->getPost('tgl_kembali'));

            if ($tglKembali <= $tglPinjam) {
                return redirect()->back()->withInput()->with('errors', ['tgl_kembali' => 'Tanggal kembali harus setelah tanggal pinjam']);
            }
            $this->peminjamanModel->save([
                'user_id' => session()->get('id'),
                'sarana_id' => $this->request->getPost('sarana_id'),
                'tgl_pinjam' => $this->request->getPost('tgl_pinjam'),
                'tgl_kembali' => $this->request->getPost('tgl_kembali'),
                'alasan' => $this->request->getPost('alasan'),
                'status' => 'pending'
            ]);

            // Update status sarana
            $this->saranaModel->update($this->request->getPost('sarana_id'), ['status' => 'dipinjam']);

            return redirect()->to('/peminjaman')->with('success', 'Permohonan peminjaman berhasil diajukan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    // Aksi admin
    public function action($id, $action)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
        }

        $settingModel = new \App\Models\SettingModel();
        $dendaPerHari = $settingModel->getValue('denda_per_hari') ?? 5000;

        $newStatus = '';
        switch ($action) {
            case 'approve':
                $newStatus = 'disetujui';
                break;
            case 'reject':
                $newStatus = 'ditolak';
                // Kembalikan status sarana jika ditolak
                $this->saranaModel->update($peminjaman['sarana_id'], ['status' => 'tersedia']);
                break;
            case 'return':
                $tglKembali = new \DateTime($peminjaman['tgl_kembali']);
                $tglDikembalikan = new \DateTime(date('Y-m-d'));
                $denda = 0;
                $keterangan = null;

                // Hitung denda jika terlambat
                if ($tglDikembalikan > $tglKembali) {
                    $selisihHari = $tglDikembalikan->diff($tglKembali)->days;
                    $denda = $selisihHari * $dendaPerHari;
                    $keterangan = "Terlambat $selisihHari hari (Rp" . number_format($dendaPerHari) . "/hari)";
                }

                $this->peminjamanModel->update($id, [
                    'status' => 'selesai',
                    'tgl_dikembalikan' => date('Y-m-d'),
                    'denda' => $denda,
                    'keterangan_denda' => $keterangan,
                    'catatan' => $this->request->getPost('catatan')
                ]);

                // Kembalikan status sarana
                $this->saranaModel->update($peminjaman['sarana_id'], ['status' => 'tersedia']);

                return redirect()->back()->with(
                    'success',
                    $denda > 0
                        ? "Peminjaman selesai. Denda Rp" . number_format($denda) . " ($keterangan)"
                        : "Peminjaman selesai tanpa denda"
                );
                break;
            default:
                return redirect()->back()->with('error', 'Aksi tidak valid');
        }

        $this->peminjamanModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui');
    }

    public function return($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
        }

        $dendaPerHari = 5000; // Ganti dengan nilai denda per hari yang sesuai

        $tglKembali = new \DateTime($peminjaman['tgl_kembali']);
        $tglDikembalikan = new \DateTime(date('Y-m-d'));
        $denda = 0;
        $keterangan = null;

        // Hitung denda jika terlambat
        if ($tglDikembalikan > $tglKembali) {
            $selisihHari = $tglDikembalikan->diff($tglKembali)->days;
            $denda = $selisihHari * $dendaPerHari;
            $keterangan = "Terlambat $selisihHari hari (Rp" . number_format($dendaPerHari) . "/hari)";
        }

        // Update status peminjaman dan kembalikan sarana
        $this->peminjamanModel->update($id, [
            'status' => 'selesai',
            'tgl_dikembalikan' => date('Y-m-d'),
            'denda' => $denda,
            'keterangan_denda' => $keterangan,
            'catatan' => $this->request->getPost('catatan')
        ]);

        // Kembalikan status sarana
        // $this->saranaModel->update($peminjaman['sarana_id'], ['status' => 'tersedia']);

        return redirect()->back()->with(
            'success',
            $denda > 0
                ? "Peminjaman selesai. Denda Rp" . number_format($denda) . " ($keterangan)"
                : "Peminjaman selesai tanpa denda"
        );
    }
}
