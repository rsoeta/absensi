<?php

namespace App\Controllers;

class Kedisiplinan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function mapel_umum()
    {
        $this->checkAuth();
        $thn = $this->request->getGet('tahun_ajaran_id');
        $dataRangking = [];

        if ($thn) {
            $dataTahun = $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $thn)->get()->getRow();
            if ($dataTahun) {
                $tgl_awal = $dataTahun->tgl_awal;
                $tgl_akhir = $dataTahun->tgl_akhir;

                $sql = "
                    SELECT 
                        s.siswa_id, s.nama_siswa, s.photo, k.nama_kelas,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'H' THEN 1 ELSE 0 END), 0) AS total_Hadir,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'A' THEN 1 ELSE 0 END), 0) AS total_Alpha,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'B' THEN 1 ELSE 0 END), 0) AS total_Bolos,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'I' THEN 1 ELSE 0 END), 0) AS total_Izin,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'S' THEN 1 ELSE 0 END), 0) AS total_Sakit,
                        COALESCE(
                            (SUM(CASE WHEN am.keterangan = 'A' THEN 5 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'B' THEN 5 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'I' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'S' THEN 1 ELSE 0 END)), 0) AS total_poin
                    FROM siswa s
                    JOIN kelas k ON k.kelas_id = s.kelas_id
                    LEFT JOIN absen_mapel am ON s.siswa_id = am.siswa_id AND am.tanggal >= ? AND am.tanggal <= ?
                    GROUP BY s.siswa_id
                    ORDER BY total_poin DESC";

                $dataRangking = $this->db->query($sql, [$tgl_awal, $tgl_akhir])->getResult();
            }
        }

        $data = [
            'tahun_ajar'        => $thn,
            'tahun_ajaran_data' => $this->db->table('tahun_ajaran')->get()->getResult(),
            'dataRangking'      => $dataRangking,
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'jenis_laporan'     => 'MAPEL UMUM',
            'action_url'        => base_url('kedisiplinan/mapel_umum')
        ];

        return view('kedisiplinan/laporan_kedisiplinan', $data);
    }

    public function mapel_peminatan()
    {
        $this->checkAuth();
        $thn = $this->request->getGet('tahun_ajaran_id');
        $dataRangking = [];

        if ($thn) {
            $dataTahun = $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $thn)->get()->getRow();
            if ($dataTahun) {
                $tgl_awal = $dataTahun->tgl_awal;
                $tgl_akhir = $dataTahun->tgl_akhir;

                $sql = "
                    SELECT 
                        s.siswa_id, s.nama_siswa, s.photo, k.nama_kelas,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'H' THEN 1 ELSE 0 END), 0) AS total_Hadir,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'A' THEN 1 ELSE 0 END), 0) AS total_Alpha,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'B' THEN 1 ELSE 0 END), 0) AS total_Bolos,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'I' THEN 1 ELSE 0 END), 0) AS total_Izin,
                        COALESCE(SUM(CASE WHEN am.keterangan = 'S' THEN 1 ELSE 0 END), 0) AS total_Sakit,
                        COALESCE(
                            (SUM(CASE WHEN am.keterangan = 'A' THEN 5 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'B' THEN 5 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'I' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN am.keterangan = 'S' THEN 1 ELSE 0 END)), 0) AS total_poin
                    FROM siswa s
                    JOIN kelas k ON k.kelas_id = s.kelas_id
                    LEFT JOIN absen_mapel_peminatan am ON s.siswa_id = am.siswa_id AND am.tanggal >= ? AND am.tanggal <= ?
                    GROUP BY s.siswa_id
                    ORDER BY total_poin DESC";

                $dataRangking = $this->db->query($sql, [$tgl_awal, $tgl_akhir])->getResult();
            }
        }

        $data = [
            'tahun_ajar'        => $thn,
            'tahun_ajaran_data' => $this->db->table('tahun_ajaran')->get()->getResult(),
            'dataRangking'      => $dataRangking,
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'jenis_laporan'     => 'MAPEL PEMINATAN',
            'action_url'        => base_url('kedisiplinan/mapel_peminatan')
        ];

        return view('kedisiplinan/laporan_kedisiplinan', $data);
    }
}
