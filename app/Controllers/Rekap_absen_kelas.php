<?php

namespace App\Controllers;

class Rekap_absen_kelas extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();
        check_admin(); // Memanggil dari fungsi_helper.php yang sudah kita buat

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'tanggal'   => $this->request->getGet('tanggal') // Menangkap input form tanggal
        ];

        return view('rekap_absen_kelas/index', $data);
    }
}
