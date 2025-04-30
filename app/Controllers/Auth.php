<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[8]'
            ];

            if ($this->validate($rules)) {
                $model = new UserModel();
                $user = $model->where('email', $this->request->getPost('email'))->first();

                if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                    $session = session();
                    $session->set([
                        'id' => $user['id'],
                        'nama' => $user['nama'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ]);

                    return redirect()->to('/dashboard');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Email atau password salah');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nama' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'confirm_password' => 'matches[password]'
            ];

            if ($this->validate($rules)) {
                $model = new UserModel();
                $model->save([
                    'nama' => $this->request->getPost('nama'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'peminjam'
                ]);

                return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
