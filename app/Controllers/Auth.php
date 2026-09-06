<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // Cek jika sudah login, langsung tendang ke dashboard
        if (session()->get('userid')) {
            if (session()->get('level_id') == 1) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/dashboard_user');
            }
        }
        return view('login');
    }

    public function profile()
    {
        return view('profile'); // Nantinya disesuaikan jika menggunakan layout template CI4
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username && $password) {
            // Pencarian data user dengan hash SHA1 sesuai versi aslinya
            $user = $this->userModel->where('username', $username)
                ->where('password', sha1($password))
                ->first();

            if ($user) {
                $sessionData = [
                    'userid'   => $user->user_id,
                    'level_id' => $user->level_id
                ];
                session()->set($sessionData);

                if ($user->level_id == 1) {
                    return redirect()->to('/dashboard');
                } else {
                    return redirect()->to('/dashboard_user');
                }
            } else {
                session()->setFlashdata('gagal', 'Login gagal, username atau password salah');
                return redirect()->to('/auth');
            }
        }
    }

    public function logout()
    {
        session()->remove(['userid', 'level_id']);
        return redirect()->to('/auth');
    }

    public function lock()
    {
        session()->remove('un_lock');
        return redirect()->to('/absensi');
    }

    public function edit_profil($id)
    {
        $data = [
            'name'    => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'email'   => $this->request->getPost('email'),
        ];

        $this->userModel->update($id, $data);
        session()->setFlashdata('message', 'Data Berhasil diupdate');
        return redirect()->to('/auth/profile');
    }

    public function edit_password($id)
    {
        $user = $this->userModel->find($id);

        if ($user && sha1($this->request->getPost('lama')) == $user->password) {
            $data = [
                'password' => sha1($this->request->getPost('password')),
            ];
            $this->userModel->update($id, $data);

            session()->setFlashdata('message', 'Data Password Berhasil diupdate');
            return redirect()->to('/auth/logout');
        } else {
            session()->setFlashdata('error', 'Password Lama Salah');
            return redirect()->to('/auth/profile');
        }
    }
}
