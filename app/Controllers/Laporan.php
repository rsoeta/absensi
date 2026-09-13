<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function laporan_guru()
    {
        $this->checkAuth();
        $query = $this->db->query("SELECT YEAR(tanggal) as tahun FROM absen GROUP BY YEAR(tanggal)");

        $data = [
            'button'     => 'Tampilkan Laporan',
            'tahun_data' => $query->getResult(),
            'user_data'  => $this->db->table('user')->where('level_id', 2)->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'     => base_url('laporan/view_laporan_guru'),
            'user_id'    => old('user_id'),
            'bulan'      => old('bulan'),
            'tahun'      => old('tahun'),
        ];
        return view('laporan/laporan_guru', $data);
    }

    public function laporan_pegawai()
    {
        $this->checkAuth();
        $query = $this->db->query("SELECT YEAR(tanggal) as tahun FROM absen GROUP BY YEAR(tanggal)");

        $data = [
            'button'     => 'Tampilkan Laporan',
            'tahun_data' => $query->getResult(),
            'user_data'  => $this->db->table('user')->where('level_id', 3)->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'     => base_url('laporan/view_laporan_pegawai'),
            'user_id'    => old('user_id'),
            'bulan'      => old('bulan'),
            'tahun'      => old('tahun'),
        ];
        return view('laporan/laporan_pegawai', $data);
    }

    public function laporan_siswa()
    {
        $this->checkAuth();
        $query = $this->db->query("SELECT YEAR(tanggal) as tahun FROM absen GROUP BY YEAR(tanggal)");

        $data = [
            'button'     => 'Tampilkan Laporan',
            'tahun_data' => $query->getResult(),
            'kelas'      => $this->db->table('kelas')->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'     => base_url('laporan/view_laporan_siswa'),
            'user_id'    => old('user_id'),
            'kelas_id'   => old('kelas_id'),
            'bulan'      => old('bulan'),
            'tahun'      => old('tahun'),
        ];
        return view('laporan/laporan_siswa', $data);
    }

    public function view_laporan_guru()
    {
        $this->checkAuth();
        if (!$this->request->getPost('bulan') || !$this->request->getPost('tahun') || !$this->request->getPost('user_id')) {
            return redirect()->to('/laporan/laporan_guru')->withInput();
        }

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'   => $this->request->getPost('user_id'),
            'bulan'     => $this->request->getPost('bulan'),
            'tahun'     => $this->request->getPost('tahun'),
            'area'      => 'guru'
        ];
        return view('laporan/view_laporan_guru', $data);
    }

    public function view_laporan_pegawai()
    {
        $this->checkAuth();
        if (!$this->request->getPost('bulan') || !$this->request->getPost('tahun') || !$this->request->getPost('user_id')) {
            return redirect()->to('/laporan/laporan_pegawai')->withInput();
        }

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'   => $this->request->getPost('user_id'),
            'bulan'     => $this->request->getPost('bulan'),
            'tahun'     => $this->request->getPost('tahun'),
            'area'      => 'pegawai'
        ];
        return view('laporan/view_laporan_pegawai', $data);
    }

    public function view_laporan_siswa()
    {
        $this->checkAuth();
        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'   => $this->request->getPost('user_id'),
            'kelas_id'  => $this->request->getPost('kelas_id'),
            'bulan'     => $this->request->getPost('bulan'),
            'tahun'     => $this->request->getPost('tahun'),
            'area'      => 'siswa'
        ];
        return view('laporan/view_laporan_siswa', $data);
    }

    public function get_data_siswa()
    {
        $id = $this->request->getPost('selectedValue');
        $output = '';

        if (empty($id)) {
            $output .= 'Silahkan pilih Kelas terlebih dahulu';
            echo $output;
            return;
        }

        $kelas_data = $this->db->query("SELECT siswa.*, user.* FROM siswa JOIN user ON user.username = siswa.nisn WHERE siswa.kelas_id = ?", [$id])->getResult();

        if (count($kelas_data) > 0) {
            $output .= '
            <select name="user_id" class="form-control theSelect" required>
                <option value="">-- Pilih --</option>
                <option value="semua_data">-- Semua Siswa --</option>';
            foreach ($kelas_data as $row) {
                $output .= '<option value="' . $row->user_id . '">' . $row->nama_siswa . '</option>';
            }
            $output .= '</select>';
        } else {
            $output .= '
            <select class="form-control" name="user_id" required><option value="">-- Pilih --</option></select>
            <p class="text-warning mt-2">Tidak ada siswa di kelas ini.</p>';
        }

        echo $output;
    }
}
