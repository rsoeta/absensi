<?php

namespace App\Controllers;

class Kios_wajah extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // public function index()
    // {
    //     // Tarik data beserta user_id dengan melakukan JOIN ke tabel user
    //     $siswa = $this->db->table('siswa')
    //         ->select('siswa.nama_siswa, siswa.face_descriptor, user.user_id')
    //         ->join('user', 'user.username = siswa.nisn', 'left')
    //         ->where('siswa.face_descriptor !=', null)
    //         ->get()->getResult();

    //     $guru = $this->db->table('guru')
    //         ->select('guru.nama_guru, guru.face_descriptor, user.user_id')
    //         ->join('user', 'user.username = guru.nip', 'left')
    //         ->where('guru.face_descriptor !=', null)
    //         ->get()->getResult();

    //     $pegawai = $this->db->table('pegawai')
    //         ->select('pegawai.nama_pegawai, pegawai.face_descriptor, user.user_id')
    //         ->join('user', 'user.username = pegawai.nip', 'left')
    //         ->where('pegawai.face_descriptor !=', null)
    //         ->get()->getResult();

    //     $data_master = [];

    //     // Looping data. Pengecekan if($s->user_id) untuk mencegah error jika ada data yatim-piatu
    //     foreach ($siswa as $s) {
    //         if ($s->user_id) $data_master[] = ['label' => 'siswa_' . $s->user_id . '_' . $s->nama_siswa, 'descriptor' => $s->face_descriptor];
    //     }
    //     foreach ($guru as $g) {
    //         if ($g->user_id) $data_master[] = ['label' => 'guru_' . $g->user_id . '_' . $g->nama_guru, 'descriptor' => $g->face_descriptor];
    //     }
    //     foreach ($pegawai as $p) {
    //         if ($p->user_id) $data_master[] = ['label' => 'pegawai_' . $p->user_id . '_' . $p->nama_pegawai, 'descriptor' => $p->face_descriptor];
    //     }

    //     $data = [
    //         'face_data' => json_encode($data_master),
    //         'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow()
    //     ];

    //     return view('kios_wajah/index', $data);
    // }
    public function index()
    {
        // 1. Tarik Data Vektor Wajah (Tetap dipertahankan)
        $siswa = $this->db->table('siswa')->select('siswa.nama_siswa, siswa.face_descriptor, user.user_id')->join('user', 'user.username = siswa.nisn', 'left')->where('siswa.face_descriptor !=', null)->get()->getResult();
        $guru = $this->db->table('guru')->select('guru.nama_guru, guru.face_descriptor, user.user_id')->join('user', 'user.username = guru.nip', 'left')->where('guru.face_descriptor !=', null)->get()->getResult();
        $pegawai = $this->db->table('pegawai')->select('pegawai.nama_pegawai, pegawai.face_descriptor, user.user_id')->join('user', 'user.username = pegawai.nip', 'left')->where('pegawai.face_descriptor !=', null)->get()->getResult();

        $data_master = [];
        foreach ($siswa as $s) {
            if ($s->user_id) $data_master[] = ['label' => 'siswa_' . $s->user_id . '_' . $s->nama_siswa, 'descriptor' => $s->face_descriptor];
        }
        foreach ($guru as $g) {
            if ($g->user_id) $data_master[] = ['label' => 'guru_' . $g->user_id . '_' . $g->nama_guru, 'descriptor' => $g->face_descriptor];
        }
        foreach ($pegawai as $p) {
            if ($p->user_id) $data_master[] = ['label' => 'pegawai_' . $p->user_id . '_' . $p->nama_pegawai, 'descriptor' => $p->face_descriptor];
        }

        // 2. Tarik Data Tabel (Sinkronisasi dengan UI Absensi)
        $tgl_hari_ini = date('Y-m-d');
        $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hari_ini = $hari_array[date('l')];
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        // Riwayat absen hari ini
        $dataabsen = $this->db->table('absen')->where('tanggal', $tgl_hari_ini)->orderBy('jam_masuk', 'DESC')->get()->getResult();

        // Query Siswa Belum Absen (Subquery)
        $subquery = $this->db->table('absen')->select('user_id')->where('tanggal', $tgl_hari_ini)->getCompiledSelect();
        $belum_absen = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id', 'left')
            ->join('user', 'user.username = siswa.nisn', 'left')
            ->where("user.user_id NOT IN ($subquery)", null, false)
            ->get()->getResult();

        $data = [
            'face_data'      => json_encode($data_master),
            'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'nama_hari'      => $hari_ini,
            'jam_masuk_p_g'  => $dtabsentime ? $dtabsentime->jam_masuk_guru : '-',
            'jam_keluar_p_g' => $dtabsentime ? $dtabsentime->jam_pulang_guru : '-',
            'jam_masuk_m'    => $dtabsentime ? $dtabsentime->jam_masuk_siswa : '-',
            'jam_keluar_m'   => $dtabsentime ? $dtabsentime->jam_pulang_siswa : '-',
            'dataabsen'      => $dataabsen,
            'belum_absen'    => $belum_absen
        ];

        return view('kios_wajah/index', $data);
    }

    public function proses_absen_otomatis()
    {
        $raw_label = $this->request->getPost('label'); // Contoh: siswa_15_Budi
        $parts = explode('_', $raw_label);

        if (count($parts) >= 3) {
            $role = $parts[0];
            $user_id = $parts[1];
            $nama = str_replace($role . '_' . $user_id . '_', '', $raw_label);

            $tanggal_hari_ini = date('Y-m-d');
            $waktu_sekarang   = date('H:i:s');

            // 1. Ambil data user & waktu absen berdasarkan hari aktif
            $user = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();
            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'pesan' => 'User tidak ditemukan']);
            }

            $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
            $hari_ini = $hari_array[date('l')];
            $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

            if (!$dtabsentime) {
                return $this->response->setJSON(['status' => 'holiday', 'pesan' => 'Hari ini libur / tidak ada jadwal absen']);
            }

            // Tentukan aturan keterlambatan
            if (in_array($user->level_id, [2, 3])) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } else {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            }

            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i:s');

            $status_masuk = ($waktu_sekarang > $stamp) ? 'Terlambat' : 'Tepat Waktu';
            $point = ($waktu_sekarang > $stamp) ? 3 : 5;

            // Format Tanggal Indonesia untuk WA Blast
            $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
            $tanggal_indo = $hari_ini . ', ' . date('d') . ' ' . $bulan_array[date('m')] . ' ' . date('Y');

            // Cek apakah user sudah absen hari ini
            $cek_absen = $this->db->table('absen')
                ->where('user_id', $user_id)
                ->where('tanggal', $tanggal_hari_ini)
                ->get()->getRow();

            $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

            if (!$cek_absen) {
                // ==========================================
                // PROSES ABSEN MASUK
                // ==========================================
                $this->db->table('absen')->insert([
                    'user_id'        => $user_id,
                    'tanggal'        => $tanggal_hari_ini,
                    'jam_masuk'      => $waktu_sekarang,
                    'status'         => 'Hadir',
                    'status_masuk'   => $status_masuk,
                    'keterangan'     => 'Masuk',
                    'is_geolocation' => 'Tidak', // Kios Fisik Sekolah
                    'point'          => $point
                ]);

                // WA Blast Masuk Khusus Siswa (Level ID 4)
                if ($user->level_id == 4) {
                    $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                    $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                    if ($setting && $setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                        $pesan_wa = str_replace(
                            ['[nama_sekolah]', '[tanggal]', '[nama_siswa]', '[nisn]', '[kelas]', '[tipe_absen]', '[jam_absen]', '[status_absen]'],
                            [$setting->nama_sekolah, $tanggal_indo, $siswa->nama_siswa, $siswa->nisn, $kelas ? $kelas->nama_kelas : '-', 'Masuk', date('H:i'), $status_masuk],
                            $setting->template_notif_wa
                        );

                        $this->db->table('tabel_antrean_wa')->insert([
                            'no_hp'      => $siswa->no_hp_wali_siswa,
                            'pesan'      => $pesan_wa,
                            'status'     => 'pending',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'jenis' => 'Masuk',
                    'nama' => $nama,
                    'waktu' => $waktu_sekarang,
                    'keterangan' => $status_masuk
                ]);
            } else {
                // ==========================================
                // PROSES ABSEN PULANG
                // ==========================================
                if (!empty($cek_absen->jam_pulang) && $cek_absen->jam_pulang != '00:00:00') {
                    return $this->response->setJSON(['status' => 'done', 'pesan' => 'Sudah absen masuk & pulang']);
                }

                // Cek batasan jam boleh pulang
                $allowedtopulang = 0;
                if (in_array($user->level_id, [2, 3]) && $waktu_sekarang >= $dtabsentime->jam_pulang_guru) {
                    $allowedtopulang = 1;
                } elseif ($user->level_id == 4 && $waktu_sekarang >= $dtabsentime->jam_pulang_siswa) {
                    $allowedtopulang = 1;
                }

                // Beri jeda minimal 1 menit dari jam masuk agar tidak serta-merta langsung terhitung pulang
                $selisih_waktu = strtotime($waktu_sekarang) - strtotime($cek_absen->jam_masuk);

                if ($allowedtopulang == 1 && $selisih_waktu > 60) {
                    $this->db->table('absen')->where('absen_id', $cek_absen->absen_id)->update([
                        'jam_pulang'    => $waktu_sekarang,
                        'status_pulang' => 'Tepat Waktu',
                        'point_pulang'  => 5
                    ]);

                    // WA Blast Pulang Khusus Siswa (Level ID 4)
                    if ($user->level_id == 4) {
                        $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                        $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                        if ($setting && $setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                            $pesan_wa = str_replace(
                                ['[nama_sekolah]', '[tanggal]', '[nama_siswa]', '[nisn]', '[kelas]', '[tipe_absen]', '[jam_absen]', '[status_absen]'],
                                [$setting->nama_sekolah, $tanggal_indo, $siswa->nama_siswa, $siswa->nisn, $kelas ? $kelas->nama_kelas : '-', 'Pulang', date('H:i'), 'Tepat Waktu'],
                                $setting->template_notif_wa
                            );

                            $this->db->table('tabel_antrean_wa')->insert([
                                'no_hp'      => $siswa->no_hp_wali_siswa,
                                'pesan'      => $pesan_wa,
                                'status'     => 'pending',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    return $this->response->setJSON([
                        'status' => 'success',
                        'jenis' => 'Pulang',
                        'nama' => $nama,
                        'waktu' => $waktu_sekarang
                    ]);
                } else {
                    return $this->response->setJSON(['status' => 'cooldown', 'pesan' => 'Belum waktunya pulang atau jeda terlalu singkat']);
                }
            }
        }
        return $this->response->setJSON(['status' => 'error', 'pesan' => 'Format label wajah tidak valid']);
    }
}
