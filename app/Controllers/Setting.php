<?php

namespace App\Controllers;

use App\Models\SettingModel;

class Setting extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Pengaturan Sistem',
            'denda' => $this->settingModel->getValue('denda_per_hari')
        ];

        return view('setting/index', $data);
    }

    public function updateDenda()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $rules = [
            'denda_per_hari' => 'required|numeric'
        ];

        if ($this->validate($rules)) {
            if ($this->request->getMethod() === 'POST') {
                $denda = $this->request->getPost('denda_per_hari');

                // Update denda
                $this->settingModel->save([
                    'key' => 'denda_per_hari',
                    'value' => $denda,
                    'description' => 'Denda per hari'
                ]);

                return redirect()->to('/setting')->with('success', 'Pengaturan denda berhasil diperbarui');
            } else {
                $this->settingModel->where('key', 'denda_per_hari')->set(['value' => $this->request->getPost('denda_per_hari')])->update();
                return redirect()->to('/setting')->with('success', 'Nilai denda berhasil diperbarui');
            }
        }

        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
}
