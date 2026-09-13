<?php

namespace App\Controllers;

class Surat_panggilan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Asumsi checkAuth() sudah ada di BaseController untuk proteksi admin
        $this->checkAuth();

        $whereto = $this->request->getGet('page');

        if ($whereto == 'list') {
            return $this->list();
        }

        if ($whereto == 'history') {
            return $this->history();
        }

        if ($whereto == null) {
            return redirect()->to('/surat_panggilan?page=list');
        }
    }

    public function list()
    {
        $this->checkAuth();

        $data = [
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            // Join dengan tabel kelas agar nama kelas bisa langsung dimunculkan di list
            'siswa_data' => $this->db->table('siswa')
                ->join('kelas', 'kelas.kelas_id = siswa.kelas_id', 'left')
                ->orderBy('kelas.nama_kelas', 'ASC')
                ->orderBy('siswa.nama_siswa', 'ASC')
                ->get()->getResult(),
        ];

        return view('surat_panggilan/surat_panggilan_list', $data);
    }

    public function history()
    {
        $this->checkAuth();

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'surat_panggilan_data' => $this->db->query("
                SELECT * FROM surat_panggilan
                JOIN siswa ON (siswa.nisn COLLATE utf8mb4_general_ci) = (surat_panggilan.nisn COLLATE utf8mb4_general_ci)
                LEFT JOIN kelas ON kelas.kelas_id = siswa.kelas_id
                ORDER BY id_surat_panggilan DESC
            ")->getResult(),
        ];

        return view('surat_panggilan/surat_panggilan_history', $data);
    }

    public function generate()
    {
        $this->checkAuth();

        $siswa_id = decrypt_url($this->request->getPost('id_siswa'));
        $getdatasiswa = $this->db->table('siswa')->where('siswa_id', $siswa_id)->get()->getRow();

        if (!$getdatasiswa) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data siswa tidak ditemukan'
            ]);
        }

        $tgl_dipanggil = date('Y-m-d', strtotime($this->request->getPost('tanggal_dipanggil')));

        // Update tanggal panggil di tabel siswa
        $this->db->table('siswa')->where('siswa_id', $siswa_id)->update([
            'tgl_panggil_wali' => $tgl_dipanggil
        ]);

        $telat = $this->request->getPost('telat');
        $tidakhadir = $this->request->getPost('tidakhadir');
        $textsurat = "Telah terlambat sebanyak <b>{$telat}x</b> dan tidak hadir selama <b>{$tidakhadir}x</b>";

        $data_sp = [
            'nisn'               => $getdatasiswa->nisn,
            'siswa_id'           => $siswa_id,
            'terakhir_dipanggil' => $tgl_dipanggil,
            'keterangan'         => $textsurat,
            'status_sp'          => 0,
        ];

        // Insert ke tabel surat_panggilan
        $this->db->table('surat_panggilan')->insert($data_sp);
        $id_surat_panggilan = $this->db->insertID();

        if ($this->db->affectedRows() > 0) {
            $status = 'success';
            $message = 'Surat panggilan berhasil dibuat';
        } else {
            $status = 'error';
            $message = 'Surat panggilan gagal dibuat';
        }

        return $this->response->setJSON([
            'status'   => $status,
            'message'  => $message,
            'id_surat' => $id_surat_panggilan
        ]);
    }

    public function update_status_sp($id_encrypted)
    {
        $this->checkAuth();

        $id_surat_panggilan = decrypt_url($id_encrypted);

        $this->db->table('surat_panggilan')
            ->where('id_surat_panggilan', $id_surat_panggilan)
            ->update(['status_sp' => 1]);

        if ($this->db->affectedRows() > 0) {
            session()->setFlashdata('message', 'Surat panggilan berhasil diselesaikan');
        } else {
            session()->setFlashdata('error', 'Surat panggilan gagal diselesaikan');
        }

        return redirect()->to('/surat_panggilan?page=history');
    }

    public function cetak($id_encrypted)
    {
        $this->checkAuth();
        $id_surat = decrypt_url($id_encrypted);

        // Tangkap inputan dari modal (tanggal, waktu, tempat)
        $tgl = $this->request->getPost('tgl');
        $waktu = $this->request->getPost('waktu');
        $tempat = $this->request->getPost('tempat');

        $surat = $this->db->query("
            SELECT * FROM surat_panggilan
            JOIN siswa ON (siswa.nisn COLLATE utf8mb4_general_ci) = (surat_panggilan.nisn COLLATE utf8mb4_general_ci)
            LEFT JOIN kelas ON kelas.kelas_id = siswa.kelas_id
            WHERE id_surat_panggilan = ?
        ", [$id_surat])->getRow();

        if (!$surat) {
            session()->setFlashdata('error', 'Data surat tidak ditemukan.');
            return redirect()->to('/surat_panggilan?page=history');
        }

        // Cari wali kelas
        $walikelas = '-';
        if ($surat->kelas_id) {
            $kelas = $this->db->table('kelas')->where('kelas_id', $surat->kelas_id)->get()->getRow();
            if ($kelas && !empty($kelas->wali_kelas)) {
                $guru = $this->db->table('guru')->where('guru_id', $kelas->wali_kelas)->get()->getRow();
                if ($guru) $walikelas = $guru->nama_guru;
            }
        }

        // Deteksi Tahun Ajaran
        $tgl_sekarang = date('Y-m-d');
        $ta = $this->db->table('tahun_ajaran')
            ->where('tgl_awal <=', $tgl_sekarang)
            ->where('tgl_akhir >=', $tgl_sekarang)
            ->get()->getRow();
        $tahun_ajaran_nama = $ta ? $ta->nama_tahun_ajaran : '-';

        // Konversi Nama Hari
        $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $nama_hari = $hari_array[date('l', strtotime($tgl))];

        $data = [
            'sett_apps'          => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'id_surat_panggilan' => $surat->id_surat_panggilan,
            'asal_kelas'         => $surat->nama_kelas,
            'tahun_ajaran'       => $tahun_ajaran_nama,
            'nama_siswa'         => $surat->nama_siswa,
            'nisn'               => $surat->nisn,
            'kelas'              => $surat->nama_kelas,
            'keterangan'         => $surat->keterangan,
            'hari'               => $nama_hari,
            'tgl'                => $tgl,
            'waktu'              => $waktu,
            'tempat'             => $tempat,
            'walikelas'          => $walikelas,
        ];

        return view('surat_panggilan/cetak', $data);
    }
}
