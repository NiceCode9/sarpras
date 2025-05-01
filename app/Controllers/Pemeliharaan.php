<?php

namespace App\Controllers;

use App\Models\PemeliharaanModel;
use App\Models\SaranaModel;

class Pemeliharaan extends BaseController
{
    protected $pemeliharaanModel;
    protected $saranaModel;

    public function __construct()
    {
        $this->pemeliharaanModel = new PemeliharaanModel();
        $this->saranaModel = new SaranaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Jadwal Pemeliharaan',
            'pemeliharaan' => $this->pemeliharaanModel->getWithSarana()->findAll(),
            'sarana' => $this->saranaModel->findAll()
        ];

        return view('pemeliharaan/index', $data);
    }

    public function calendar()
    {
        return view('pemeliharaan/calendar', [
            'title' => 'Kalender Pemeliharaan',
            'events' => json_encode($this->pemeliharaanModel->getEventsForCalendar())
        ]);
    }

    public function store()
    {
        $rules = [
            'sarana_id' => 'required|numeric',
            'tgl_mulai' => 'required|valid_date',
            'tgl_selesai' => 'required|valid_date',
            'keterangan' => 'required'
        ];

        if ($this->validate($rules) && strtotime($this->request->getPost('tgl_selesai')) > strtotime($this->request->getPost('tgl_mulai'))) {
            $this->pemeliharaanModel->save([
                'sarana_id' => $this->request->getPost('sarana_id'),
                'tgl_mulai' => $this->request->getPost('tgl_mulai'),
                'tgl_selesai' => $this->request->getPost('tgl_selesai'),
                'keterangan' => $this->request->getPost('keterangan')
            ]);

            // Update status sarana
            $this->saranaModel->update($this->request->getPost('sarana_id'), ['status' => 'pemeliharaan']);

            return redirect()->to('/pemeliharaan')->with('success', 'Jadwal pemeliharaan berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function delete($id)
    {
        $pemeliharaan = $this->pemeliharaanModel->find($id);
        if ($pemeliharaan) {
            // Kembalikan status sarana
            $this->saranaModel->update($pemeliharaan['sarana_id'], ['status' => 'tersedia']);
            $this->pemeliharaanModel->delete($id);
        }

        return redirect()->to('/pemeliharaan')->with('success', 'Jadwal pemeliharaan berhasil dihapus');
    }
}
