<?php

namespace App\Controllers;

class Notif_siswa extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();

        // Otomatis ubah semua status menjadi 'Terbaca' saat halaman diakses
        $this->db->table('notif_siswa')->update(['status_baca' => 'Terbaca']);

        // Ambil semua data notif (Diurutkan dari yang terbaru/DESC)
        $notif_siswa = $this->db->table('notif_siswa')->orderBy('notif_siswa_id', 'DESC')->get()->getResult();

        $data = [
            'notif_siswa_data' => $notif_siswa,
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow()
        ];

        return view('notif_siswa/notif_siswa_list', $data);
    }

    public function delete($id)
    {
        $this->checkAuth();

        $real_id = decrypt_url($id);
        $row = $this->db->table('notif_siswa')->where('notif_siswa_id', $real_id)->get()->getRow();

        if ($row) {
            $this->db->table('notif_siswa')->where('notif_siswa_id', $real_id)->delete();
            session()->setFlashdata('message', 'Data Notifikasi berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Data Notifikasi tidak ditemukan');
        }

        return redirect()->to(base_url('notif_siswa'));
    }
}
