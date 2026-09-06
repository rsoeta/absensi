<?php

namespace App\Controllers;

class Absen extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Kueri dinamis untuk mengambil data absen + join ke tabel profil
     */
    private function getAbsenData($level_id)
    {
        $builder = $this->db->table('absen');
        $builder->select('absen.*, user.level_id');

        if ($level_id == 2) {
            $builder->select('guru.nama_guru as nama_lengkap');
            $builder->join('user', 'user.user_id = absen.user_id');
            $builder->join('guru', 'guru.nip = user.username', 'left');
        } elseif ($level_id == 3) {
            $builder->select('pegawai.nama_pegawai as nama_lengkap');
            $builder->join('user', 'user.user_id = absen.user_id');
            $builder->join('pegawai', 'pegawai.nip = user.username', 'left');
        } elseif ($level_id == 4) {
            $builder->select('siswa.nama_siswa as nama_lengkap');
            $builder->join('user', 'user.user_id = absen.user_id');
            $builder->join('siswa', 'siswa.nisn = user.username', 'left');
        }

        $builder->where('user.level_id', $level_id);
        $builder->orderBy('absen.tanggal', 'DESC');
        return $builder->get()->getResult();
    }

    public function guru()
    {
        $this->checkAuth();
        $data = [
            'absen_data' => $this->getAbsenData(2),
            'level_id'   => 2,
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('absen/absen_list', $data);
    }

    public function pegawai()
    {
        $this->checkAuth();
        $data = [
            'absen_data' => $this->getAbsenData(3),
            'level_id'   => 3,
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('absen/absen_list', $data);
    }

    public function siswa()
    {
        $this->checkAuth();
        $data = [
            'absen_data' => $this->getAbsenData(4),
            'level_id'   => 4,
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('absen/absen_list', $data);
    }

    public function delete($id, $level_id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $real_level = decrypt_url($level_id);

        $row = $this->db->table('absen')->where('absen_id', $real_id)->get()->getRow();
        if ($row) {
            // Hapus foto fisik jika ada
            if ($row->selfie_masuk && file_exists(FCPATH . 'assets/bukti_absen/' . $row->selfie_masuk)) {
                unlink(FCPATH . 'assets/bukti_absen/' . $row->selfie_masuk);
            }
            if ($row->selfie_keluar && file_exists(FCPATH . 'assets/bukti_absen/' . $row->selfie_keluar)) {
                unlink(FCPATH . 'assets/bukti_absen/' . $row->selfie_keluar);
            }

            $this->db->table('absen')->where('absen_id', $real_id)->delete();
            session()->setFlashdata('message', 'Data Absen berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
        }

        if ($real_level == '2') return redirect()->to('/absen/guru');
        if ($real_level == '3') return redirect()->to('/absen/pegawai');
        return redirect()->to('/absen/siswa');
    }
}
