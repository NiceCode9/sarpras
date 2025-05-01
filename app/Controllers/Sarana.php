<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaranaModel;

class Sarana extends BaseController
{
    protected $saranaModel;

    public function __construct()
    {
        $this->saranaModel = new SaranaModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $keyword = $this->request->getGet('search');

        $query = $this->saranaModel;

        if ($status) {
            $query = $query->where('status', $status);
        }

        if ($keyword) {
            $query = $query->search($keyword);
        }

        $data = [
            'title' => 'Manajemen Sarana',
            'sarana' => $query->paginate(10),
            'pager' => $this->saranaModel->pager,
            'statusFilter' => $status,
            'searchKeyword' => $keyword
        ];

        return view('sarana/sarana', $data);
    }

    public function create()
    {
        return view('sarana/create', ['title' => 'Tambah Sarana Baru']);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'kategori' => 'required',
            'lokasi' => 'required',
            'status' => 'required'
        ];

        if ($this->validate($rules)) {
            $this->saranaModel->save([
                'nama' => $this->request->getPost('nama'),
                'kategori' => $this->request->getPost('kategori'),
                'lokasi' => $this->request->getPost('lokasi'),
                'status' => $this->request->getPost('status'),
                'deskripsi' => $this->request->getPost('deskripsi')
            ]);

            return redirect()->to('/sarana')->with('success', 'Data sarana berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function edit($id)
    {
        $sarana = $this->saranaModel->find($id);
        if (!$sarana) {
            return redirect()->to('/sarana')->with('error', 'Data sarana tidak ditemukan');
        }

        return view('sarana/create', [
            'title' => 'Edit Sarana',
            'sarana' => $sarana
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'kategori' => 'required',
            'lokasi' => 'required',
            'status' => 'required'
        ];

        if ($this->validate($rules)) {
            $this->saranaModel->save([
                'id' => $id,
                'nama' => $this->request->getPost('nama'),
                'kategori' => $this->request->getPost('kategori'),
                'lokasi' => $this->request->getPost('lokasi'),
                'status' => $this->request->getPost('status'),
                'deskripsi' => $this->request->getPost('deskripsi')
            ]);

            return redirect()->to('/sarana')->with('success', 'Data sarana berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function delete($id)
    {
        $this->saranaModel->delete($id);
        return redirect()->to('/sarana')->with('success', 'Data sarana berhasil dihapus');
    }
}
