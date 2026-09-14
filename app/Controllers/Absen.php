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

    // =========================================================================
    // 1. FUNGSI DELETE (Sudah Diperbarui tanpa decrypt_url)
    // =========================================================================
    public function delete($id, $level_id)
    {
        $this->checkAuth();

        // Langsung gunakan parameter asli tanpa decrypt_url
        $real_id = $id;
        $real_level = $level_id;

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

        // Redirect sesuai level_id
        if ($real_level == '2') return redirect()->to('/absen/guru');
        if ($real_level == '3') return redirect()->to('/absen/pegawai');
        return redirect()->to('/absen/siswa');
    }

    // =========================================================================
    // 2. FUNGSI CREATE (Menampilkan Form Tambah)
    // =========================================================================
    public function create($level_id)
    {
        $this->checkAuth();

        // Ambil data user sesuai level_id untuk pilihan dropdown di Form
        $users = $this->db->table('user')->where('level_id', $level_id)->get()->getResult();
        foreach ($users as $u) {
            if ($level_id == 2) {
                $dt = $this->db->table('guru')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? $dt->nama_guru : 'Unknown';
            } elseif ($level_id == 3) {
                $dt = $this->db->table('pegawai')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? $dt->nama_pegawai : 'Unknown';
            } elseif ($level_id == 4) {
                $dt = $this->db->table('siswa')->where('nisn', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? $dt->nama_siswa : 'Unknown';
            }
        }

        $data = [
            'button'        => 'Create',
            'action'        => base_url("absen/create_action/$level_id"),
            'level_id'      => $level_id,
            'user_data'     => $users,
            'absen_id'      => '',
            'user_id'       => '',
            'tanggal'       => date('Y-m-d'),
            'keterangan'    => '',
            'jam_masuk'     => '',
            'jam_pulang'    => '',
            'status_masuk'  => '',
            'status_pulang' => '',
            'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('absen/absen_form', $data);
    }

    // =========================================================================
    // 3. FUNGSI CREATE ACTION (Memproses Data Tambah)
    // =========================================================================
    public function create_action($level_id)
    {
        $this->checkAuth();

        $data = [
            'user_id'       => $this->request->getPost('user_id'),
            'tanggal'       => $this->request->getPost('tanggal'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'jam_masuk'     => $this->request->getPost('jam_masuk'),
            'jam_pulang'    => $this->request->getPost('jam_pulang'),
            'status_masuk'  => $this->request->getPost('status_masuk'),
            'status_pulang' => $this->request->getPost('status_pulang'),
        ];

        $this->db->table('absen')->insert($data);
        session()->setFlashdata('message', 'Data Absen berhasil ditambahkan.');

        if ($level_id == '2') return redirect()->to('/absen/guru');
        if ($level_id == '3') return redirect()->to('/absen/pegawai');
        return redirect()->to('/absen/siswa');
    }

    // =========================================================================
    // 4. FUNGSI UPDATE (Menampilkan Form Edit)
    // =========================================================================
    public function update($id, $level_id)
    {
        $this->checkAuth();

        $row = $this->db->table('absen')->where('absen_id', $id)->get()->getRow();

        if ($row) {
            // Ambil data user sesuai level_id untuk pilihan dropdown
            $users = $this->db->table('user')->where('level_id', $level_id)->get()->getResult();
            foreach ($users as $u) {
                if ($level_id == 2) {
                    $dt = $this->db->table('guru')->where('nip', $u->username)->get()->getRow();
                    $u->nama_lengkap = $dt ? $dt->nama_guru : 'Unknown';
                } elseif ($level_id == 3) {
                    $dt = $this->db->table('pegawai')->where('nip', $u->username)->get()->getRow();
                    $u->nama_lengkap = $dt ? $dt->nama_pegawai : 'Unknown';
                } elseif ($level_id == 4) {
                    $dt = $this->db->table('siswa')->where('nisn', $u->username)->get()->getRow();
                    $u->nama_lengkap = $dt ? $dt->nama_siswa : 'Unknown';
                }
            }

            $data = [
                'button'        => 'Update',
                'action'        => base_url("absen/update_action/$level_id"),
                'level_id'      => $level_id,
                'user_data'     => $users,
                'absen_id'      => $row->absen_id,
                'user_id'       => $row->user_id,
                'tanggal'       => $row->tanggal,
                'keterangan'    => $row->keterangan,
                'jam_masuk'     => $row->jam_masuk,
                'jam_pulang'    => $row->jam_pulang,
                'status_masuk'  => $row->status_masuk,
                'status_pulang' => $row->status_pulang,
                'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];

            return view('absen/absen_form', $data);
        } else {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            if ($level_id == '2') return redirect()->to('/absen/guru');
            if ($level_id == '3') return redirect()->to('/absen/pegawai');
            return redirect()->to('/absen/siswa');
        }
    }

    // =========================================================================
    // 5. FUNGSI UPDATE ACTION (Memproses Data Edit)
    // =========================================================================
    public function update_action($level_id)
    {
        $this->checkAuth();

        $absen_id = $this->request->getPost('absen_id');

        $data = [
            'user_id'       => $this->request->getPost('user_id'),
            'tanggal'       => $this->request->getPost('tanggal'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'jam_masuk'     => $this->request->getPost('jam_masuk'),
            'jam_pulang'    => $this->request->getPost('jam_pulang'),
            'status_masuk'  => $this->request->getPost('status_masuk'),
            'status_pulang' => $this->request->getPost('status_pulang'),
        ];

        $this->db->table('absen')->where('absen_id', $absen_id)->update($data);
        session()->setFlashdata('message', 'Data Absen berhasil diperbarui.');

        if ($level_id == '2') return redirect()->to('/absen/guru');
        if ($level_id == '3') return redirect()->to('/absen/pegawai');
        return redirect()->to('/absen/siswa');
    }
}
