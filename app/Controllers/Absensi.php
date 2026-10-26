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
                    SELECT 
                        u.username AS nisn,
                        COALESCE(s.nama_siswa, g.nama_guru, p.nama_pegawai, 'Unknown') AS nama_siswa,
                        CASE 
                            WHEN u.level_id = 2 THEN 'Guru'
                            WHEN u.level_id = 3 THEN 'Pegawai'
                            ELSE COALESCE(k.nama_kelas, '-')
                        END AS nama_kelas,
                        u.level_id
                    FROM user u
                    LEFT JOIN siswa s ON s.nisn = u.username AND u.level_id = 4
                    LEFT JOIN kelas k ON k.kelas_id = s.kelas_id
                    LEFT JOIN guru g ON g.nip = u.username AND u.level_id = 2
                    LEFT JOIN pegawai p ON p.nip = u.username AND u.level_id = 3
                    WHERE u.level_id IN (2, 3, 4)
                    AND u.user_id NOT IN (
                        SELECT user_id FROM absen WHERE tanggal = ?
                    )
                    AND u.user_id NOT IN (
                        SELECT user_id FROM izin_sakit WHERE tanggal = ? AND status != 'Reject'
                    )
                    ORDER BY 
                        CASE WHEN u.level_id = 4 THEN 1 WHEN u.level_id = 2 THEN 2 ELSE 3 END ASC,
                        nama_kelas ASC,
                        nama_siswa ASC
                ";
                $siswa_belum_absen = $this->db->query($sql_belum_absen, [$tanggal_hari_ini, $tanggal_hari_ini])->getResult();

                $data = [
                    'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                    'status_pengumuman' => $peng ? $peng->status : 'Tidak',
                    'text'              => $peng ? $peng->text : '',

                    // --- UBAH BAGIAN INI ---
                    // 'dataabsen' => $this->db->table('absen')
                    //     ->where('tanggal', $tanggal_hari_ini)
                    //     ->orderBy('COALESCE(jam_pulang, jam_masuk)', 'DESC', false) // <-- Tambahkan parameter false ini!
                    //     ->get()->getResult(),
                    // -----------------------

                    'belum_absen'       => $siswa_belum_absen,
                    'semua_kelas'       => $this->db->table('kelas')->orderBy('nama_kelas', 'ASC')->get()->getResult(),
                    'jadwal'            => $jadwal,

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

    public function show_latest_absen()
    {
        // --- TAMBAHKAN PARAMETER 'false' DI AKHIR orderBy ---
        $data = $this->db->table('absen')
            ->where('tanggal', date('Y-m-d'))
            ->orderBy('COALESCE(jam_pulang, jam_masuk)', 'DESC', false)
            ->get()->getResult();

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

        $hari_ini = $this->getHariIni();
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        if ($dtabsentime) {
            $user = $this->db->table('user')->where('user_id', $id_user)->get()->getRow();

            // --- AMBIL NAMA LENGKAP UNTUK PESAN PENOLAKAN ---
            $nama_user = 'Pengguna';
            if ($user->level_id == 2) {
                $guru = $this->db->table('guru')->where('nip', $user->username)->get()->getRow();
                $nama_user = $guru ? $guru->nama_guru : 'Guru';
            } elseif ($user->level_id == 3) {
                $pegawai = $this->db->table('pegawai')->where('nip', $user->username)->get()->getRow();
                $nama_user = $pegawai ? $pegawai->nama_pegawai : 'Pegawai';
            } elseif ($user->level_id == 4) {
                $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                $nama_user = $siswa ? $siswa->nama_siswa : 'Siswa';
            }

            if (in_array($user->level_id, [2, 3])) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } elseif ($user->level_id == 4) {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            }

            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i:s');
            $now = date('H:i:s');

            $status = ($now > $stamp) ? 'Terlambat' : 'Tepat Waktu';
            $point = ($now > $stamp) ? 3 : 5;
            $tgl = date('Y-m-d');

            $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
            $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
            $tanggal_indo = $hari_array[date('l')] . ', ' . date('d') . ' ' . $bulan_array[date('m')] . ' ' . date('Y');

            // Jika sudah absen masuk hari ini (Proses Pulang)
            if ($cekdataabsenmasuk) {
                if ($cekdataabsenmasuk->jam_pulang != null) {
                    // RETURN KUSTOM DENGAN NAMA (Sebelumnya: return 'no';)
                    return "Maaf {$nama_user}, Anda sudah menyelesaikan absen masuk dan pulang hari ini.";
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
                            // (Variabel $siswa sudah dideklarasikan di blok pengambilan nama di atas)
                            $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
                            $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                            if ($setting->wa_blast == 'Aktif' && isset($siswa) && !empty($setting->template_notif_wa)) {
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
                        // RETURN KUSTOM DENGAN NAMA (Sebelumnya: return 'not allowed pulang';)
                        return "Sabar {$nama_user}, belum waktunya jam pulang!";
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
                    $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
                    $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                    if ($setting->wa_blast == 'Aktif' && isset($siswa) && !empty($setting->template_notif_wa)) {
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

            if ($proses == 'ok_masuk' || $proses == 'ok') {
                $telat = $this->cek_telat($user->user_id);
                $soundKey = ($telat == 'ya') ? 'audio_terlambat' : 'audio_masuk_sukses';
                $audioFile = $this->get_sound_config($soundKey);

                return $this->response->setJSON([
                    'response'     => 'ok',
                    'action'       => 'masuk',
                    'message'      => ($telat == 'ya') ? 'Absen Masuk Berhasil (Terlambat)' : 'Absen Masuk Berhasil (Tepat Waktu)',
                    'type'         => $type,
                    'kode'         => $kode,
                    'nama'         => $nama,
                    'photo'        => $photo,
                    'list_absensi' => $this->show_latest_absen(),
                    'telatkah'     => $telat,
                    'audio'        => base_url('assets/audio/' . $audioFile)
                ]);
            } elseif ($proses == 'ok_pulang') {
                $audioFile = $this->get_sound_config('audio_pulang_sukses');

                return $this->response->setJSON([
                    'response'     => 'ok',
                    'action'       => 'pulang',
                    'message'      => 'Absen Pulang Berhasil',
                    'type'         => $type,
                    'kode'         => $kode,
                    'nama'         => $nama,
                    'photo'        => $photo,
                    'list_absensi' => $this->show_latest_absen(),
                    'telatkah'     => 'tidak',
                    'audio'        => base_url('assets/audio/' . $audioFile)
                ]);
            } elseif ($proses == 'holiday') {
                return $this->response->setJSON(['response' => 'holiday', 'message' => 'Hari Libur']);
            } else {
                // KUNCI: Menangkap kalimat penolakan kustom ("Sabar Budi...", "Maaf Budi...")
                $audioFile = $this->get_sound_config('audio_sudah_absen');

                return $this->response->setJSON([
                    'response'     => 'error',
                    'message'      => $proses,
                    'nama'         => $nama,
                    'list_absensi' => $this->show_latest_absen(),
                    'audio'        => base_url('assets/audio/' . $audioFile)
                ]);
            }
        }

        // Jika QR benar-benar tidak terdaftar di database
        $audioFile = $this->get_sound_config('audio_sudah_absen');
        return $this->response->setJSON([
            'response'     => 'not found',
            'message'      => 'Kode QR Tidak Dikenali',
            'list_absensi' => $this->show_latest_absen(),
            'audio'        => base_url('assets/audio/' . $audioFile)
        ]);
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

    // FUNGSI BARU: Endpoint DataTables Server-Side untuk Riwayat Absen (Pencarian Semua Kolom & Integrasi Izin/Sakit)
    public function get_riwayat_absen_serverside()
    {
        $db = \Config\Database::connect();
        $tanggal_hari_ini = date('Y-m-d');

        $request = \Config\Services::request();

        // 1. Sinkronisasi data Izin/Sakit hari ini ke tabel absen jika belum tercatat
        $izinSakitHariIni = $db->table('izin_sakit')
            ->where('tanggal', $tanggal_hari_ini)
            ->where('status !=', 'Reject')
            ->get()->getResult();

        foreach ($izinSakitHariIni as $iz) {
            $exists = $db->table('absen')
                ->where('user_id', $iz->user_id)
                ->where('tanggal', $tanggal_hari_ini)
                ->countAllResults();

            if ($exists == 0) {
                $db->table('absen')->insert([
                    'user_id'      => $iz->user_id,
                    'tanggal'      => $iz->tanggal,
                    'keterangan'   => $iz->keterangan,
                    'point'        => 1,
                    'point_pulang' => 1,
                ]);
            }
        }

        // Parameter bawaan DataTables (Panjang data -1 jika tanpa paging / mode scrolling)
        $start = (int) ($request->getPost('start') ?? 0);
        $rawLength = $request->getPost('length');
        $length = ($rawLength === null || $rawLength === '') ? -1 : (int) $rawLength;
        $search = trim($request->getPost('search')['value'] ?? '');
        $custom_search = trim($request->getPost('custom_search') ?? '');
        if (!empty($custom_search)) {
            $search = $custom_search;
        }
        $filter_kelas = $request->getPost('filter_kelas');
        $filter_status = $request->getPost('filter_status');

        $builder = $db->table('absen');
        // Ambil data absen, user, nama siswa/guru/pegawai, kelas, serta keterangan & deskripsi dari tabel izin_sakit
        $builder->select('absen.*, user.username, user.level_id, siswa.nama_siswa, guru.nama_guru, pegawai.nama_pegawai, kelas.nama_kelas, izin_sakit.keterangan AS iz_keterangan, izin_sakit.deskripsi AS iz_deskripsi');
        $builder->join('user', 'user.user_id = absen.user_id');
        $builder->join('siswa', 'siswa.nisn = user.username', 'left');
        $builder->join('kelas', 'kelas.kelas_id = siswa.kelas_id', 'left');
        $builder->join('guru', 'guru.nip = user.username', 'left');
        $builder->join('pegawai', 'pegawai.nip = user.username', 'left');
        $builder->join('izin_sakit', 'izin_sakit.user_id = absen.user_id AND izin_sakit.tanggal = absen.tanggal', 'left');

        $builder->where('absen.tanggal', $tanggal_hari_ini);

        // 1. Filtering Kelas / Peran (Guru, Pegawai, atau Kelas Siswa)
        if (!empty($filter_kelas)) {
            if ($filter_kelas == 'Guru') {
                $builder->where('user.level_id', 2);
            } elseif ($filter_kelas == 'Pegawai') {
                $builder->where('user.level_id', 3);
            } else {
                $builder->where('kelas.nama_kelas', $filter_kelas);
            }
        }

        // 2. Filtering Status (Hadir, Izin, Sakit)
        if (!empty($filter_status)) {
            if ($filter_status == 'Izin') {
                $builder->groupStart()
                    ->like('absen.keterangan', 'Izin')
                    ->orLike('izin_sakit.keterangan', 'Izin')
                    ->groupEnd();
            } elseif ($filter_status == 'Sakit') {
                $builder->groupStart()
                    ->like('absen.keterangan', 'Sakit')
                    ->orLike('izin_sakit.keterangan', 'Sakit')
                    ->groupEnd();
            } elseif ($filter_status == 'Hadir') {
                $builder->groupStart()
                    ->where('absen.keterangan', 'Masuk')
                    ->orWhere('absen.jam_masuk IS NOT NULL', null, false)
                    ->groupEnd();
            }
        }

        // 2. Pencarian Fleksibel ke Seluruh Kolom
        if (!empty($search)) {
            $builder->groupStart()
                ->like('user.username', $search)         // NISN / NIP
                ->orLike('siswa.nama_siswa', $search)     // Nama Siswa
                ->orLike('guru.nama_guru', $search)      // Nama Guru
                ->orLike('pegawai.nama_pegawai', $search) // Nama Pegawai
                ->orLike('kelas.nama_kelas', $search)     // Nama Kelas
                ->orLike('absen.jam_masuk', $search)      // Jam Masuk
                ->orLike('absen.jam_pulang', $search)     // Jam Pulang
                ->orLike('absen.status_masuk', $search)   // Status Masuk
                ->orLike('absen.status_pulang', $search)  // Status Pulang
                ->orLike('absen.keterangan', $search)     // Keterangan Absen
                ->orLike('izin_sakit.keterangan', $search)// Keterangan Izin / Sakit
                ->orLike('izin_sakit.deskripsi', $search) // Deskripsi Izin / Sakit
                ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(false);

        // Pengurutan waktu absensi terbaru
        $builder->orderBy('COALESCE(absen.jam_pulang, absen.jam_masuk, "00:00:00")', 'DESC', false);

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $dataabsen = $builder->get()->getResult();

        // Hitung total seluruh riwayat hari ini tanpa filter
        $totalRecords = $db->table('absen')->where('tanggal', $tanggal_hari_ini)->countAllResults();

        $data = [];
        $no = $start + 1;

        foreach ($dataabsen as $absen) {
            $nisn_nip = $absen->username;
            $name = 'Unknown';
            $kelas_or_level = '-';

            // Identifikasi Profil & Kelas/Peran
            if ($absen->level_id == 1) {
                $name = 'Admin Aplikasi';
                $kelas_or_level = 'Admin';
            } elseif ($absen->level_id == 2) {
                $name = $absen->nama_guru ?? '-';
                $kelas_or_level = 'Guru';
            } elseif ($absen->level_id == 3) {
                $name = $absen->nama_pegawai ?? '-';
                $kelas_or_level = 'Pegawai';
            } elseif ($absen->level_id == 4) {
                $name = $absen->nama_siswa ?? '-';
                $kelas_or_level = $absen->nama_kelas ?? '-';
            }

            // Deteksi Keterangan Khusus (Izin / Sakit) dari tabel izin_sakit atau absen
            $is_izin_sakit = false;
            $ket_khusus = '';
            $deskripsi_khusus = $absen->iz_deskripsi ?? '';

            if (!empty($absen->iz_keterangan)) {
                $is_izin_sakit = true;
                $ket_khusus = $absen->iz_keterangan;
            } elseif (!empty($absen->keterangan) && in_array(strtolower($absen->keterangan), ['izin', 'sakit'])) {
                $is_izin_sakit = true;
                $ket_khusus = ucfirst(strtolower($absen->keterangan));
            }

            // Format Kolom Masuk & Pulang
            if ($is_izin_sakit) {
                // Untuk siswa yang memiliki keterangan khusus (izin / sakit), jam di kolom masuk diganti keterangan izin_sakit
                $badge_class = (strtolower($ket_khusus) == 'sakit') ? 'badge bg-warning text-dark' : 'badge bg-info text-white';
                $icon = (strtolower($ket_khusus) == 'sakit') ? 'fas fa-notes-medical' : 'fas fa-envelope-open-text';

                $label_ket = $ket_khusus;
                if (!empty($deskripsi_khusus)) {
                    $label_ket .= ' (' . htmlspecialchars($deskripsi_khusus) . ')';
                }

                $kolom_masuk = '<span class="' . $badge_class . ' py-1 px-2" style="font-size: 11px; font-weight: 500; display: inline-block; white-space: normal;" title="' . htmlspecialchars($label_ket) . '"><i class="' . $icon . ' me-1"></i> ' . $label_ket . '</span>';
                $kolom_pulang = '-';
            } else {
                $sts_m = ($absen->status_masuk == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger" title="Terlambat"></i>' : '<i class="fas fa-check-circle text-success" title="Tepat Waktu"></i>';
                $jam_m = $absen->jam_masuk ? substr($absen->jam_masuk, 0, 8) : '-';
                $kolom_masuk = ($jam_m !== '-') ? $jam_m . ' ' . $sts_m : '-';

                $sts_k = ($absen->status_pulang == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger" title="Terlambat"></i>' : (($absen->status_pulang == 'Tepat Waktu') ? '<i class="fas fa-check-circle text-success" title="Tepat Waktu"></i>' : '');
                $jam_p = $absen->jam_pulang ? substr($absen->jam_pulang, 0, 8) : '-';
                $kolom_pulang = ($jam_p !== '-') ? $jam_p . ' ' . $sts_k : '-';
            }

            $btn_pilih = '<button type="button" class="btn btn-sm btn-primary btn-pilih-riwayat" style="font-size: 12px; padding: 3px 10px; font-weight: 500;" data-nisn="' . $nisn_nip . '" title="Pilih NISN/NIP"><i class="fas fa-hand-pointer"></i> Pilih</button>';

            $row = [];
            $row[] = $no++;
            $row[] = $nisn_nip;
            $row[] = $name;
            $row[] = $kelas_or_level;
            $row[] = $kolom_masuk;
            $row[] = $kolom_pulang;
            $row[] = $btn_pilih;

            $data[] = $row;
        }

        $output = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ];

        return $this->response->setJSON($output);
    }

    public function get_sound_config($config_name)
    {
        $config = $this->db->table('konfigurasi_tambahan')->where('config_name', $config_name)->get()->getRow();
        if ($config && !empty($config->value) && file_exists(FCPATH . 'assets/audio/' . $config->value)) {
            return $config->value;
        }

        $defaults = [
            'audio_masuk_sukses'  => 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav',
            'audio_pulang_sukses' => 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav',
            'audio_sudah_absen'   => 'popup.ogg',
            'audio_terlambat'     => 'audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav',
        ];

        return $defaults[$config_name] ?? 'popup.mp3';
    }
}