<?php

namespace App\Controllers;

class Rangking_absen extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Menginisialisasi koneksi database bawaan CI4
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
    }

    // Fungsi pengganti is_login() dan check_admin() CI3
    protected function checkAuth()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            header('Location: ' . base_url('auth'));
            exit;
        }
    }

    public function index()
    {
        $this->checkAuth();

        // Mengambil parameter GET di CI4
        $thn = $this->request->getGet('tahun_ajaran_id');
        $dataRangking = [];

        if (!empty($thn)) {
            // Mengambil data tahun ajaran menggunakan Query Builder CI4
            $dataTahun = $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $thn)->get()->getRow();

            if ($dataTahun) {
                $tgl_awal = $dataTahun->tgl_awal;
                $tgl_akhir = $dataTahun->tgl_akhir;

                // Menggunakan Query Binding (?) untuk keamanan Eksekusi Raw SQL
                $sql = "SELECT SUM(point) AS point, SUM(point_pulang) AS point_pulang, SUM(point+point_pulang) AS total_ea, absen.user_id, siswa.nama_siswa, kelas.nama_kelas 
                        FROM absen
                        JOIN user ON user.user_id = absen.user_id
                        JOIN siswa ON siswa.nisn = user.username
                        JOIN kelas ON kelas.kelas_id = siswa.kelas_id
                        WHERE user.level_id = 4 
                        AND tanggal >= ? AND tanggal <= ?
                        GROUP BY absen.user_id 
                        ORDER BY total_ea DESC";

                $dataRangking = $this->db->query($sql, [$tgl_awal, $tgl_akhir])->getResult();
            }
        } else {
            $thn = '';
        }

        // Menarik semua data tahun ajaran dan setting aplikasi
        $tahun_ajaran = $this->db->table('tahun_ajaran')->get()->getResult();
        $sett_apps = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

        // Menggabungkan data untuk dikirim ke View
        $data = [
            'tahun_ajar'        => $thn,
            'tahun_ajaran_data' => $tahun_ajaran,
            'dataRangking'      => $dataRangking,
            'sett_apps'         => $sett_apps,
        ];

        // Meload view CI4 (pastikan file view absen/rangking_absen.php menggunakan $this->extend)
        return view('absen/rangking_absen', $data);
    }
}
