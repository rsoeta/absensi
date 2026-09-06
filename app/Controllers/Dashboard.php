<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // Proteksi halaman
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            return redirect()->to('/auth');
        }

        // Menghitung jumlah user yang absen menggunakan geolocation hari ini
        $geo = $this->db->query("SELECT COUNT(*) as total, user.level_id 
                                 FROM absen 
                                 JOIN user ON absen.user_id = user.user_id 
                                 WHERE absen.is_geolocation = 'Ya' AND DATE(tanggal) = CURDATE() 
                                 GROUP BY user.level_id")->getResult();

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'geo'       => $geo,
            'classnyak' => $this // Passing object controller ke view (sesuai struktur CI3 sebelumnya)
        ];

        return view('dashboard', $data);
    }

    public function user()
    {
        if (!session()->get('userid')) {
            return redirect()->to('/auth');
        }
        return view('dashboard_user');
    }

    public function get_average_time($date, $tipe, $jenisjam = 'jam_masuk')
    {
        // Query kalkulasi rata-rata waktu (jam_masuk / jam_pulang)
        $query = "
            SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(`" . $jenisjam . "`))) as avg_time 
            FROM absen 
            JOIN user ON user.user_id = absen.user_id
            WHERE DATE(tanggal) = '" . $date . "' AND level_id = " . $tipe;

        $result = $this->db->query($query)->getRow();

        if ($result && $result->avg_time) {
            return substr($result->avg_time, 0, -8);
        } else {
            return 'N/A';
        }
    }

    public function get_average_time_list($jenisjam)
    {
        $str = '';
        // Melakukan loop untuk 5 hari terakhir
        for ($i = 0; $i < 5; $i++) {
            $date = date('Y-m-d', strtotime('-' . $i . ' days'));
            $stylny = ($i == 0) ? 'style="font-size: 15px; font-weight: bold; font-style: italic;"' : '';

            $str .= '<tr ' . $stylny . '>
                    <td nowrap="">' . $date . '</td>
                    <td>' . $this->get_average_time($date, 4, $jenisjam) . '</td>
                    <td>' . $this->get_average_time($date, 2, $jenisjam) . '</td>
                    <td>' . $this->get_average_time($date, 3, $jenisjam) . '</td>
                </tr>';
        }

        // Return format JSON standar CI4
        return $this->response->setJSON($str);
    }

    public function get_absensi_month()
    {
        $data = [
            'bulan'        => date('m'),
            'tahun'        => date('Y'),
            'jumlah_masuk' => [],
            'jumlah_izin'  => [],
            'jumlah_sakit' => [],
        ];

        // Mendapatkan total hari dalam bulan ini
        $max_days = cal_days_in_month(CAL_GREGORIAN, $data['bulan'], $data['tahun']);

        for ($i = 1; $i <= $max_days; $i++) {
            $date = $data['tahun'] . '-' . $data['bulan'] . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            // Query builder CI4 untuk count
            $data['jumlah_masuk'][] = $this->db->table('absen')
                ->where('DATE(tanggal)', $date)
                ->where('keterangan', 'Masuk')
                ->countAllResults();

            $data['jumlah_izin'][]  = $this->db->table('absen')
                ->where('DATE(tanggal)', $date)
                ->where('keterangan', 'Izin')
                ->countAllResults();

            $data['jumlah_sakit'][] = $this->db->table('absen')
                ->where('DATE(tanggal)', $date)
                ->where('keterangan', 'Sakit')
                ->countAllResults();
        }

        return $this->response->setJSON($data);
    }

    public function jumlah_status_absensi()
    {
        $arr = [
            'masuk' => [0, ''],
            'izin'  => [0, ''],
            'sakit' => [0, ''],
            'alpa'  => [0, ''],
            'total' => [0, '']
        ];

        $today = date('Y-m-d');

        // Mengambil jumlah per status hari ini
        foreach (['masuk', 'izin', 'sakit'] as $key) {
            $arr[$key][0] = $this->db->table('absen')
                ->where('keterangan', ucfirst($key))
                ->where('DATE(tanggal)', $today)
                ->countAllResults();
        }

        // Mengambil total entitas data master
        $totalguru    = $this->db->table('guru')->countAllResults();
        $totalpegawai = $this->db->table('pegawai')->countAllResults();
        $totalsiswa   = $this->db->table('siswa')->countAllResults();

        $totalseluruhny = $totalguru + $totalpegawai + $totalsiswa;

        // Total akumulasi yang absen hari ini
        $total = $arr['masuk'][0] + $arr['izin'][0] + $arr['sakit'][0];
        $arr['total'][0] = $total;
        $arr['total'][1] = '100%';

        // Menghitung Alpa
        $arr['alpa'][0] = $totalseluruhny - $total;

        // Persentase keseluruhan
        $totalseluruhgurupegawaisiswa = $this->db->table('user')->whereIn('level_id', [1, 2, 3, 4])->countAllResults();
        if ($totalseluruhgurupegawaisiswa == 0) {
            $totalseluruhgurupegawaisiswa = 1;
        }

        foreach ($arr as $key => $value) {
            if ($key == 'total') continue;
            $arr[$key][1] = round($value[0] / $totalseluruhgurupegawaisiswa * 100, 2) . "%";
        }

        return $this->response->setJSON($arr);
    }
}
