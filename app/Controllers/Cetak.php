<?php

namespace App\Controllers;

use Mpdf\Mpdf;

class Cetak extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        echo 'ngapain u';
    }

    public function laporan()
    {
        $this->checkAuth();

        // Tambahkan baris pengaman memori dan waktu eksekusi
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $data = $this->getLaporanData();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A3-L',
            'shrink_tables_to_fit' => 1, // Mencegah tabel meluber dan membuat mPDF hang
            'useOddEven' => false,
        ]);

        if ($data['area'] == 'guru') {
            $html = view('laporan/cetak_view_laporan_guru', $data);
        } else if ($data['area'] == 'pegawai') {
            $html = view('laporan/cetak_view_laporan_pegawai', $data);
        } else if ($data['area'] == 'siswa') {
            $html = view('laporan/cetak_view_laporan_siswa', $data);
        } else {
            return 'p';
        }

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function laporan_view_debug()
    {
        $this->checkAuth();
        $data = $this->getLaporanData();

        if ($data['area'] == 'guru') {
            return view('laporan/cetak_view_laporan_guru', $data);
        } else if ($data['area'] == 'pegawai') {
            return view('laporan/cetak_view_laporan_pegawai', $data);
        } else if ($data['area'] == 'siswa') {
            return view('laporan/cetak_view_laporan_siswa', $data);
        } else {
            return 'p';
        }
    }

    private function getLaporanData()
    {
        $user_id_get   = $this->request->getVar('user_id');
        $kelas_id      = $this->request->getVar('kelas_id');
        $tipe_filter   = $this->request->getVar('tipe_filter') ?? 'bulan';
        $bulan         = $this->request->getVar('bulan');
        $tahun         = $this->request->getVar('tahun');
        $tanggal_mulai = $this->request->getVar('tanggal_mulai');
        $tanggal_akhir = $this->request->getVar('tanggal_akhir');
        $area          = $this->request->getVar('area') ?? 'siswa';

        $user_id = empty($user_id_get) ? 'semua_data' : $user_id_get;

        // Logika Judul Pintar
        $teks_judul = "Semua " . ucfirst($area);
        if ($user_id != 'semua_data') {
            if ($area == 'siswa') {
                $dt = $this->db->table('siswa')->where('nisn', $user_id)->get()->getRow();
                if ($dt) $teks_judul = $dt->nama_siswa;
            }
        } elseif (!empty($kelas_id) && $area == 'siswa') {
            $dt_kelas = $this->db->table('kelas')->where('kelas_id', $kelas_id)->get()->getRow();
            if ($dt_kelas) $teks_judul = "Siswa Kelas " . $dt_kelas->nama_kelas;
        }

        // Logika Periode & Tarik API Libur yang Aman
        $teks_periode = "";
        $bulans = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        if (empty($bulan)) $bulan = date('m');
        if (empty($tahun)) $tahun = date('Y');

        if ($tipe_filter == 'rentang' && !empty($tanggal_mulai) && !empty($tanggal_akhir)) {
            $tgl_m_indo = date('d', strtotime($tanggal_mulai)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_mulai))] . ' ' . date('Y', strtotime($tanggal_mulai));
            $tgl_a_indo = date('d', strtotime($tanggal_akhir)) . ' ' . $bulans[(int)date('m', strtotime($tanggal_akhir))] . ' ' . date('Y', strtotime($tanggal_akhir));
            $teks_periode = "Periode: " . $tgl_m_indo . " s/d " . $tgl_a_indo;
        } else {
            $nama_bulan = $bulans[(int)$bulan] ?? 'Bulan Tidak Valid';
            $teks_periode = "Bulan: " . $nama_bulan . " " . $tahun;
        }

        // Fetch Hari Libur dengan cURL yang Aman dari Error 402/Timeout
        $result_holdaydate = [];
        $url = 'https://api-harilibur.vercel.app/api?month=' . (int)$bulan . '&year=' . (int)$tahun;
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http_code == 200 && $response !== false) {
                $decoded = json_decode($response);
                if (is_array($decoded)) $result_holdaydate = $decoded;
            }
        }

        return [
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'           => $user_id,
            'kelas_id'          => $kelas_id,
            'tipe_filter'       => $tipe_filter,
            'bulan'             => $bulan,
            'tahun'             => $tahun,
            'tanggal_mulai'     => $tanggal_mulai,
            'tanggal_akhir'     => $tanggal_akhir,
            'teks_judul'        => $teks_judul,
            'teks_periode'      => $teks_periode,
            'result_holdaydate' => $result_holdaydate,
            'area'              => $area
        ];
    }

    public function surat_panggilan($id_surat_panggilan)
    {
        $this->checkAuth();

        $tgl = $this->request->getPost('tgl');
        $waktu = $this->request->getPost('waktu');
        $tempat = $this->request->getPost('tempat');
        $ambil_nama_hari_eng = date('D', strtotime($tgl));
        $hari = hari_ini($ambil_nama_hari_eng);

        $tahunajaran = cek_tahun_pelajaran_berjalan();
        $getdatasuratpanggilan = $this->db->table('surat_panggilan')
            ->select('*')
            ->join('siswa', 'surat_panggilan.siswa_id = siswa.siswa_id')
            ->join('kelas', 'siswa.kelas_id = kelas.kelas_id')
            ->join('guru', 'kelas.walikelas = guru.guru_id')
            ->where('id_surat_panggilan', $id_surat_panggilan)
            ->get()->getRow();

        $data = [
            'sett_apps'           => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'             => $this->request->getGet('user_id'),
            'keterangan'          => $getdatasuratpanggilan->keterangan ?? '',
            'id_surat_panggilan'  => $getdatasuratpanggilan->id_surat_panggilan ?? '',
            'asal_kelas'          => $getdatasuratpanggilan->nama_kelas ?? '',
            'tahun_ajaran'        => isset($tahunajaran->tgl_awal) ? date('Y', strtotime($tahunajaran->tgl_awal)) . '/' . date('Y', strtotime($tahunajaran->tgl_akhir)) : '',
            'nama_siswa'          => $getdatasuratpanggilan->nama_siswa ?? '',
            'nisn'                => $getdatasuratpanggilan->nisn ?? '',
            'kelas'               => $getdatasuratpanggilan->nama_kelas ?? '',
            'walikelas'           => $getdatasuratpanggilan->nama_guru ?? '',
            'tgl'                 => $tgl,
            'hari'                => $hari,
            'waktu'               => $waktu,
            'tempat'              => $tempat,
        ];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P',
        ]);

        $html = view('surat_panggilan/v_cetak_surat_panggilan', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
