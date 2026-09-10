<?php

namespace App\Controllers;

class Absensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
    }

    // public function index()
    // {
    //     $tanggal_hari_ini = date('Y-m-d');
    //     $row = $this->db->table('halaman_absensi')->where('halaman_absensi_id', 1)->get()->getRow();
    //     $cekharilibur = $this->db->table('hari_libur')->where('DATE(tanggal)', $tanggal_hari_ini)->get()->getRow();

    //     // Otomatis tembus jika aplikasi berjalan di mode development
    //     if (($cekharilibur || date('D') == 'Sun') && getenv('CI_ENVIRONMENT') !== 'development') {
    //         return view('halaman_absensi_mt');
    //     } else {
    //         if (!session()->get('un_lock')) {
    //             $data['sett_apps'] = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
    //             return view('lock_screen', $data);
    //         } else {
    //             $peng = $this->db->table('pengumuman')->where('running_text_id', 1)->get()->getRow();

    //             // Ambil jadwal absensi berdasarkan hari ini
    //             $nama_hari = $this->getHariIni();
    //             $jadwal = $this->db->table('waktu_absen')->where('nama_hari', $nama_hari)->get()->getRow();

    //             $data = [
    //                 'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
    //                 'status_pengumuman' => $peng ? $peng->status : 'Tidak',
    //                 'text'              => $peng ? $peng->text : '',
    //                 'dataabsen'         => $this->db->table('absen')->where('tanggal', $tanggal_hari_ini)->orderBy('absen_id', 'DESC')->get()->getResult(),

    //                 // Variabel disesuaikan dengan kebutuhan View (_m untuk Murid)
    //                 'nama_hari'         => $nama_hari,
    //                 'jam_masuk_p_g'     => $jadwal ? $jadwal->jam_masuk_guru : '00:00',
    //                 'jam_keluar_p_g'    => $jadwal ? $jadwal->jam_pulang_guru : '00:00',
    //                 'jam_masuk_m'       => $jadwal ? $jadwal->jam_masuk_siswa : '00:00',
    //                 'jam_keluar_m'      => $jadwal ? $jadwal->jam_pulang_siswa : '00:00',
    //             ];
    //             return view('halaman_absensi', $data);
    //         }
    //     }
    // }
    public function index()
    {
        $tanggal_hari_ini = date('Y-m-d');
        $row = $this->db->table('halaman_absensi')->where('halaman_absensi_id', 1)->get()->getRow();
        $cekharilibur = $this->db->table('hari_libur')->where('DATE(tanggal)', $tanggal_hari_ini)->get()->getRow();

        // Otomatis tembus jika aplikasi berjalan di mode development
        if (($cekharilibur || date('D') == 'Sun') && getenv('CI_ENVIRONMENT') !== 'development') {
            return view('halaman_absensi_mt');
        } else {
            if (!session()->get('un_lock')) {
                $data['sett_apps'] = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
                return view('lock_screen', $data);
            } else {
                $peng = $this->db->table('pengumuman')->where('running_text_id', 1)->get()->getRow();

                // Ambil jadwal absensi berdasarkan hari ini
                $nama_hari = $this->getHariIni();
                $jadwal = $this->db->table('waktu_absen')->where('nama_hari', $nama_hari)->get()->getRow();

                // QUERY BARU: Ambil data siswa yang BELUM absen hari ini
                $sql_belum_absen = "
                    SELECT s.nisn, s.nama_siswa, k.nama_kelas 
                    FROM siswa s
                    JOIN user u ON s.nisn = u.username
                    LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
                    WHERE u.level_id = 4 
                    AND u.user_id NOT IN (
                        SELECT user_id FROM absen WHERE tanggal = ?
                    )
                    ORDER BY k.nama_kelas ASC, s.nama_siswa ASC
                ";
                $siswa_belum_absen = $this->db->query($sql_belum_absen, [$tanggal_hari_ini])->getResult();

                $data = [
                    'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                    'status_pengumuman' => $peng ? $peng->status : 'Tidak',
                    'text'              => $peng ? $peng->text : '',
                    'dataabsen'         => $this->db->table('absen')->where('tanggal', $tanggal_hari_ini)->orderBy('absen_id', 'DESC')->get()->getResult(),
                    'belum_absen'       => $siswa_belum_absen, // Variabel baru untuk dikirim ke view

                    // Variabel disesuaikan dengan kebutuhan View (_m untuk Murid)
                    'nama_hari'         => $nama_hari,
                    'jam_masuk_p_g'     => $jadwal ? $jadwal->jam_masuk_guru : '00:00',
                    'jam_keluar_p_g'    => $jadwal ? $jadwal->jam_pulang_guru : '00:00',
                    'jam_masuk_m'       => $jadwal ? $jadwal->jam_masuk_siswa : '00:00',
                    'jam_keluar_m'      => $jadwal ? $jadwal->jam_pulang_siswa : '00:00',
                ];
                return view('halaman_absensi', $data);
            }
        }
    }

    public function un_lock()
    {
        $password = $this->request->getPost('un_lock');

        if ($password) {
            // Bypass khusus di mode development
            if (getenv('CI_ENVIRONMENT') === 'development') {
                session()->set(['un_lock' => 'Aktif']);
                return redirect()->to('/absensi');
            }

            // Memeriksa password lock screen secara langsung ke tabel halaman_absensi
            $row = $this->db->table('halaman_absensi')->where('halaman_absensi_id', 1)->where('password_lock_screen', sha1($password))->get()->getRow();

            if ($row) {
                session()->set(['un_lock' => $row->is_aktif]);
                return redirect()->to('/absensi');
            } else {
                session()->setFlashdata('error', 'Password Salah');
                return redirect()->to('/absensi');
            }
        }
    }

    // public function show_latest_absen()
    // {
    //     $data = $this->db->table('absen')->where('tanggal', date('Y-m-d'))->orderBy('absen_id', 'DESC')->get()->getResult();
    //     $str = '';

    //     foreach ($data as $absen) {
    //         $user = $this->db->table('user')->where('user_id', $absen->user_id)->get()->getRow();
    //         $name = 'null';
    //         $level = 'null';

    //         if ($user) {
    //             if ($user->level_id == 1) {
    //                 $name = 'Admin Aplikasi';
    //                 $level = 'Admin Aplikasi';
    //             } elseif ($user->level_id == 2) {
    //                 $guru = $this->db->table('guru')->where('nip', $user->username)->get()->getRow();
    //                 $name = $guru ? $guru->nama_guru : 'Unknown';
    //                 $level = 'Guru';
    //             } elseif ($user->level_id == 3) {
    //                 $pegawai = $this->db->table('pegawai')->where('nip', $user->username)->get()->getRow();
    //                 $name = $pegawai ? $pegawai->nama_pegawai : 'Unknown';
    //                 $level = 'Pegawai';
    //             } elseif ($user->level_id == 4) {
    //                 $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
    //                 $name = $siswa ? $siswa->nama_siswa : 'Unknown';
    //                 $level = 'Murid';
    //             }
    //         }

    //         $sts_m = ($absen->status_masuk == 'Terlambat') ? '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>' : '<i class="fas fa-check-circle" style="color: #04c142;"></i>';
    //         $sts_k = ($absen->status_pulang == 'Terlambat') ? '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>' : (($absen->status_pulang == 'Tepat Waktu') ? '<i class="fas fa-check-circle" style="color: #04c142;"></i>' : '');

    //         $str .= '<tr>
    //                     <td>' . $name . '</td>
    //                     <td>' . $level . '</td>
    //                     <td>' . $absen->tanggal . '</td>
    //                     <td>' . $absen->keterangan . '</td>
    //                     <td>' . $absen->jam_masuk . ' <span>' . $sts_m . '</span></td>
    //                     <td>' . $absen->jam_pulang . ' <span>' . $sts_k . '</span></td>
    //                 </tr>';
    //     }
    //     return $str;
    // }

    public function show_latest_absen()
    {
        $data = $this->db->table('absen')->where('tanggal', date('Y-m-d'))->orderBy('absen_id', 'DESC')->get()->getResult();
        $str = '';

        foreach ($data as $absen) {
            $user = $this->db->table('user')->where('user_id', $absen->user_id)->get()->getRow();
            $nisn_nip = '-';
            $name = 'null';
            $level = 'null';

            if ($user) {
                $nisn_nip = $user->username; // Ekstrak NISN/NIP

                if ($user->level_id == 1) {
                    $name = 'Admin Aplikasi';
                    $level = 'Admin Aplikasi';
                } elseif ($user->level_id == 2) {
                    $guru = $this->db->table('guru')->where('nip', $user->username)->get()->getRow();
                    $name = $guru ? $guru->nama_guru : 'Unknown';
                    $level = 'Guru';
                } elseif ($user->level_id == 3) {
                    $pegawai = $this->db->table('pegawai')->where('nip', $user->username)->get()->getRow();
                    $name = $pegawai ? $pegawai->nama_pegawai : 'Unknown';
                    $level = 'Pegawai';
                } elseif ($user->level_id == 4) {
                    $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                    $name = $siswa ? $siswa->nama_siswa : 'Unknown';
                    $level = 'Murid';
                }
            }

            $sts_m = ($absen->status_masuk == 'Terlambat') ? '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>' : '<i class="fas fa-check-circle" style="color: #04c142;"></i>';
            $sts_k = ($absen->status_pulang == 'Terlambat') ? '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>' : (($absen->status_pulang == 'Tepat Waktu') ? '<i class="fas fa-check-circle" style="color: #04c142;"></i>' : '');

            // Sisipkan kolom NISN/NIP di urutan pertama
            $str .= '<tr>
                        <td>' . $nisn_nip . '</td>
                        <td>' . $name . '</td>
                        <td>' . $level . '</td>
                        <td>' . $absen->tanggal . '</td>
                        <td>' . $absen->keterangan . '</td>
                        <td>' . $absen->jam_masuk . ' <span>' . $sts_m . '</span></td>
                        <td>' . $absen->jam_pulang . ' <span>' . $sts_k . '</span></td>
                    </tr>';
        }
        return $str;
    }

    // Tambahkan ini di dalam class Absensi (app/Controllers/Absensi.php) jika belum ada
    private function cek_telat($id_user)
    {
        $hari_ini = $this->getHariIni();
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        // Sama, hapus bagian hardcode bypass libur di sini
        if ($dtabsentime) {
            $user = $this->db->table('user')->where('user_id', $id_user)->get()->getRow();

            if (in_array($user->level_id, [2, 3])) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } elseif ($user->level_id == 4) {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            } else {
                return 'tidak';
            }

            // Gunakan format yang ekuivalen (H:i:s)
            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i:s');
            $now = date('H:i:s');

            if ($now > $stamp) {
                return 'ya';
            } else {
                return 'tidak';
            }
        }
        return 'tidak';
    }

    private function getHariIni()
    {
        // Menggunakan helper env() bawaan CI4 yang dijamin pasti terbaca
        // Atau bisa sementara di-uncomment baris return 'Senin'; di bawah ini jika .env belum disetting
        // return 'Senin'; 

        if (env('CI_ENVIRONMENT') === 'development' || (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'development')) {
            return 'Senin';
        }

        $hari_map = [
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat', // Pastikan tulisan di database benar-benar 'Jumat' (bukan Jum'at)
            'Sat' => 'Sabtu'
        ];
        return $hari_map[date('D')] ?? 'Unknown';
    }

    public function insert_absen_data($id_user, $kode)
    {
        $cekdataabsenmasuk = $this->db->table('absen')->where('user_id', $id_user)->where('tanggal', date('Y-m-d'))->get()->getRow();

        // 1. Ganti hardcode 'Senin' dengan deteksi hari dinamis
        $hari_ini = $this->getHariIni();
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        // 2. Blok // --- HARDCODE EKSTREM SEMENTARA --- SEPENUHNYA DIHAPUS

        if ($dtabsentime) {
            $user = $this->db->table('user')->where('user_id', $id_user)->get()->getRow();

            if (in_array($user->level_id, [2, 3])) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } elseif ($user->level_id == 4) {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            }

            // 3. Sertakan elemen Detik ('H:i:s') dalam komparasi agar PHP tidak bingung
            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i:s'); // Diubah jadi H:i:s
            $now = date('H:i:s');            // Diubah jadi H:i:s

            $status = ($now > $stamp) ? 'Terlambat' : 'Tepat Waktu';
            $point = ($now > $stamp) ? 3 : 5;
            $tgl = date('Y-m-d');

            // Format Tanggal Indonesia
            $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
            $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
            $tanggal_indo = $hari_array[date('l')] . ', ' . date('d') . ' ' . $bulan_array[date('m')] . ' ' . date('Y');

            // Jika sudah absen masuk hari ini (Proses Pulang)
            if ($cekdataabsenmasuk) {
                if ($cekdataabsenmasuk->jam_pulang != null) {
                    return 'no'; // Sudah absen pulang
                } else {
                    $allowedtopulang = 0;
                    if (in_array($user->level_id, [2, 3]) && $now >= $dtabsentime->jam_pulang_guru) {
                        $allowedtopulang = 1;
                    } elseif ($user->level_id == 4 && $now >= $dtabsentime->jam_pulang_siswa) {
                        $allowedtopulang = 1;
                    }

                    if ($allowedtopulang == 1) {
                        $jam_pulang = date('H:i:s');

                        $this->db->table('absen')->where('absen_id', $cekdataabsenmasuk->absen_id)->update([
                            'jam_pulang' => $jam_pulang,
                            'status_pulang' => 'Tepat Waktu',
                            'point_pulang' => 5,
                        ]);

                        if ($user->level_id == 4) {
                            $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                            $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
                            $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                            if ($setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                                // Eksekusi Template WA Pulang
                                $pesan_wa = $setting->template_notif_wa;
                                $pesan_wa = str_replace('[nama_sekolah]', $setting->nama_sekolah, $pesan_wa);
                                $pesan_wa = str_replace('[tanggal]', $tanggal_indo, $pesan_wa);
                                $pesan_wa = str_replace('[nama_siswa]', $siswa->nama_siswa, $pesan_wa);
                                $pesan_wa = str_replace('[nisn]', $siswa->nisn, $pesan_wa);
                                $pesan_wa = str_replace('[kelas]', $kelas ? $kelas->nama_kelas : '-', $pesan_wa);
                                $pesan_wa = str_replace('[tipe_absen]', 'Pulang', $pesan_wa);
                                $pesan_wa = str_replace('[jam_absen]', date('H:i'), $pesan_wa);
                                $pesan_wa = str_replace('[status_absen]', 'Tepat Waktu', $pesan_wa);

                                $this->db->table('tabel_antrean_wa')->insert([
                                    'no_hp' => $siswa->no_hp_wali_siswa,
                                    'pesan' => $pesan_wa,
                                    'status' => 'pending',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                        return 'ok';
                    } else {
                        return 'not allowed pulang';
                    }
                }
            } else {
                // Jika belum absen masuk (Proses Masuk)
                $jam_masuk = date('H:i:s');

                $this->db->table('absen')->insert([
                    'user_id' => $id_user,
                    'tanggal' => $tgl,
                    'keterangan' => 'Masuk',
                    'jam_masuk' => $jam_masuk,
                    'point' => $point,
                    'status_masuk' => $status
                ]);

                if ($user->level_id == 4) {
                    $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                    $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
                    $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                    if ($setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                        // Eksekusi Template WA Masuk
                        $pesan_wa = $setting->template_notif_wa;
                        $pesan_wa = str_replace('[nama_sekolah]', $setting->nama_sekolah, $pesan_wa);
                        $pesan_wa = str_replace('[tanggal]', $tanggal_indo, $pesan_wa);
                        $pesan_wa = str_replace('[nama_siswa]', $siswa->nama_siswa, $pesan_wa);
                        $pesan_wa = str_replace('[nisn]', $siswa->nisn, $pesan_wa);
                        $pesan_wa = str_replace('[kelas]', $kelas ? $kelas->nama_kelas : '-', $pesan_wa);
                        $pesan_wa = str_replace('[tipe_absen]', 'Masuk', $pesan_wa);
                        $pesan_wa = str_replace('[jam_absen]', date('H:i'), $pesan_wa);
                        $pesan_wa = str_replace('[status_absen]', $status, $pesan_wa);

                        $this->db->table('tabel_antrean_wa')->insert([
                            'no_hp' => $siswa->no_hp_wali_siswa,
                            'pesan' => $pesan_wa,
                            'status' => 'pending',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                return 'ok';
            }
        }
        return 'holiday';
    }

    public function get_info_absen()
    {
        $kode = $this->request->getPost('codeny');
        $user = $this->db->table('user')->where('username', $kode)->get()->getRow();

        if ($user) {
            $type = '';
            $nama = '';
            $photo = 'default.png';

            if ($user->level_id == 4) {
                $profil = $this->db->table('siswa')->where('nisn', $kode)->get()->getRow();
                $type = 'siswa';
                $nama = $profil->nama_siswa ?? 'Unknown';
                $photo = $profil->photo ?: 'default.png';
            } elseif ($user->level_id == 2) {
                $profil = $this->db->table('guru')->where('nip', $kode)->get()->getRow();
                $type = 'guru';
                $nama = $profil->nama_guru ?? 'Unknown';
                $photo = $profil->photo ?: 'default.png';
            } elseif ($user->level_id == 3) {
                $profil = $this->db->table('pegawai')->where('nip', $kode)->get()->getRow();
                $type = 'pegawai';
                $nama = $profil->nama_pegawai ?? 'Unknown';
                $photo = $profil->photo ?: 'default.png';
            }

            $proses = $this->insert_absen_data($user->user_id, $kode);

            if ($proses == 'ok') {
                return $this->response->setJSON([
                    'response' => 'ok',
                    'message' => 'found',
                    'type' => $type,
                    'kode' => $kode,
                    'nama' => $nama,
                    'photo' => $photo,
                    'list_absensi' => $this->show_latest_absen(),
                    'telatkah' => $this->cek_telat($user->user_id)
                ]);
            } elseif ($proses == 'no') {
                return $this->response->setJSON(['response' => 'not allowed', 'message' => 'Absen Sudah dilakukan hari ini', 'list_absensi' => $this->show_latest_absen()]);
            } elseif ($proses == 'holiday') {
                return $this->response->setJSON(['response' => 'holiday', 'message' => 'Hari Libur']);
            } elseif ($proses == 'not allowed pulang') {
                return $this->response->setJSON(['response' => 'not allowed pulang', 'message' => 'Absen pulang tidak diperbolehkan', 'list_absensi' => $this->show_latest_absen()]);
            }
        }

        return $this->response->setJSON(['response' => 'not found', 'message' => 'Data Tidak Ditemukan', 'list_absensi' => $this->show_latest_absen()]);
    }

    public function proses_wa_fonnte()
    {
        // Pastikan fitur WA blast memang aktif di tabel pengaturan
        $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
        if (!$setting || $setting->wa_blast != 'Aktif') {
            return $this->response->setJSON(['status' => 'nonaktif']);
        }

        // Ambil 1 antrean paling lama dari tabel notif
        $notif = $this->db->table('notif')->orderBy('id', 'ASC')->limit(1)->get()->getRow();

        if ($notif) {
            // PASTIKAN Anda memiliki kolom token_fonnte di tabel app_setting, 
            // atau ganti $setting->token_fonnte dengan string Token Anda langsung secara hardcode jika belum ada.
            $token_fonnte = $setting->token_fonnte ?? 'oXmwJm6Xoiz3LkgLpRC7';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5, // Batasi timeout agar tidak memberatkan server
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $notif->no_hp,
                    'message' => $notif->pesan,
                    // 'countryCode' => '62', // <-- Matikan baris ini
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $token_fonnte
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            // Hapus notif dari antrean setelah dieksekusi agar tidak dikirim ganda
            $this->db->table('notif')->where('id', $notif->id)->delete();

            return $this->response->setJSON([
                'status' => 'sent',
                'target' => $notif->no_hp,
                'fonnte_response' => json_decode($response)
            ]);
        }

        return $this->response->setJSON(['status' => 'empty']);
    }
}
