<?php

namespace App\Controllers;

class App_setting extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function update($id)
    {
        // Proteksi Halaman Khusus Admin
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            return redirect()->to('/auth');
        }

        $real_id = decrypt_url($id);
        $row = $this->db->table('app_setting')->where('id', $real_id)->get()->getRow();

        if ($row) {
            // Di dalam fungsi update()
            $data = [
                'button'            => 'Update',
                'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'            => base_url('app_setting/update_action'),
                'id'                => $row->id ?? 1,
                'nama_aplikasi'     => $row->nama_aplikasi ?? '',
                'kepala_sekolah'    => $row->kepala_sekolah ?? '',
                'nama_sekolah'      => $row->nama_sekolah ?? '',
                'alamat_sekolah'    => $row->alamat_sekolah ?? '',
                'logo_sekolah'      => $row->logo_sekolah ?? '',
                'wa_blast'          => $row->wa_blast ?? '',
                'token_fonnte'      => $row->token_fonnte ?? '',
                'template_notif_wa' => $row->template_notif_wa ?? '',
            ];

            // ----------------------------------------------------


            return view('app_setting/app_setting_form', $data);
        } else {
            session()->setFlashdata('error', 'Record Not Found');
            return redirect()->to('/dashboard');
        }
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            return redirect()->to('/auth');
        }

        // Validasi Form Standar CI4
        $rules = [
            'nama_aplikasi'  => 'required',
            'nama_sekolah'   => 'required',
            'alamat_sekolah' => 'required',
            'kepala_sekolah' => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Gagal: Pastikan semua kolom teks terisi dengan benar.');
            return redirect()->back()->withInput();
        }

        $id = $this->request->getPost('id');
        $row = $this->db->table('app_setting')->where('id', $id)->get()->getRow();

        // Tangkap file lama jika tidak ada upload baru
        $logo_sekolah = $this->request->getPost('logo_sekolah_lama');
        $file = $this->request->getFile('logo_sekolah');

        // Proses Eksekusi Upload File CI4
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate nama acak agar aman dari duplikasi
            $newName = $file->getRandomName();
            // Pindahkan file ke direktori public/assets/img/logo
            $file->move('assets/img/logo', $newName);
            $logo_sekolah = $newName;

            // Hapus logo lama dari server
            if ($row->logo_sekolah != null && file_exists('assets/img/logo/' . $row->logo_sekolah)) {
                unlink('assets/img/logo/' . $row->logo_sekolah);
            }
        }


        // Di dalam fungsi update_action() bagian array $data
        $data = [
            'nama_aplikasi'     => $this->request->getPost('nama_aplikasi'),
            'kepala_sekolah'    => $this->request->getPost('kepala_sekolah'),
            'nama_sekolah'      => $this->request->getPost('nama_sekolah'),
            'alamat_sekolah'    => $this->request->getPost('alamat_sekolah'),
            'wa_blast'          => $this->request->getPost('wa_blast'),
            'token_fonnte'      => $this->request->getPost('token_fonnte'),
            'template_notif_wa' => $this->request->getPost('template_notif_wa'),
        ];

        $this->db->table('app_setting')->where('id', $id)->update($data);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/app_setting/update/' . encrypt_url($id));
    }
}
