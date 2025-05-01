<?php

namespace App\Controllers;

use App\Models\UserModel;

class Pengguna extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('pengguna/index', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        return view('pengguna/create', ['title' => 'Tambah Pengguna']);
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[admin,peminjam]'
        ];

        if ($this->validate($rules)) {
            $this->userModel->save([
                'nama' => $this->request->getPost('nama'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role' => $this->request->getPost('role')
            ]);

            return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengguna')->with('error', 'Pengguna tidak ditemukan');
        }

        return view('pengguna/edit', [
            'title' => 'Edit Pengguna',
            'user' => $user
        ]);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,$id]",
            'role' => 'required|in_list[admin,peminjam]'
        ];

        // Jika password diisi, validasi password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
        }

        if ($this->validate($rules)) {
            $data = [
                'id' => $id,
                'nama' => $this->request->getPost('nama'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role')
            ];

            // Update password jika diisi
            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
            }

            $this->userModel->save($data);

            return redirect()->to('/pengguna')->with('success', 'Data pengguna berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user yang dihapus adalah admin utama
        $user = $this->userModel->find($id);
        if ($user && $user['email'] === 'admin@sekolah.com') {
            return redirect()->to('/pengguna')->with('error', 'Tidak dapat menghapus admin utama');
        }

        $this->userModel->delete($id);
        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil dihapus');
    }
}
