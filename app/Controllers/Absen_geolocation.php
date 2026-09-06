<?php

namespace App\Controllers;

class Absen_geolocation extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function update($id)
    {
        // Gunakan fungsi auth global dari BaseController
        $this->checkAuth();

        $real_id = decrypt_url($id);
        $row = $this->db->table('absen_geolocation')->where('id', $real_id)->get()->getRow();

        // Jika data dengan ID 1 tidak ditemukan (tabel kosong), otomatis buat data default
        if (!$row && $real_id == 1) {
            $this->db->table('absen_geolocation')->insert([
                'id'        => 1,
                'is_aktif'  => 'Tidak',
                'is_photo'  => 'Tidak',
                'latitude'  => '-7.378772', // Silakan sesuaikan dengan kordinat default Pakenjeng
                'longitude' => '107.728867',
                'radius'    => 50
            ]);
            // Ambil ulang datanya setelah di-insert
            $row = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();
        }

        if ($row) {
            $data = [
                'button'    => 'Update',
                'action'    => base_url('absen_geolocation/update_action'),
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'id'        => $row->id,
                'is_aktif'  => $row->is_aktif,
                'is_photo'  => $row->is_photo,
                'latitude'  => $row->latitude,
                'longitude' => $row->longitude,
                'radius'    => $row->radius,
            ];
            return view('absen_geolocation/absen_geolocation_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/dashboard');
    }

    public function update_action()
    {
        $this->checkAuth();

        $rules = [
            'is_aktif'  => 'required',
            'is_photo'  => 'required',
            'latitude'  => 'required',
            'longitude' => 'required',
            'radius'    => 'required'
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Semua kolom wajib diisi!');
            return redirect()->to('/absen_geolocation/update/' . encrypt_url($this->request->getPost('id')));
        }

        $data = [
            'is_aktif'  => $this->request->getPost('is_aktif'),
            'is_photo'  => $this->request->getPost('is_photo'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius'    => $this->request->getPost('radius'),
        ];

        $this->db->table('absen_geolocation')->where('id', $this->request->getPost('id'))->update($data);

        session()->setFlashdata('message', 'Pengaturan Geolocation Berhasil Diupdate');
        return redirect()->to('/absen_geolocation/update/' . encrypt_url($this->request->getPost('id')));
    }
}
