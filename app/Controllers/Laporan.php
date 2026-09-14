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

        // Gunakan getVar() agar kebal dan bisa menangkap dari POST maupun GET
        $user_id_post  = $this->request->getVar('user_id');
        $kelas_id      = $this->request->getVar('kelas_id');
        $tipe_filter   = $this->request->getVar('tipe_filter');
        $bulan         = $this->request->getVar('bulan');
        $tahun         = $this->request->getVar('tahun');
        $tanggal_mulai = $this->request->getVar('tanggal_mulai');
        $tanggal_akhir = $this->request->getVar('tanggal_akhir');

        // Pastikan default filter jika kosong
        if (empty($tipe_filter)) $tipe_filter = 'bulan';

        $user_id = empty($user_id_post) ? 'semua_data' : $user_id_post;

        // =======================================================
        // LOGIKA JUDUL PINTAR (TARGET SISWA/KELAS)
        // =======================================================
        $teks_judul = "Semua Siswa";
        if ($user_id != 'semua_data') {
            // Ambil nama 1 siswa jika AJAX pilih siswa aktif
            $dt_siswa = $this->db->table('siswa')->where('nisn', $user_id)->get()->getRow();
            if ($dt_siswa) $teks_judul = $dt_siswa->nama_siswa;
        } elseif (!empty($kelas_id)) {
            // Ambil nama kelas jika mem-filter per kelas
            $dt_kelas = $this->db->table('kelas')->where('kelas_id', $kelas_id)->get()->getRow();
            if ($dt_kelas) $teks_judul = "Siswa Kelas " . $dt_kelas->nama_kelas;
        }

        // =======================================================
        // LOGIKA PERIODE (BULAN / RENTANG)
        // =======================================================
        $teks_periode = "";
        $bulans = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        if ($tipe_filter == 'rentang' && !empty($tanggal_mulai) && !empty($tanggal_akhir)) {
            // Format: 01 September 2026 s/d 14 September 2026
            $tgl_m_indo = date('d', strtotime($tanggal_mulai)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_mulai))] . ' ' . date('Y', strtotime($tanggal_mulai));
            $tgl_a_indo = date('d', strtotime($tanggal_akhir)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_akhir))] . ' ' . date('Y', strtotime($tanggal_akhir));

            $teks_periode = "Periode: " . $tgl_m_indo . " s/d " . $tgl_a_indo;
        } else {
            // Fallback ke mode Bulan & Tahun
            if (empty($bulan)) $bulan = date('m');
            if (empty($tahun)) $tahun = date('Y');

            $nama_bulan = $bulans[(int)$bulan] ?? 'Bulan Tidak Valid';
            $teks_periode = "Bulan: " . $nama_bulan . " " . $tahun;
        }

        $data = [
            'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'       => $user_id,
            'kelas_id'      => $kelas_id,
            'tipe_filter'   => $tipe_filter,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            // Variabel matang yang dikirim ke View:
            'teks_judul'    => $teks_judul,
            'teks_periode'  => $teks_periode,
            'area'          => 'siswa'
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
