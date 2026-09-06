<?php

namespace App\Controllers;

class Pegawai extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    protected function checkAuth()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            header('Location: ' . base_url('auth'));
            exit;
        }
    }

    public function index()
    {
        $this->checkAuth();
        $data = [
            'pegawai_data' => $this->db->table('pegawai')->get()->getResult(),
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('pegawai/pegawai_list', $data);
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'            => 'Create',
            'status_pegawai'    => $this->db->table('status_pegawai')->get()->getResult(),
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'            => base_url('pegawai/create_action'),
            'pegawai_id'        => '',
            'nip' => '',
            'nama_pegawai' => '',
            'jk_kelamin' => '',
            'status_pegawai_id' => '',
            'alamat' => '',
            'no_hp' => '',
            'tempat_lahir' => '',
            'tanggal_lahir'     => '',
            'photo' => '',
            'password' => '',
        ];
        return view('pegawai/pegawai_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        if (!$this->validate(['nip' => 'required|is_unique[pegawai.nip]|is_unique[user.username]', 'nama_pegawai' => 'required', 'password' => 'required'])) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NIP unik.');
            return redirect()->to('/pegawai/create');
        }

        $nip = $this->request->getPost('nip');
        $nama_pegawai = $this->request->getPost('nama_pegawai');

        $this->db->table('user')->insert([
            'password' => sha1($this->request->getPost('password')),
            'username' => $nip,
            'level_id' => 3
        ]);

        $image_name = $nip . '_' . str_replace(' ', '_', $nama_pegawai) . '.png';
        $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
        file_put_contents(FCPATH . 'assets/img/qr/pegawai/' . $image_name, $qrData);

        $photoName = 'default.png';
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/pegawai', $photoName);
        }

        $this->db->table('pegawai')->insert([
            'nip'               => $nip,
            'nama_pegawai'      => $nama_pegawai,
            'jk_kelamin'        => $this->request->getPost('jk_kelamin'),
            'status_pegawai_id' => $this->request->getPost('status_pegawai_id'),
            'alamat'            => $this->request->getPost('alamat'),
            'no_hp'             => $this->request->getPost('no_hp'),
            'tempat_lahir'      => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir'),
            'photo'             => $photoName,
            'qr_code'           => $image_name,
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/pegawai');
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('pegawai')->where('pegawai_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'            => 'Update',
                'status_pegawai'    => $this->db->table('status_pegawai')->get()->getResult(),
                'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'            => base_url('pegawai/update_action'),
                'pegawai_id'        => $row->pegawai_id,
                'nip'               => $row->nip,
                'nip_lama'          => $row->nip,
                'nama_pegawai'      => $row->nama_pegawai,
                'jk_kelamin'        => $row->jk_kelamin,
                'status_pegawai_id' => $row->status_pegawai_id,
                'alamat'            => $row->alamat,
                'no_hp'             => $row->no_hp,
                'tempat_lahir'      => $row->tempat_lahir,
                'tanggal_lahir'     => $row->tanggal_lahir,
                'photo'             => $row->photo ?: 'default.png',
            ];
            return view('pegawai/pegawai_form', $data);
        }
        return redirect()->to('/pegawai');
    }

    public function update_action()
    {
        $this->checkAuth();
        $pegawai_id = $this->request->getPost('pegawai_id');
        $nip_lama = $this->request->getPost('nip_lama');
        $nip = $this->request->getPost('nip');
        $nama_pegawai = $this->request->getPost('nama_pegawai');

        $rules = ['nama_pegawai' => 'required'];
        if ($nip != $nip_lama) {
            $rules['nip'] = 'required|is_unique[pegawai.nip]|is_unique[user.username]';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NIP unik.');
            return redirect()->to('/pegawai/update/' . encrypt_url($pegawai_id));
        }

        $userData = ['username' => $nip, 'level_id' => 3];
        if (!empty($this->request->getPost('password'))) {
            $userData['password'] = sha1($this->request->getPost('password'));
        }
        $this->db->table('user')->where('username', $nip_lama)->update($userData);

        $row = $this->db->table('pegawai')->where('pegawai_id', $pegawai_id)->get()->getRow();
        $image_name = $row->qr_code;

        if ($nip != $nip_lama || $nama_pegawai != $row->nama_pegawai) {
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code);
            }
            $image_name = $nip . '_' . str_replace(' ', '_', $nama_pegawai) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
            file_put_contents(FCPATH . 'assets/img/qr/pegawai/' . $image_name, $qrData);
        }

        $photoName = $this->request->getPost('photo_lama');
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($photoName && $photoName != 'default.png' && file_exists(FCPATH . 'assets/img/pegawai/' . $photoName)) {
                unlink(FCPATH . 'assets/img/pegawai/' . $photoName);
            }
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/pegawai', $photoName);
        }

        $this->db->table('pegawai')->where('pegawai_id', $pegawai_id)->update([
            'nip'               => $nip,
            'nama_pegawai'      => $nama_pegawai,
            'jk_kelamin'        => $this->request->getPost('jk_kelamin'),
            'status_pegawai_id' => $this->request->getPost('status_pegawai_id'),
            'alamat'            => $this->request->getPost('alamat'),
            'no_hp'             => $this->request->getPost('no_hp'),
            'tempat_lahir'      => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir'),
            'photo'             => $photoName,
            'qr_code'           => $image_name,
        ]);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/pegawai');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $row = $this->db->table('pegawai')->where('pegawai_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            if ($row->photo && $row->photo != 'default.png' && file_exists(FCPATH . 'assets/img/pegawai/' . $row->photo)) {
                unlink(FCPATH . 'assets/img/pegawai/' . $row->photo);
            }
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code);
            }

            $this->db->table('pegawai')->where('pegawai_id', $row->pegawai_id)->delete();
            $this->db->table('user')->where('username', $row->nip)->delete();

            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/pegawai');
    }
}
