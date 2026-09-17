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
        $level_id = session()->get('level_id');
        $cekhari = date('l');
        $today = date('Y-m-d');

        // Cek data absen hari ini
        $row_absen = $this->db->table('absen')
            ->where('user_id', $user_id)
            ->where('DATE(tanggal)', $today)
            ->get()
            ->getRow();

        $cek_absen = $row_absen ? 1 : 0;

        // Cek hari libur
        $cek_libur = $this->db->table('hari_libur')
            ->where('DATE(tanggal)', $today)
            ->countAllResults();

        $remark = "";
        $show = "Tidak";
        $masuk = '-';
        $pulang = '-';

        if ($cekhari == 'Sunday') {
            $remark = "Hari Minggu";
        } elseif ($cek_libur > 0) {
            $remark = "Hari Libur";
        } else {
            // Ambil waktu absen berdasarkan hari ini (menggunakan fungsi helper hari_ini() Anda)
            $hari_ini = function_exists('hari_ini') ? hari_ini() : date('l');
            $jam_waktu = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();
            $time = date('H:i:s');

            if ($cek_absen > 0) {
                // Sudah absen masuk, cek apakah sudah absen pulang
                if ($row_absen->jam_pulang != null) {
                    $remark = "Terimakasih Sudah Absen Hari ini";
                    $masuk = $row_absen->jam_masuk;
                    $pulang = $row_absen->jam_pulang;
                } else {
                    // Tentukan batas pulang berdasarkan level user
                    $batas_pulang = ($level_id == 2 || $level_id == 3)
                        ? ($jam_waktu->jam_pulang_guru ?? '15:00:00')
                        : ($jam_waktu->jam_pulang_siswa ?? '13:00:00');

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
                // Belum absen sama sekali, cek batas jam masuk
                if ($jam_waktu) {
                    $batas_masuk = ($level_id == 2 || $level_id == 3)
                        ? ($jam_waktu->jam_masuk_guru ?? '07:00:00')
                        : ($jam_waktu->jam_masuk_siswa ?? '07:30:00');

                    if ($batas_masuk > $time) {
                        $remark = "Absen Masuk Buka : " . $batas_masuk;
                    } else {
                        $remark = "Absen Masuk";
                        $show = "Ya";
                    }
                } else {
                    $remark = "Jam Absen Belum Diatur";
                }
            }
        }

        $pengumuman = $this->db->table('pengumuman')->where('pengumuman_id', 1)->get()->getRow();
        $geo_setting = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();

        $data = [
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'text'              => $pengumuman ? $pengumuman->text : '',
            'status_pengumuman' => $pengumuman ? $pengumuman->status : 'Tidak Aktif',
            'remark'            => $remark,
            'show'              => $show,
            'masuk'             => $masuk,
            'pulang'            => $pulang,
            'latitude'          => $geo_setting ? $geo_setting->latitude : '',
            'longitude'         => $geo_setting ? $geo_setting->longitude : '',
            'radius'            => $geo_setting ? $geo_setting->radius : '',
            'is_geo'            => $geo_setting ? $geo_setting->is_aktif : 'Tidak',
            'is_photo'          => $geo_setting ? $geo_setting->is_photo : 'Tidak',
        ];

        return view('dashboard_user', $data);
    }

    public function getLapanganRadius()
    {
        $data = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();
        return $this->response->setJSON([
            'lat'              => $data ? $data->latitude : '',
            'lng'              => $data ? $data->longitude : '',
            'radius_diizinkan' => $data ? $data->radius : 0
        ]);
    }

    public function kartu($id)
    {
        $level_id = session()->get('level_id');

        // Dekripsi ID yang dikirim dari URL, tangani juga jika formatnya plain/aman
        $real_id = function_exists('decrypt_url') ? decrypt_url($id) : $id;

        if ($level_id == '2') {
            $row = $this->db->table('guru')->where('guru_id', $real_id)->get()->getRow();
            $data = [
                'guru_id'        => $row->guru_id ?? '',
                'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nip'            => $row->nip ?? '',
                'qr_code'        => $row->qr_code ?? '',
                'nama_guru'      => $row->nama_guru ?? '',
                'jk_kelamin'     => $row->jk_kelamin ?? '',
                'status_guru_id' => $row->status_guru_id ?? '',
                'alamat'         => $row->alamat ?? '',
                'no_hp'          => $row->no_hp ?? '',
                'tempat_lahir'   => $row->tempat_lahir ?? '',
                'tanggal_lahir'  => $row->tanggal_lahir ?? '',
                'photo'          => $row->photo ?? '',
            ];
            return view('guru/cetak', $data);
        } elseif ($level_id == '3') {
            $row = $this->db->table('pegawai')->where('pegawai_id', $real_id)->get()->getRow();
            $data = [
                'pegawai_id'        => $row->pegawai_id ?? '',
                'nip'               => $row->nip ?? '',
                'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nama_pegawai'      => $row->nama_pegawai ?? '',
                'jk_kelamin'        => $row->jk_kelamin ?? '',
                'status_pegawai_id' => $row->status_pegawai_id ?? '',
                'alamat'            => $row->alamat ?? '',
                'no_hp'             => $row->no_hp ?? '',
                'tempat_lahir'      => $row->tempat_lahir ?? '',
                'tanggal_lahir'     => $row->tanggal_lahir ?? '',
                'photo'             => $row->photo ?? '',
                'qr_code'           => $row->qr_code ?? '',
            ];
            return view('pegawai/cetak', $data);
        } elseif ($level_id == '4') {
            $row = $this->db->table('siswa')->where('siswa_id', $real_id)->get()->getRow();
            $data = [
                'siswa_id'         => $row->siswa_id ?? '',
                'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nisn'             => $row->nisn ?? '',
                'nama_siswa'       => $row->nama_siswa ?? '',
                'jk_kelamin'       => $row->jk_kelamin ?? '',
                'kelas_id'         => $row->kelas_id ?? '',
                'alamat'           => $row->alamat ?? '',
                'tempat_lahir'     => $row->tempat_lahir ?? '',
                'tanggal_lahir'    => $row->tanggal_lahir ?? '',
                'photo'            => $row->photo ?? '',
                'nama_wali_siswa'  => $row->nama_wali_siswa ?? '',
                'no_hp_wali_siswa' => $row->no_hp_wali_siswa ?? '',
                'qr_code'          => $row->qr_code ?? '',
            ];
            return view('siswa/cetak', $data);
        } else {
            return redirect()->to(site_url('dashboard_user'))->with('error', 'Akses ditolak.');
        }
    }

    public function absen()
    {
        $user_id = session()->get('userid');

        // Ambil data absen dengan JOIN ke tabel user agar level_id terbawa
        $absen = $this->db->table('absen')
            ->select('absen.*, user.level_id')
            ->join('user', 'user.user_id = absen.user_id', 'left')
            ->where('absen.user_id', $user_id)
            ->orderBy('absen.tanggal', 'DESC')
            ->get()
            ->getResult();

        $data = [
            'absen_data' => $absen,
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('web_user/absen/absen_list', $data);
    }

    public function izin_sakit()
    {
        $user_id = session()->get('userid');

        // Lakukan JOIN ke tabel user agar level_id terbaca dengan aman di view
        $izin_sakit = $this->db->table('izin_sakit')
            ->select('izin_sakit.*, user.level_id')
            ->join('user', 'user.user_id = izin_sakit.user_id', 'left')
            ->where('izin_sakit.user_id', $user_id)
            ->orderBy('izin_sakit.tanggal', 'DESC')
            ->get()
            ->getResult();

        $data = [
            'izin_sakit_data' => $izin_sakit,
            'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('web_user/izin_sakit/izin_sakit_list', $data);
    }

    // =========================================================================
    // FUNGSI INTI ABSENSI (Mendukung Siswa, Guru, dan Pegawai)
    // =========================================================================
    public function insert_absen_data($id_user)
    {
        $dataURL = $this->request->getPost('photo');
        $today = date('Y-m-d');

        // Cek apakah sudah absen masuk hari ini
        $cekdataabsenmasuk = $this->db->table('absen')
            ->where('user_id', $id_user)
            ->where('DATE(tanggal)', $today)
            ->get()
            ->getRow();

        $hari_ini = function_exists('hari_ini') ? hari_ini() : date('l');
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        if ($dtabsentime) {
            $datausersipengabsen = $this->db->table('user')->where('user_id', $id_user)->get()->getRow();
            if (!$datausersipengabsen) return 'holiday';

            if ($datausersipengabsen->level_id == 2 || $datausersipengabsen->level_id == 3) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } else {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            }

            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i');
            $now = date('H:i');

            $status = ($now > $stamp) ? 'Terlambat' : 'Tepat Waktu';
            $point = ($now > $stamp) ? 3 : 5;

            if ($cekdataabsenmasuk) {
                // Sudah ada absen masuk, berarti ini proses ABSEN PULANG
                if ($cekdataabsenmasuk->jam_pulang != null) {
                    return 'no'; // Sudah absen pulang sebelumnya
                } else {
                    $allowedtopulang = 0;
                    $datapulang = $dtabsentime;

                    if ($datausersipengabsen->level_id == 2 || $datausersipengabsen->level_id == 3) {
                        if ($now >= ($datapulang->jam_pulang_guru ?? '15:00:00')) {
                            $allowedtopulang = 1;
                        }
                    } else {
                        if ($now >= ($datapulang->jam_pulang_siswa ?? '13:00:00')) {
                            $allowedtopulang = 1;
                        }
                    }

                    if ($allowedtopulang == 1) {
                        $jam_pulang = date('H:i:s');
                        $filename = null;

                        if ($dataURL && $dataURL != 'a') {
                            $dataURL = str_replace('data:image/png;base64,', '', $dataURL);
                            $dataURL = str_replace(' ', '+', $dataURL);
                            $image = base64_decode($dataURL);
                            $filename = $id_user . '-' . date('YmdHis') . '-pulang.png';
                            file_put_contents(FCPATH . 'assets/bukti_absen/' . $filename, $image);
                        }

                        $datatoupdate = [
                            'jam_pulang'    => $jam_pulang,
                            'status_pulang' => 'Tepat Waktu',
                            'point_pulang'  => 5,
                            'selfie_keluar' => $filename,
                        ];

                        $this->db->table('absen')->where('absen_id', $cekdataabsenmasuk->absen_id)->update($datatoupdate);
                        return 'ok';
                    } else {
                        return 'not allowed pulang';
                    }
                }
            } else {
                // Belum ada absen masuk, ini proses ABSEN MASUK
                $jam_masuk = date('H:i:s');
                $filename = null;

                if ($dataURL && $dataURL != 'a') {
                    $dataURL = str_replace('data:image/png;base64,', '', $dataURL);
                    $dataURL = str_replace(' ', '+', $dataURL);
                    $image = base64_decode($dataURL);
                    $filename = $id_user . '-' . date('YmdHis') . '-masuk.png';
                    file_put_contents(FCPATH . 'assets/bukti_absen/' . $filename, $image);
                }

                $dataabsentoinsert = [
                    'user_id'        => $id_user,
                    'tanggal'        => $today,
                    'keterangan'     => 'Masuk',
                    'jam_masuk'      => $jam_masuk,
                    'jam_pulang'     => NULL,
                    'status_masuk'   => $status,
                    'point'          => $point,
                    'is_geolocation' => 'Ya',
                    'selfie_masuk'   => $filename,
                ];

                $this->db->table('absen')->insert($dataabsentoinsert);
                return 'ok';
            }
        } else {
            return 'holiday';
        }
    }

    public function get_info_absen($codeny = null)
    {
        $post = $codeny ?? $this->request->getPost('codeny');
        if (!$post) {
            $user_id = session()->get('userid');
            $user_row = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();
            $post = $user_row ? $user_row->username : '';
        }

        // Cek identitas berdasarkan NISN (Siswa) atau NIP (Guru/Pegawai)
        $ceksiswa = $this->db->table('siswa')->where('nisn', $post)->get()->getRow();
        $cekguru = $this->db->table('guru')->where('nip', $post)->get()->getRow();
        $cekpegawai = $this->db->table('pegawai')->where('nip', $post)->get()->getRow();
        $getdatauser = $this->db->table('user')->where('username', $post)->get()->getRow();

        if (!$getdatauser) {
            return $this->response->setJSON(['response' => 'not found', 'message' => 'Data User Tidak Ditemukan']);
        }

        $ceklgi = $this->insert_absen_data($getdatauser->user_id);

        if ($ceklgi == 'ok') {
            $nama = '';
            $type = '';
            if ($ceksiswa) {
                $nama = $ceksiswa->nama_siswa;
                $type = 'siswa';
            } elseif ($cekguru) {
                $nama = $cekguru->nama_guru;
                $type = 'guru';
            } elseif ($cekpegawai) {
                $nama = $cekpegawai->nama_pegawai;
                $type = 'pegawai';
            }

            return $this->response->setJSON([
                'response' => 'ok',
                'message'  => 'found',
                'type'     => $type,
                'kode'     => $post,
                'nama'     => $nama,
                'telatkah' => $this->cek_telat($getdatauser->user_id)
            ]);
        } elseif ($ceklgi == 'no') {
            return $this->response->setJSON(['response' => 'not allowed', 'message' => 'Absen sudah dilakukan hari ini.']);
        } elseif ($ceklgi == 'holiday') {
            return $this->response->setJSON(['response' => 'holiday', 'message' => 'Hari Libur / Waktu absen tidak ditemukan.']);
        } elseif ($ceklgi == 'not allowed pulang') {
            return $this->response->setJSON(['response' => 'not allowed pulang', 'message' => 'Belum waktunya melakukan absen pulang.']);
        }

        return $this->response->setJSON(['response' => 'error', 'message' => 'Gagal memproses absensi.']);
    }

    public function cek_telat($id_user)
    {
        $hari_ini = function_exists('hari_ini') ? hari_ini() : date('l');
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        if ($dtabsentime) {
            $datausersipengabsen = $this->db->table('user')->where('user_id', $id_user)->get()->getRow();
            if (!$datausersipengabsen) return 'ga';

            if ($datausersipengabsen->level_id == 2 || $datausersipengabsen->level_id == 3) {
                $late_waktu_absen = $dtabsentime->jam_masuk_guru;
                $minutes_to_add = $dtabsentime->absen_terlambat_guru;
            } else {
                $late_waktu_absen = $dtabsentime->jam_masuk_siswa;
                $minutes_to_add = $dtabsentime->absen_terlambat_siswa;
            }

            $time = new \DateTime($late_waktu_absen);
            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
            $stamp = $time->format('H:i');
            $now = date('H:i');

            if ($now > $stamp) {
                return 'ya';
            } else {
                return 'ga';
            }
        }
        return 'ga';
    }

    // API Grafik User (Chart.js)
    public function get_chart_user()
    {
        $user_id = session()->get('userid');
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        $hadir = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->whereIn('status_masuk', ['Tepat Waktu', 'Terlambat'])->countAllResults();
        $sakit = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->where('keterangan', 'Sakit')->countAllResults();
        $izin = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_ini)->where('YEAR(tanggal)', $tahun_ini)->where('keterangan', 'Izin')->countAllResults();

        $hari_efektif = 22;
        $alpha = $hari_efektif - ($hadir + $sakit + $izin);
        $alpha = $alpha < 0 ? 0 : $alpha;

        $label_bulan = [];
        $data_hadir_sem = [];
        $data_absen_sem = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan_target = date('m', strtotime("-$i months"));
            $tahun_target = date('Y', strtotime("-$i months"));
            $label_bulan[] = date('M Y', strtotime("-$i months"));

            $h_sem = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_target)->where('YEAR(tanggal)', $tahun_target)->whereIn('status_masuk', ['Tepat Waktu', 'Terlambat'])->countAllResults();
            $s_sem = $this->db->table('absen')->where('user_id', $user_id)->where('MONTH(tanggal)', $bulan_target)->where('YEAR(tanggal)', $tahun_target)->whereIn('keterangan', ['Sakit', 'Izin'])->countAllResults();

            $data_hadir_sem[] = $h_sem;
            $data_absen_sem[] = $s_sem;
        }

        return $this->response->setJSON([
            'rapor_bulan' => [$hadir, $sakit, $izin, $alpha],
            'trend_semester' => [
                'labels'      => $label_bulan,
                'hadir'       => $data_hadir_sem,
                'tidak_hadir' => $data_absen_sem,
            ]
        ]);
    }

    // =========================================================================
    // MODUL EDIT PROFILE (Mendukung Siswa, Guru, dan Pegawai)
    // =========================================================================
    public function edit_profile()
    {
        $user_id = session()->get('userid');
        $row = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();

        if ($row) {
            $data = [
                'user_id'   => $row->user_id,
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'username'  => $row->username,
                'password'  => $row->password,
                'level_id'  => $row->level_id,
            ];
            return view('web_user/edit_profile', $data);
        } else {
            session()->setFlashdata('error', 'Record Not Found');
            return redirect()->to('/dashboard_user');
        }
    }

    public function update_profile()
    {
        $user_id = $this->request->getPost('user_id');
        $level_id = session()->get('level_id');

        // Tentukan folder, tabel, dan primary key berdasarkan level user
        if ($level_id == '2') {
            $upload_path = './assets/img/guru/';
            $table_name = 'guru';
            $pk_field = 'guru_id';
            $id_column = 'nip';
        } else if ($level_id == '3') {
            $upload_path = './assets/img/pegawai/';
            $table_name = 'pegawai';
            $pk_field = 'pegawai_id';
            $id_column = 'nip';
        } else {
            $upload_path = './assets/img/siswa/';
            $table_name = 'siswa';
            $pk_field = 'siswa_id';
            $id_column = 'nisn';
        }

        // Ambil data user untuk mendapatkan username (NIP / NISN)
        $user_row = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();
        $username = $user_row ? $user_row->username : '';

        // Ambil data profil spesifik sesuai kolom identitas yang benar (nip/nisn)
        $profile_row = $this->db->table($table_name)->where($id_column, $username)->get()->getRow();

        // Handle Upload Foto Baru jika ada
        $filePhoto = $this->request->getFile('photo');
        $photoName = $this->request->getPost('photo_lama');

        // Siapkan array data penampung untuk di-update
        $update_data = [];

        if ($filePhoto && $filePhoto->isValid() && !$filePhoto->hasMoved()) {
            $photoName = 'File-' . date('ymd') . '-' . substr(sha1(rand()), 0, 10) . '.' . $filePhoto->getExtension();
            $filePhoto->move($upload_path, $photoName);

            // Hapus foto lama jika ada
            if ($profile_row && !empty($profile_row->photo) && $profile_row->photo != 'default.png') {
                $old_file = $upload_path . $profile_row->photo;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            // --- KUNCI UTAMA: Reset Face Descriptor karena ada foto baru ---
            $update_data['face_descriptor'] = null;
        }

        // Masukkan nama foto ke dalam array update (bisa foto baru, bisa foto lama)
        $update_data['photo'] = $photoName;

        // Update foto (dan reset AI jika ada foto baru) ke tabel entitas terkait
        if ($profile_row) {
            $this->db->table($table_name)->where($pk_field, $profile_row->$pk_field)->update($update_data);
        }

        // Update Password jika diisi
        $new_password = $this->request->getPost('password');
        if (!empty($new_password)) {
            $this->db->table('user')->where('user_id', $user_id)->update([
                'password' => sha1($new_password)
            ]);
        }

        session()->setFlashdata('message', 'Update Profile Berhasil');
        return redirect()->to('/dashboard_user/edit_profile');
    }

    // =========================================================================
    // MODUL PENGAJUAN IZIN / SAKIT
    // =========================================================================

    public function create_izin_sakit()
    {
        $user_id = session()->get('userid');
        $data = [
            'button'        => 'Create',
            'action'        => site_url('dashboard_user/create_action_izin_sakit'),
            'izin_sakit_id' => '',
            'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'user_id'       => $user_id,
            'photo'         => '',
            'tanggal'       => date('Y-m-d'),
            'keterangan'    => '',
            'status'        => 'Waiting',
            'deskripsi'     => '',
        ];
        return view('web_user/izin_sakit/izin_sakit_form', $data);
    }

    public function create_action_izin_sakit()
    {
        $user_id = $this->request->getPost('user_id');
        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $deskripsi = $this->request->getPost('deskripsi');

        $day = date('l', strtotime($tanggal));

        // Cek apakah hari Minggu
        if ($day == 'Sunday') {
            session()->setFlashdata('error', 'Tidak bisa pengajuan izin/sakit pada hari Minggu.');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        }

        // Cek apakah tanggal tersebut merupakan hari libur di database
        $cek_libur = $this->db->table('hari_libur')->where('DATE(tanggal)', $tanggal)->countAllResults();
        if ($cek_libur > 0) {
            session()->setFlashdata('error', 'Tidak bisa pengajuan izin/sakit pada hari libur.');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        }

        // Cek duplikasi pengajuan pada tanggal dan user yang sama
        $cek_duplikasi = $this->db->table('izin_sakit')
            ->where('tanggal', $tanggal)
            ->where('user_id', $user_id)
            ->countAllResults();

        if ($cek_duplikasi > 0) {
            session()->setFlashdata('error', 'Gagal: Sudah ada data pengajuan izin/sakit pada tanggal tersebut.');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        }

        // Handle Upload File Surat Keterangan
        $filePhoto = $this->request->getFile('photo');
        $fileName = '';

        if ($filePhoto && $filePhoto->isValid() && !$filePhoto->hasMoved()) {
            $fileName = 'File-' . date('ymd') . '-' . substr(sha1(rand()), 0, 10) . '.' . $filePhoto->getExtension();
            $targetPath = FCPATH . 'assets/assets/img/izin';

            // Pastikan folder tujuan ada
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0777, true);
            }

            $filePhoto->move($targetPath, $fileName);
        }

        // Simpan ke database menggunakan Query Builder CI4
        $dataToInsert = [
            'user_id'    => $user_id,
            'photo'      => $fileName,
            'keterangan' => $keterangan,
            'tanggal'    => $tanggal,
            'status'     => 'Waiting',
            'deskripsi'  => $deskripsi,
        ];

        $this->db->table('izin_sakit')->insert($dataToInsert);

        session()->setFlashdata('message', 'Pengajuan Izin/Sakit Berhasil Dikirim');
        return redirect()->to(site_url('dashboard_user/izin_sakit'));
    }

    public function update_izin_sakit($id)
    {
        $real_id = decrypt_url($id);
        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'button'        => 'Update',
                'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'        => site_url('dashboard_user/update_action_izin_sakit'),
                'izin_sakit_id' => $row->izin_sakit_id,
                'user_id'       => $row->user_id,
                'photo'         => $row->photo,
                'tanggal'       => $row->tanggal,
                'keterangan'    => $row->keterangan,
                'status'        => $row->status,
                'deskripsi'     => $row->deskripsi,
            ];
            return view('web_user/izin_sakit/izin_sakit_form', $data);
        } else {
            session()->setFlashdata('error', 'Data Izin/Sakit Tidak Ditemukan');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        }
    }

    public function update_action_izin_sakit()
    {
        $izin_sakit_id = $this->request->getPost('izin_sakit_id');
        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $deskripsi = $this->request->getPost('deskripsi');
        $photo_lama = $this->request->getPost('photo_lama');

        $filePhoto = $this->request->getFile('photo');
        $fileName = $photo_lama;

        if ($filePhoto && $filePhoto->isValid() && !$filePhoto->hasMoved()) {
            $fileName = 'File-' . date('ymd') . '-' . substr(sha1(rand()), 0, 10) . '.' . $filePhoto->getExtension();
            $targetPath = FCPATH . 'assets/assets/img/izin';
            $filePhoto->move($targetPath, $fileName);

            // Hapus file lama jika ada
            if (!empty($photo_lama) && file_exists($targetPath . '/' . $photo_lama)) {
                unlink($targetPath . '/' . $photo_lama);
            }
        }

        $dataToUpdate = [
            'photo'      => $fileName,
            'keterangan' => $keterangan,
            'tanggal'    => $tanggal,
            'deskripsi'  => $deskripsi,
        ];

        $this->db->table('izin_sakit')->where('izin_sakit_id', $izin_sakit_id)->update($dataToUpdate);

        session()->setFlashdata('message', 'Update Pengajuan Berhasil');
        return redirect()->to(site_url('dashboard_user/izin_sakit'));
    }

    public function delete_izin_sakit($id)
    {
        $real_id = decrypt_url($id);
        $row = $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->get()->getRow();

        if ($row) {
            if (!empty($row->photo)) {
                $target_file = FCPATH . 'assets/assets/img/izin/' . $row->photo;
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }

            $this->db->table('izin_sakit')->where('izin_sakit_id', $real_id)->delete();
            session()->setFlashdata('message', 'Hapus Data Berhasil');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        } else {
            session()->setFlashdata('error', 'Data Tidak Ditemukan');
            return redirect()->to(site_url('dashboard_user/izin_sakit'));
        }
    }

    public function download_izin_sakit($gambar)
    {
        $path = FCPATH . 'assets/assets/img/izin/' . $gambar;
        if (file_exists($path)) {
            return $this->response->download($path, null);
        }
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function download_kartu_img($id)
    {
        $level_id = session()->get('level_id');
        $real_id = decrypt_url($id);

        $sett_apps = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

        if ($level_id == '2') {
            $row = $this->db->table('guru')->where('guru_id', $real_id)->get()->getRow();
            $data = [
                'guru'           => $row, // Tambahkan alias objek guru
                'guru_id'        => $row->guru_id ?? '',
                'sett_apps'      => $sett_apps,
                'nip'            => $row->nip ?? '',
                'qr_code'        => $row->qr_code ?? '',
                'nama_guru'      => $row->nama_guru ?? '',
                'jk_kelamin'     => $row->jk_kelamin ?? '',
                'status_guru_id' => $row->status_guru_id ?? '',
                'alamat'         => $row->alamat ?? '',
                'no_hp'          => $row->no_hp ?? '',
                'tempat_lahir'   => $row->tempat_lahir ?? '',
                'tanggal_lahir'  => $row->tanggal_lahir ?? '',
                'photo'          => $row->photo ?? '',
            ];
            return view('web_user/cetak_img', $data); // Atau arahkan ke view cetak gambar yang sesuai
        } elseif ($level_id == '3') {
            $row = $this->db->table('pegawai')->where('pegawai_id', $real_id)->get()->getRow();
            $data = [
                'pegawai'           => $row, // Tambahkan alias objek pegawai
                'pegawai_id'        => $row->pegawai_id ?? '',
                'nip'               => $row->nip ?? '',
                'sett_apps'         => $sett_apps,
                'nama_pegawai'      => $row->nama_pegawai ?? '',
                'jk_kelamin'        => $row->jk_kelamin ?? '',
                'status_pegawai_id' => $row->status_pegawai_id ?? '',
                'alamat'            => $row->alamat ?? '',
                'no_hp'             => $row->no_hp ?? '',
                'tempat_lahir'      => $row->tempat_lahir ?? '',
                'tanggal_lahir'     => $row->tanggal_lahir ?? '',
                'photo'             => $row->photo ?? '',
                'qr_code'           => $row->qr_code ?? '',
            ];
            return view('web_user/cetak_img', $data);
        } elseif ($level_id == '4') {
            $row = $this->db->table('siswa')->where('siswa_id', $real_id)->get()->getRow();
            $data = [
                'siswa'            => $row, // Menyediakan variabel $siswa yang dicari oleh view
                'siswa_id'         => $row->siswa_id ?? '',
                'sett_apps'        => $sett_apps,
                'nisn'             => $row->nisn ?? '',
                'nama_siswa'       => $row->nama_siswa ?? '',
                'jk_kelamin'       => $row->jk_kelamin ?? '',
                'kelas_id'         => $row->kelas_id ?? '',
                'alamat'           => $row->alamat ?? '',
                'tempat_lahir'     => $row->tempat_lahir ?? '',
                'tanggal_lahir'    => $row->tanggal_lahir ?? '',
                'photo'            => $row->photo ?? '',
                'nama_wali_siswa'  => $row->nama_wali_siswa ?? '',
                'no_hp_wali_siswa' => $row->no_hp_wali_siswa ?? '',
                'qr_code'          => $row->qr_code ?? '',
            ];
            return view('web_user/cetak_img', $data);
        } else {
            return redirect()->to(site_url('dashboard_user'))->with('error', 'Akses ditolak.');
        }
    }

    // ==========================================================
    // MENU ABSEN MANDIRI (FOTO & LOKASI)
    // ==========================================================
    public function absen_mandiri()
    {
        $user_id = session()->get('userid');
        if (!$user_id) {
            return redirect()->to(site_url('auth'));
        }

        // 1. Cek Manual Toggle dari Admin (Tabel absen_geolocation)
        $geo_config = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();
        $is_manual_disabled = ($geo_config && $geo_config->is_aktif == 'Tidak') ? true : false;

        // 2. Cek Hari Libur (Berdasarkan tabel waktu_absen)
        $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hari_ini = $hari_array[date('l')];
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        $is_holiday = (!$dtabsentime) ? true : false;

        $data = [
            'sett_apps'          => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'is_manual_disabled' => $is_manual_disabled,
            'is_holiday'         => $is_holiday,
            'hari_ini'           => $hari_ini
        ];

        return view('web_user/absen_mandiri', $data);
    }

    public function proses_absen_mandiri()
    {
        $user_id    = session()->get('userid');
        $latitude   = $this->request->getPost('latitude');
        $longitude  = $this->request->getPost('longitude');
        $image_data = $this->request->getPost('image_data');
        $gps_time   = $this->request->getPost('gps_time');

        if (!$user_id) {
            return redirect()->to(site_url('auth'));
        }

        if (empty($latitude) || empty($longitude)) {
            session()->setFlashdata('error', 'Gagal mendapatkan titik koordinat lokasi Anda.');
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        }

        // ==========================================================
        // LAPIS 1 & 2 & 3: VALIDASI KEAMANAN (TIMEOUT, IP, RADIUS)
        // ==========================================================
        $server_time = time();
        $selisih_waktu = $server_time - (int)$gps_time;

        if (empty($gps_time) || $selisih_waktu > 60) {
            session()->setFlashdata('error', 'Waktu sesi absen habis (' . $selisih_waktu . ' detik). Indikasi manipulasi lokasi. Silakan refresh halaman.');
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        }

        /* 
        $client_ip = $this->request->getIPAddress();
        $allowed_ips = ['203.175.102.225', '36.81.22.33'];
        if (!in_array($client_ip, $allowed_ips)) {
            session()->setFlashdata('error', 'Akses Ditolak! Anda harus terhubung dengan jaringan Wi-Fi Instansi. (IP Anda saat ini: ' . $client_ip . ')');
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        }
        */

        $geo_config = $this->db->table('absen_geolocation')->where('id', 1)->get()->getRow();
        if ($geo_config && $geo_config->is_aktif == 'Ya') {
            $earthRadius = 6371000;
            $lat1 = deg2rad((float)$geo_config->latitude);
            $lon1 = deg2rad((float)$geo_config->longitude);
            $lat2 = deg2rad((float)$latitude);
            $lon2 = deg2rad((float)$longitude);
            $radius_izin = (float)$geo_config->radius;

            $latDelta = $lat2 - $lat1;
            $lonDelta = $lon2 - $lon1;

            $a = sin($latDelta / 2) * sin($latDelta / 2) + cos($lat1) * cos($lat2) * sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            if ($distance > $radius_izin) {
                $jarak_bulat = round($distance, 2);
                session()->setFlashdata('error', "Anda berada di luar area absen! (Jarak Anda: {$jarak_bulat} meter dari pusat).");
                return redirect()->to(site_url('dashboard_user/absen_mandiri'));
            }
        }

        // ==========================================================
        // PROSES UPLOAD FOTO WEBCAM
        // ==========================================================
        $photoName = null;
        if (!empty($image_data)) {
            $image_parts = explode(";base64,", $image_data);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $photoName = $user_id . '_' . date('Ymd_His') . '.jpg';
                $pathFolder = FCPATH . 'assets/img/absen/';

                if (!is_dir($pathFolder)) {
                    mkdir($pathFolder, 0777, true);
                }
                file_put_contents($pathFolder . $photoName, $image_base64);
            }
        }

        // ==========================================================
        // SINKRONISASI LOGIKA ABSENSI (KETERLAMBATAN & WA BLAST)
        // ==========================================================
        $user = $this->db->table('user')->where('user_id', $user_id)->get()->getRow();
        $hari_array = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hari_ini = $hari_array[date('l')];
        $dtabsentime = $this->db->table('waktu_absen')->where('nama_hari', $hari_ini)->get()->getRow();

        if (!$dtabsentime) {
            session()->setFlashdata('error', 'Tidak ada jadwal absen untuk hari ini (Libur).');
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        }

        // Penentuan toleransi keterlambatan sesuai level user
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
        $tgl = date('Y-m-d');

        $status = ($now > $stamp) ? 'Terlambat' : 'Tepat Waktu';
        $point = ($now > $stamp) ? 3 : 5;

        $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        $tanggal_indo = $hari_ini . ', ' . date('d') . ' ' . $bulan_array[date('m')] . ' ' . date('Y');

        $cek_absen = $this->db->table('absen')->where('user_id', $user_id)->where('tanggal', $tgl)->get()->getRow();
        $setting = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

        if (!$cek_absen) {
            // PROSES ABSEN MASUK
            $this->db->table('absen')->insert([
                'user_id'        => $user_id,
                'tanggal'        => $tgl,
                'keterangan'     => 'Masuk',
                'jam_masuk'      => $now,
                'point'          => $point,
                'status_masuk'   => $status,
                'is_geolocation' => 'Ya',
                'lat_masuk'      => $latitude,
                'long_masuk'     => $longitude,
                'photo_masuk'    => $photoName
            ]);

            // WA Blast Masuk Siswa
            if ($user->level_id == 4) {
                $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                if ($setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                    $pesan_wa = $setting->template_notif_wa;
                    $pesan_wa = str_replace(
                        ['[nama_sekolah]', '[tanggal]', '[nama_siswa]', '[nisn]', '[kelas]', '[tipe_absen]', '[jam_absen]', '[status_absen]'],
                        [$setting->nama_sekolah, $tanggal_indo, $siswa->nama_siswa, $siswa->nisn, $kelas ? $kelas->nama_kelas : '-', 'Masuk', date('H:i'), $status],
                        $pesan_wa
                    );

                    $this->db->table('tabel_antrean_wa')->insert([
                        'no_hp'      => $siswa->no_hp_wali_siswa,
                        'pesan'      => $pesan_wa,
                        'status'     => 'pending',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            session()->setFlashdata('message', "Absen Masuk Berhasil! Status: {$status}.");
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        } else {
            // PROSES ABSEN PULANG
            if (!empty($cek_absen->jam_pulang) && $cek_absen->jam_pulang != '00:00:00') {
                session()->setFlashdata('error', 'Anda sudah menyelesaikan absensi masuk dan pulang untuk hari ini.');
                return redirect()->to(site_url('dashboard_user/absen_mandiri'));
            }

            $allowedtopulang = 0;
            if (in_array($user->level_id, [2, 3]) && $now >= $dtabsentime->jam_pulang_guru) {
                $allowedtopulang = 1;
            } elseif ($user->level_id == 4 && $now >= $dtabsentime->jam_pulang_siswa) {
                $allowedtopulang = 1;
            }

            if ($allowedtopulang == 1) {
                $this->db->table('absen')->where('absen_id', $cek_absen->absen_id)->update([
                    'jam_pulang'    => $now,
                    'status_pulang' => 'Tepat Waktu',
                    'point_pulang'  => 5,
                    'lat_pulang'    => $latitude,
                    'long_pulang'   => $longitude,
                    'photo_pulang'  => $photoName
                ]);

                // WA Blast Pulang Siswa
                if ($user->level_id == 4) {
                    $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
                    $kelas = $this->db->table('kelas')->where('kelas_id', $siswa->kelas_id)->get()->getRow();

                    if ($setting->wa_blast == 'Aktif' && $siswa && !empty($setting->template_notif_wa)) {
                        $pesan_wa = $setting->template_notif_wa;
                        $pesan_wa = str_replace(
                            ['[nama_sekolah]', '[tanggal]', '[nama_siswa]', '[nisn]', '[kelas]', '[tipe_absen]', '[jam_absen]', '[status_absen]'],
                            [$setting->nama_sekolah, $tanggal_indo, $siswa->nama_siswa, $siswa->nisn, $kelas ? $kelas->nama_kelas : '-', 'Pulang', date('H:i'), 'Tepat Waktu'],
                            $pesan_wa
                        );

                        $this->db->table('tabel_antrean_wa')->insert([
                            'no_hp'      => $siswa->no_hp_wali_siswa,
                            'pesan'      => $pesan_wa,
                            'status'     => 'pending',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                session()->setFlashdata('message', "Absen Pulang Berhasil pada pukul {$now}.");
            } else {
                session()->setFlashdata('error', 'Belum waktunya jam pulang!');
            }
            return redirect()->to(site_url('dashboard_user/absen_mandiri'));
        }
    }
}
