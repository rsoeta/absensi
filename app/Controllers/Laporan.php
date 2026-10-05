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

    public function export_excel_siswa()
    {
        $this->checkAuth();
        $db = \Config\Database::connect();

        $user_id_post  = $this->request->getVar('user_id');
        $kelas_id      = $this->request->getVar('kelas_id');
        $tipe_filter   = $this->request->getVar('tipe_filter');
        $bulan         = $this->request->getVar('bulan');
        $tahun         = $this->request->getVar('tahun');
        $tanggal_mulai = $this->request->getVar('tanggal_mulai');
        $tanggal_akhir = $this->request->getVar('tanggal_akhir');

        if (empty($tipe_filter)) $tipe_filter = 'bulan';
        $user_id = empty($user_id_post) ? 'semua_data' : $user_id_post;

        // 1. LOGIKA JUDUL
        $teks_judul = "Semua Siswa";
        if ($user_id != 'semua_data') {
            $dt_siswa = $db->table('siswa')->where('nisn', $user_id)->get()->getRow();
            if ($dt_siswa) $teks_judul = $dt_siswa->nama_siswa;
        } elseif (!empty($kelas_id)) {
            $dt_kelas = $db->table('kelas')->where('kelas_id', $kelas_id)->get()->getRow();
            if ($dt_kelas) $teks_judul = "Siswa Kelas " . $dt_kelas->nama_kelas;
        }

        // 2. GENERATE TANGGAL DINAMIS
        $periode_dates = [];
        $teks_periode = "";
        $bulans = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        if ($tipe_filter == 'rentang' && !empty($tanggal_mulai) && !empty($tanggal_akhir)) {
            $begin = new \DateTime($tanggal_mulai);
            $end = new \DateTime($tanggal_akhir);
            $end = $end->modify('+1 day');
            $daterange = new \DatePeriod($begin, new \DateInterval('P1D'), $end);
            foreach ($daterange as $date) {
                $periode_dates[] = $date->format("Y-m-d");
            }
            $tgl_m_indo = date('d', strtotime($tanggal_mulai)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_mulai))] . ' ' . date('Y', strtotime($tanggal_mulai));
            $tgl_a_indo = date('d', strtotime($tanggal_akhir)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_akhir))] . ' ' . date('Y', strtotime($tanggal_akhir));
            $teks_periode = "Periode: " . $tgl_m_indo . " s/d " . $tgl_a_indo;
        } else {
            if (empty($bulan)) $bulan = date('m');
            if (empty($tahun)) $tahun = date('Y');
            $hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            for ($x = 1; $x <= $hari; $x++) {
                $periode_dates[] = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($x, 2, '0', STR_PAD_LEFT);
            }
            $nama_bulan = $bulans[(int)$bulan] ?? '';
            $teks_periode = "Bulan: " . $nama_bulan . " " . $tahun;
        }

        // 3. FETCH API HARI LIBUR
        $result_holdaydate = [];
        $months_to_fetch = [];
        foreach ($periode_dates as $pd) {
            $m = date('m', strtotime($pd));
            $y = date('Y', strtotime($pd));
            $months_to_fetch["$y-$m"] = ['month' => $m, 'year' => $y];
        }
        foreach ($months_to_fetch as $my) {
            $url = 'https://api-harilibur.vercel.app/api?month=' . (int)$my['month'] . '&year=' . $my['year'];
            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($http_code == 200 && $response !== false) {
                    $data_libur = json_decode($response);
                    if (is_array($data_libur)) {
                        $result_holdaydate = array_merge($result_holdaydate, $data_libur);
                    }
                }
            }
        }

        // 4. AMBIL DATA USER ID SISWA
        if ($user_id == 'semua_data') {
            if (!empty($kelas_id)) {
                $query_siswa = $db->query("SELECT u.user_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' AND s.kelas_id='$kelas_id' ORDER BY s.nama_siswa ASC")->getResult();
            } else {
                $query_siswa = $db->query("SELECT u.user_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' ORDER BY s.nama_siswa ASC")->getResult();
            }
        } else {
            // Konversi NISN ke user_id
            $u_id = $db->table('user')->where('username', $user_id)->get()->getRow()->user_id ?? $user_id;
            $query_siswa = [(object)['user_id' => $u_id]];
        }

        $data = [
            'teks_judul'        => $teks_judul,
            'teks_periode'      => $teks_periode,
            'periode_dates'     => $periode_dates,
            'result_holdaydate' => $result_holdaydate,
            'query_siswa'       => $query_siswa
        ];

        // 5. HEADER EXCEL
        $filename = "Rekap_Absensi_Siswa_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('laporan/excel_laporan_siswa', $data);
    }
}
