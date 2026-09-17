<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Gunakan Query Builder secara konsisten
        $this->db = \Config\Database::connect();
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
        return view('profile');
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username && $password) {
            // Pencarian data user menggunakan Query Builder agar mutlak menjadi Object (getRow)
            $user = $this->db->table('user')
                ->where('username', $username)
                ->where('password', sha1($password))
                ->get()
                ->getRow();

            if ($user) {
                $sessionData = [
                    'userid'   => $user->user_id,
                    'level_id' => $user->level_id,
                    'username' => $user->username // Tambahan Krusial: Dibutuhkan oleh dashboard_user (NISN/NIP)
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
        return redirect()->to('/auth');
    }

    // public function logout()
    // {
    //     // Bersihkan semua session saat logout
    //     session()->remove(['userid', 'level_id', 'username']);
    //     return redirect()->to('/auth');
    // }

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

        $this->db->table('user')->where('user_id', $id)->update($data);
        session()->setFlashdata('message', 'Data Berhasil diupdate');
        return redirect()->to('/auth/profile');
    }

    public function edit_password($id)
    {
        $user = $this->db->table('user')->where('user_id', $id)->get()->getRow();

        if ($user && sha1($this->request->getPost('lama')) == $user->password) {
            $data = [
                'password' => sha1($this->request->getPost('password')),
            ];
            $this->db->table('user')->where('user_id', $id)->update($data);

            session()->setFlashdata('message', 'Data Password Berhasil diupdate');
            return redirect()->to('/auth/logout');
        } else {
            session()->setFlashdata('error', 'Password Lama Salah');
            return redirect()->to('/auth/profile');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }
}
