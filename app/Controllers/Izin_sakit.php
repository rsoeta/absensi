<?php

namespace App\Controllers;

class Izin_sakit extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();

        $data = [
            'izin_sakit_data' => $this->db->table('izin_sakit')->get()->getResult(),
            'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('izin_sakit/izin_sakit_list', $data);
    }

    public function create()
    {
        $this->checkAuth();

        // Ambil semua data user selain Admin (Level 1)
        $users = $this->db->table('user')->where('level_id !=', 1)->get()->getResult();
        foreach ($users as $u) {
            // Kita gabungkan manual namanya disini agar tidak error di View
            if ($u->level_id == 2) {
                $dt = $this->db->table('guru')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Guru - ' . $dt->nama_guru : 'Guru - Unknown';
            } elseif ($u->level_id == 3) {
                $dt = $this->db->table('pegawai')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Pegawai - ' . $dt->nama_pegawai : 'Pegawai - Unknown';
            } elseif ($u->level_id == 4) {
                $dt = $this->db->table('siswa')->where('nisn', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Siswa - ' . $dt->nama_siswa : 'Siswa - Unknown';
            }
        }

        $data = [
            'button'        => 'Create',
            'action'        => base_url('izin_sakit/create_action'),
            'user_data'     => $users,
            'izin_sakit_id' => '',
            'user_id'       => '',
            'photo'         => '',
            'tanggal'       => date('Y-m-d'),
            'tanggal_lama'  => '',
            'keterangan'    => '',
            'deskripsi'     => '',
        ];

        return view('izin_sakit/izin_sakit_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();
        $user_id = $this->request->getPost('user_id');
        $tanggal = $this->request->getPost('tanggal');

        $jml = $this->db->table('izin_sakit')->where('tanggal', $tanggal)->where('user_id', $user_id)->countAllResults();
        $day = date('l', strtotime($tanggal));

        // --- BYPASS HARI MINGGU UNTUK MODE DEVELOPMENT ---
        if (env('CI_ENVIRONMENT') === 'development' || (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'development')) {
            $day = 'Monday'; // Paksa sistem menganggap ini hari Senin agar lolos validasi
        }
        // -------------------------------------------------

        if ($jml > 0) {
            session()->setFlashdata('error', 'Sudah ada data pada tanggal tersebut');
            return redirect()->to('/izin_sakit');
        } elseif ($day == 'Sunday') {
            session()->setFlashdata('error', 'Tidak bisa pengajuan izin/sakit di hari Minggu');
            return redirect()->to('/izin_sakit');
        }

        $photo = '';
        $file = $this->request->getFile('photo');
        // ... sisa kode di bawahnya biarkan sama persis ...
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photo = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/izin', $photo);
        }

        $this->db->table('izin_sakit')->insert([
            'user_id'    => $user_id,
            'photo'      => $photo,
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal'    => $tanggal,
            'status'     => 'Waiting',
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ]);

        session()->setFlashdata('message', 'Pengajuan berhasil dibuat.');
        return redirect()->to('/izin_sakit');
    }

    public function update($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->get()->getRow();

        if (!$row) {
            session()->setFlashdata('error', 'Record Not Found');
            return redirect()->to('/izin_sakit');
        }

        $users = $this->db->table('user')->where('level_id !=', 1)->get()->getResult();
        foreach ($users as $u) {
            if ($u->level_id == 2) {
                $dt = $this->db->table('guru')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Guru - ' . $dt->nama_guru : 'Guru - Unknown';
            } elseif ($u->level_id == 3) {
                $dt = $this->db->table('pegawai')->where('nip', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Pegawai - ' . $dt->nama_pegawai : 'Pegawai - Unknown';
            } elseif ($u->level_id == 4) {
                $dt = $this->db->table('siswa')->where('nisn', $u->username)->get()->getRow();
                $u->nama_lengkap = $dt ? 'Siswa - ' . $dt->nama_siswa : 'Siswa - Unknown';
            }
        }

        $data = [
            'button'        => 'Update',
            'action'        => base_url('izin_sakit/update_action'),
            'user_data'     => $users,
            'izin_sakit_id' => $row->izin_sakit_id,
            'user_id'       => $row->user_id,
            'photo'         => $row->photo,
            'tanggal'       => $row->tanggal,
            'tanggal_lama'  => $row->tanggal,
            'keterangan'    => $row->keterangan,
            'deskripsi'     => $row->deskripsi,
        ];

        return view('izin_sakit/izin_sakit_form', $data);
    }

    public function update_action()
    {
        $this->checkAuth();
        $izin_sakit_id = $this->request->getPost('izin_sakit_id');
        $user_id = $this->request->getPost('user_id');
        $tanggal = $this->request->getPost('tanggal');
        $tanggal_lama = $this->request->getPost('tanggal_lama');

        if ($tanggal != $tanggal_lama) {
            $jml = $this->db->table('izin_sakit')->where('tanggal', $tanggal)->where('user_id', $user_id)->countAllResults();
            if ($jml > 0) {
                session()->setFlashdata('error', 'Sudah ada data pada hari tersebut');
                return redirect()->to('/izin_sakit');
            }
        }

        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $izin_sakit_id)->get()->getRow();
        $photo = $this->request->getPost('photo_lama');

        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photo = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/izin', $photo);

            // Hapus foto lama jika ada update foto baru
            if ($row->photo && file_exists(FCPATH . 'assets/img/izin/' . $row->photo)) {
                unlink(FCPATH . 'assets/img/izin/' . $row->photo);
            }
        }

        $this->db->table('izin_sakit')->where('izin_sakit_id', $izin_sakit_id)->update([
            'user_id'    => $user_id,
            'photo'      => $photo,
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal'    => $tanggal,
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ]);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/izin_sakit');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->get()->getRow();

        if ($row) {
            // Hapus file fisik bukti surat
            if ($row->photo != null && $row->photo != '') {
                $target_file = FCPATH . 'assets/img/izin/' . $row->photo;
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }

            $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->delete();
            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/izin_sakit');
    }

    public function update_status()
    {
        $this->checkAuth();
        $izin_sakit_id = $this->request->getPost('izin_sakit_id');
        $status = $this->request->getPost('status');

        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $izin_sakit_id)->get()->getRow();
        if (!$row) return redirect()->to('/izin_sakit');

        $jml = $this->db->table('absen')->where('tanggal', $row->tanggal)->where('user_id', $row->user_id)->countAllResults();

        // Ambil Data Profil User
        $user = $this->db->table('user')->where('user_id', $row->user_id)->get()->getRow();
        $nama_user = 'Siswa/Pegawai';
        $no_hp = '';

        if ($user) {
            if ($user->level_id == 4) { // Jika Siswa
                $profil = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                $nama_user = $profil ? $profil->nama_siswa : 'Unknown';
                $no_hp = $profil ? $profil->no_hp_wali_siswa : '';
            } else { // Jika Guru/Pegawai (opsional untuk notif)
                // Bisa dikembangkan jika guru juga punya wali/nomor sendiri
            }
        }

        if ($jml > 0) {
            session()->setFlashdata('error', 'Sudah ada data absen pada tanggal tersebut.');
        } else {
            // Update tabel Izin Sakit
            $this->db->table('izin_sakit')->where('izin_sakit_id', $izin_sakit_id)->update(['status' => $status]);

            if ($status == 'Approved') {
                $this->db->table('absen')->insert([
                    'user_id'      => $row->user_id,
                    'tanggal'      => $row->tanggal,
                    'keterangan'   => $row->keterangan,
                    'point'        => 1,
                    'point_pulang' => 1,
                ]);
            }

            // Kirim Pesan WA
            $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
            if ($setting && $setting->wa_blast == 'Aktif' && !empty($no_hp)) {
                $status_txt = ($status == 'Approved') ? 'di Setujui' : 'di Tolak';
                $pesan = "Assalamualaikum Wr Wb Bapak/Ibu wali murid. Pengajuan surat {$row->keterangan} Ananda *{$nama_user}* pada tanggal *{$row->tanggal}* {$status_txt} oleh Admin. Salam sehat selalu.";

                $this->db->table('notif')->insert([
                    'no_hp' => $no_hp,
                    'pesan' => $pesan
                ]);
            }
            session()->setFlashdata('message', 'Status Izin/Sakit Terupdate!');
        }
        return redirect()->to('/izin_sakit');
    }

    public function approved_all_data()
    {
        $this->checkAuth();
        $allData = $this->db->table('izin_sakit')->where('status', 'Waiting')->get()->getResult();
        $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

        foreach ($allData as $row) {
            $jml = $this->db->table('absen')->where('tanggal', $row->tanggal)->where('user_id', $row->user_id)->countAllResults();

            if ($jml == 0) {
                // Update ke Approved
                $this->db->table('izin_sakit')->where('izin_sakit_id', $row->izin_sakit_id)->update(['status' => 'Approved']);

                // Masukkan ke Absen
                $this->db->table('absen')->insert([
                    'user_id'      => $row->user_id,
                    'tanggal'      => $row->tanggal,
                    'keterangan'   => $row->keterangan,
                    'point'        => 1,
                    'point_pulang' => 1,
                ]);

                // Notifikasi WA
                $user = $this->db->table('user')->where('user_id', $row->user_id)->get()->getRow();
                if ($user && $user->level_id == 4 && $setting->wa_blast == 'Aktif') {
                    $profil = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                    if ($profil && !empty($profil->no_hp_wali_siswa)) {
                        $pesan = "Assalamualaikum Wr Wb Bapak/Ibu wali murid. Pengajuan surat {$row->keterangan} Ananda *{$profil->nama_siswa}* pada tanggal *{$row->tanggal}* di Setujui oleh Admin secara otomatis. Terimakasih.";
                        $this->db->table('notif')->insert([
                            'no_hp' => $profil->no_hp_wali_siswa,
                            'pesan' => $pesan
                        ]);
                    }
                }
            }
        }

        session()->setFlashdata('message', 'Semua data Izin/Sakit berhasil di-Approved.');
        return redirect()->to('/izin_sakit');
    }

    public function download($gambar)
    {
        $this->checkAuth();
        return $this->response->download(FCPATH . 'assets/img/izin/' . $gambar, null);
    }

    public function detail_siswa()
    {
        $this->checkAuth();
        $user_id = $this->request->getPost('user_id');

        // Eksekusi JOIN manual yang ekuivalen dengan CI3
        $data = $this->db->table('user')
            ->select('siswa.*, kelas.nama_kelas')
            ->join('siswa', 'siswa.nisn = user.username')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id', 'left')
            ->where('user.user_id', $user_id)
            ->get()
            ->getRowArray();

        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }

        return $this->response->setJSON(['status' => 'error']);
    }
}
