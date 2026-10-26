<?php

namespace App\Controllers;

class User extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();
        $current_user_id = session()->get('userid');

        $data = [
            'my_user'    => $this->db->table('user')->where('user_id', $current_user_id)->get()->getRow(),
            'user_data'  => $this->db->table('user')->where('level_id', 1)->orderBy('user_id', 'ASC')->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('user/user_list', $data);
    }

    public function update_profile()
    {
        $this->checkAuth();
        if (strtolower($this->request->getMethod()) === 'get') {
            return redirect()->to('/user');
        }
        $current_user_id = session()->get('userid');
        $user = $this->db->table('user')->where('user_id', $current_user_id)->get()->getRow();

        if (!$user) {
            session()->setFlashdata('error', 'Data pengguna tidak ditemukan');
            return redirect()->to('/user');
        }

        $nama_lengkap = trim($this->request->getPost('nama_lengkap'));
        $username     = trim($this->request->getPost('username'));
        $email        = trim($this->request->getPost('email'));
        $password_baru = $this->request->getPost('password_baru');
        $konfirmasi   = $this->request->getPost('konfirmasi_password');

        if (empty($username)) {
            session()->setFlashdata('error', 'Username tidak boleh kosong.');
            return redirect()->to('/user');
        }

        // Cek duplikasi username untuk user lain
        $cek_username = $this->db->table('user')
            ->where('username', $username)
            ->where('user_id !=', $current_user_id)
            ->get()->getRow();

        if ($cek_username) {
            session()->setFlashdata('error', 'Username "' . esc($username) . '" sudah digunakan akun lain. Silakan pilih username lain.');
            return redirect()->to('/user');
        }

        $updateData = [
            'nama_lengkap' => $nama_lengkap,
            'username'     => $username,
            'email'        => $email,
        ];

        // Validasi dan Update Password jika diisi
        if (!empty($password_baru)) {
            if (strlen($password_baru) < 4) {
                session()->setFlashdata('error', 'Password baru minimal harus 4 karakter.');
                return redirect()->to('/user');
            }

            if ($password_baru !== $konfirmasi) {
                session()->setFlashdata('error', 'Konfirmasi password baru tidak cocok. Silakan ulangi.');
                return redirect()->to('/user');
            }

            $updateData['password'] = sha1($password_baru);
        }

        // Upload Foto Profil jika ada
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                session()->setFlashdata('error', 'Format foto tidak valid. Gunakan file JPG, PNG, atau WEBP.');
                return redirect()->to('/user');
            }

            $targetDir = FCPATH . 'assets/img/user/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Hapus foto lama jika bukan default
            if (!empty($user->foto) && !in_array($user->foto, ['admin.png', 'default.png']) && file_exists($targetDir . $user->foto)) {
                @unlink($targetDir . $user->foto);
            }

            $newFotoName = 'admin_' . $current_user_id . '_' . time() . '.' . ($file->getClientExtension() ?: 'png');
            $file->move($targetDir, $newFotoName, true);
            $updateData['foto'] = $newFotoName;
        }

        $this->db->table('user')->where('user_id', $current_user_id)->update($updateData);

        // Perbarui sesi username
        session()->set('username', $username);

        session()->setFlashdata('message', 'Profil dan identitas admin berhasil diperbarui!');
        return redirect()->to('/user');
    }

    public function read($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'nama_lengkap' => $row->nama_lengkap ?? $row->username,
                'username'     => $row->username,
                'email'        => $row->email ?? '-',
                'level_id'     => $row->level_id,
                'foto'         => $row->foto ?? 'admin.png',
                'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('user/user_read', $data);
        } else {
            session()->setFlashdata('error', 'Data admin tidak ditemukan');
            return redirect()->to('/user');
        }
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'       => 'Simpan Admin Baru',
            'action'       => base_url('user/create_action'),
            'user_id'      => '',
            'nama_lengkap' => '',
            'username'     => '',
            'email'        => '',
            'password'     => '',
            'foto'         => 'admin.png',
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('user/user_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();
        $nama_lengkap = trim($this->request->getPost('nama_lengkap'));
        $username     = trim($this->request->getPost('username'));
        $email        = trim($this->request->getPost('email'));
        $password     = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Username dan Password wajib diisi.');
            return redirect()->to('/user/create');
        }

        // Cek duplikasi username
        $cek = $this->db->table('user')->where('username', $username)->get()->getRow();
        if ($cek) {
            session()->setFlashdata('error', 'Username sudah terdaftar. Silakan pilih username lain.');
            return redirect()->to('/user/create');
        }

        $fotoName = 'admin.png';
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $targetDir = FCPATH . 'assets/img/user/';
            $fotoName = 'admin_' . time() . '.' . ($file->getClientExtension() ?: 'png');
            $file->move($targetDir, $fotoName, true);
        }

        $this->db->table('user')->insert([
            'nama_lengkap' => $nama_lengkap,
            'username'     => $username,
            'email'        => $email,
            'password'     => sha1($password),
            'level_id'     => 1, // Level 1 untuk Admin Aplikasi
            'is_aktif'     => 'Ya',
            'foto'         => $fotoName
        ]);

        session()->setFlashdata('message', 'Akun admin baru berhasil ditambahkan!');
        return redirect()->to('/user');
    }

    public function update($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'button'       => 'Perbarui Akun Admin',
                'action'       => base_url('user/update_action'),
                'user_id'      => $row->user_id,
                'nama_lengkap' => $row->nama_lengkap ?? '',
                'username'     => $row->username,
                'email'        => $row->email ?? '',
                'password'     => '',
                'foto'         => $row->foto ?? 'admin.png',
                'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('user/user_form', $data);
        } else {
            session()->setFlashdata('error', 'Data admin tidak ditemukan');
            return redirect()->to('/user');
        }
    }

    public function update_action()
    {
        $this->checkAuth();
        $user_id      = (int) $this->request->getPost('user_id');
        $nama_lengkap = trim($this->request->getPost('nama_lengkap'));
        $username     = trim($this->request->getPost('username'));
        $email        = trim($this->request->getPost('email'));
        $password     = $this->request->getPost('password');

        $user = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();
        if (!$user) {
            session()->setFlashdata('error', 'Data user tidak ditemukan.');
            return redirect()->to('/user');
        }

        // Cek duplikasi username
        $cek = $this->db->table('user')
            ->where('username', $username)
            ->where('user_id !=', $user_id)
            ->get()->getRow();
        if ($cek) {
            session()->setFlashdata('error', 'Username sudah dipakai oleh akun lain.');
            return redirect()->to('/user/update/' . encrypt_url($user_id));
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'username'     => $username,
            'email'        => $email,
        ];

        if (!empty($password)) {
            $data['password'] = sha1($password);
        }

        // Upload foto baru jika ada
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $targetDir = FCPATH . 'assets/img/user/';
            if (!empty($user->foto) && !in_array($user->foto, ['admin.png', 'default.png']) && file_exists($targetDir . $user->foto)) {
                @unlink($targetDir . $user->foto);
            }
            $newFotoName = 'admin_' . $user_id . '_' . time() . '.' . ($file->getClientExtension() ?: 'png');
            $file->move($targetDir, $newFotoName, true);
            $data['foto'] = $newFotoName;
        }

        $this->db->table('user')->where('user_id', $user_id)->update($data);

        session()->setFlashdata('message', 'Data admin berhasil diperbarui!');
        return redirect()->to('/user');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $current_user_id = session()->get('userid');

        if ($real_id == $current_user_id) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
            return redirect()->to('/user');
        }

        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $targetDir = FCPATH . 'assets/img/user/';
            if (!empty($row->foto) && !in_array($row->foto, ['admin.png', 'default.png']) && file_exists($targetDir . $row->foto)) {
                @unlink($targetDir . $row->foto);
            }
            $this->db->table('user')->where('user_id', $real_id)->delete();
            session()->setFlashdata('message', 'Akun admin berhasil dihapus!');
        } else {
            session()->setFlashdata('error', 'Data admin tidak ditemukan.');
        }
        return redirect()->to('/user');
    }
}