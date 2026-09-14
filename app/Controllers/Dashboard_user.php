<?php

namespace App\Controllers;

class Dashboard_user extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        if (!session()->get('userid')) {
            return redirect()->to('/auth');
        }

        $user_id = session()->get('userid');
        $cekhari = date('l');

        $cek_absen = $this->db->table('absen')->where(['user_id' => $user_id, 'DATE(tanggal)' => date('Y-m-d')])->countAllResults();
        $cek_libur = $this->db->table('hari_libur')->where('DATE(tanggal)', date('Y-m-d'))->countAllResults();
        $level_id = session()->get('level_id');

        $remark = "";
        $show = "Tidak";
        $masuk = '-';
        $pulang = '-';

        if ($cekhari == 'Sunday') {
            $remark = "Hari Minggu";
        } elseif ($cek_libur > 0) {
            $remark = "Hari Libur";
        } else {
            $row_absen = $this->db->table('absen')->where(['user_id' => $user_id, 'DATE(tanggal)' => date('Y-m-d')])->get()->getRow();

            if ($cek_absen > 0) {
                if ($row_absen->jam_pulang != null) {
                    $remark = "Terimakasih Sudah Absen Hari ini";
                    $masuk = $row_absen->jam_masuk;
                    $pulang = $row_absen->jam_pulang;
                } else {
                    $jam_waktu = $this->db->table('waktu_absen')->where('nama_hari', hari_ini())->get()->getRow();
                    $time = date('H:i:s');

                    $batas_pulang = ($level_id == 2 || $level_id == 3) ? $jam_waktu->jam_pulang_guru : $jam_waktu->jam_pulang_siswa;

                    if ($batas_pulang > $time) {
                        $remark = "Absen Pulang Buka : " . $batas_pulang;
                        $masuk = $row_absen->jam_masuk;
                    } else {
                        $remark = "Absen Pulang";
                        $show = "Ya";
                        $masuk = $row_absen->jam_masuk;
                    }
                }
            } else {
                $jam_waktu = $this->db->table('waktu_absen')->where('nama_hari', hari_ini())->get()->getRow();
                $time = date('H:i:s');

                $batas_masuk = ($level_id == 2 || $level_id == 3) ? $jam_waktu->jam_masuk_guru : $jam_waktu->jam_masuk_siswa;

                if ($batas_masuk > $time) {
                    $remark = "Absen Masuk Buka : " . $batas_masuk;
                } else {
                    $remark = "Absen Masuk";
                    $show = "Ya";
                }
            }
        }

        $pengumuman = $this->db->table('pengumuman')->where('pengumuman_id', 1)->get()->getRow();
        $geo_setting = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'text' => $pengumuman ? $pengumuman->text : '',
            'status_pengumuman' => $pengumuman ? $pengumuman->status : 'Tidak Aktif',
            'remark' => $remark,
            'show' => $show,
            'masuk' => $masuk,
            'pulang' => $pulang,
            'latitude' => $geo_setting ? $geo_setting->latitude : '',
            'longitude' => $geo_setting ? $geo_setting->longitude : '',
            'radius' => $geo_setting ? $geo_setting->radius : '',
            'is_geo' => $geo_setting ? $geo_setting->is_aktif : 'Tidak',
            'is_photo' => $geo_setting ? $geo_setting->is_photo : 'Tidak',
        ];

        return view('dashboard_user', $data);
    }

    // =======================================================
    // API UNTUK GRAFIK DASHBOARD USER (CHART.JS)
    // =======================================================
    public function get_chart_user()
    {
        $user_id = session()->get('userid');
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        // 1. Data Rapor Bulan Ini (Doughnut)
        $hadir = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->whereIn('status_masuk', ['Tepat Waktu', 'Terlambat'])->countAllResults();
        $sakit = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->where('keterangan', 'Sakit')->countAllResults();
        $izin = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->where('keterangan', 'Izin')->countAllResults();

        // Asumsi hari efektif (contoh: 22 hari, bisa disesuaikan dengan fungsi kalender Anda)
        $hari_efektif = 22;
        $alpha = $hari_efektif - ($hadir + $sakit + $izin);
        $alpha = $alpha < 0 ? 0 : $alpha;

        // 2. Data Tren 6 Bulan Terakhir (Bar)
        $label_bulan = [];
        $data_hadir_sem = [];
        $data_absen_sem = []; // Gabungan Sakit, Izin, Alpha

        for ($i = 5; $i >= 0; $i--) {
            $bulan_target = date('m', strtotime("-$i months"));
            $tahun_target = date('Y', strtotime("-$i months"));
            $label_bulan[] = date('M Y', strtotime("-$i months"));

            $h_sem = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_target)->where('YEAR(tanggal)', $tahun_target)->whereIn('status_masuk', ['Tepat Waktu', 'Terlambat'])->countAllResults();
            $s_sem = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_target)->where('YEAR(tanggal)', $tahun_target)->whereIn('keterangan', ['Sakit', 'Izin'])->countAllResults();

            $data_hadir_sem[] = $h_sem;
            $data_absen_sem[] = $s_sem; // Bisa diekspansi lagi jika ingin dipisah
        }

        return $this->response->setJSON([
            'rapor_bulan' => [$hadir, $sakit, $izin, $alpha],
            'trend_semester' => [
                'labels' => $label_bulan,
                'hadir'  => $data_hadir_sem,
                'tidak_hadir' => $data_absen_sem,
            ]
        ]);
    }

    // (Fungsi-fungsi CRUD seperti izin_sakit, dll akan kita migrasikan secara bertahap nanti)
}
