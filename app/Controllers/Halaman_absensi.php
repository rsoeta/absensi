<?php

namespace App\Controllers;

class Halaman_absensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function update($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $row = $this->db->table('halaman_absensi')->where('halaman_absensi_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'               => 'Update',
                'action'               => base_url('halaman_absensi/update_action'),
                'sett_apps'            => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'halaman_absensi_id'   => $row->halaman_absensi_id,
                'is_aktif'             => $row->is_aktif,
                'password_lock_screen' => $row->password_lock_screen,
            ];
            return view('halaman_absensi/halaman_absensi_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/halaman_absensi/update/' . encrypt_url(1));
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $rules = ['is_aktif' => 'required'];

        if (!$this->validate($rules)) {
            return $this->update(encrypt_url($this->request->getPost('halaman_absensi_id')));
        }

        $password = $this->request->getPost('password_lock_screen');
        $data = ['is_aktif' => $this->request->getPost('is_aktif')];

        if (!empty($password)) {
            $data['password_lock_screen'] = sha1($password);
        }

        $this->db->table('halaman_absensi')->where('halaman_absensi_id', $this->request->getPost('halaman_absensi_id'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/halaman_absensi/update/' . encrypt_url(1));
    }
}
