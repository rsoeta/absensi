<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Gunakan Query Builder secara konsisten
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');

        // Pastikan tabel user_token tersedia
        $this->db->query("CREATE TABLE IF NOT EXISTS `user_token` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(100) DEFAULT NULL,
            `token` varchar(255) NOT NULL,
            `date_created` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function index()
    {
        // Cek jika sudah login, langsung tendang ke dashboard
        if (session()->get('userid')) {
            if (session()->get('level_id') == 1) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/dashboard_user');
            }
        }
        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow()
        ];
        return view('login', $data);
    }

    public function profile()
    {
        return view('profile');
    }

    public function process()
    {
        $identity = trim($this->request->getPost('username') ?? '');
        $password = (string)($this->request->getPost('password') ?? '');

        if (empty($identity) || empty($password)) {
            session()->setFlashdata('gagal', 'Silakan masukkan Username/Email dan Password.');
            session()->setFlashdata('error', 'Silakan masukkan Username/Email dan Password.');
            return redirect()->to('/auth');
        }

        // 1. Cari user di tabel `user` berdasarkan username, email, atau nama_lengkap
        $user = $this->db->table('user')
            ->groupStart()
                ->where('username', $identity)
                ->orWhere('email', $identity)
                ->orWhere('nama_lengkap', $identity)
            ->groupEnd()
            ->get()
            ->getRow();

        // 2. Jika belum ditemukan, periksa tabel `siswa` (mungkin login menggunakan email atau NISN siswa)
        if (!$user) {
            $siswa = $this->db->table('siswa')
                ->groupStart()
                    ->where('nisn', $identity)
                    ->orWhere('email', $identity)
                ->groupEnd()
                ->get()
                ->getRow();

            if ($siswa) {
                $user = $this->db->table('user')->where('username', $siswa->nisn)->get()->getRow();
            }
        }

        // 3. Jika belum ditemukan, periksa tabel `guru` (NIP)
        if (!$user) {
            $guru = $this->db->table('guru')->where('nip', $identity)->get()->getRow();
            if ($guru) {
                $user = $this->db->table('user')->where('username', $guru->nip)->get()->getRow();
            }
        }

        // 4. Jika belum ditemukan, periksa tabel `pegawai` (NIP)
        if (!$user) {
            $pegawai = $this->db->table('pegawai')->where('nip', $identity)->get()->getRow();
            if ($pegawai) {
                $user = $this->db->table('user')->where('username', $pegawai->nip)->get()->getRow();
            }
        }

        // Jika akun tidak ditemukan
        if (!$user) {
            session()->setFlashdata('gagal', 'Akun "' . esc($identity) . '" tidak ditemukan. Pastikan Username atau Email benar.');
            session()->setFlashdata('error', 'Akun "' . esc($identity) . '" tidak ditemukan. Pastikan Username atau Email benar.');
            return redirect()->to('/auth');
        }

        // Periksa apakah akun aktif
        if (isset($user->is_aktif) && $user->is_aktif === 'Tidak') {
            session()->setFlashdata('gagal', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.');
            session()->setFlashdata('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.');
            return redirect()->to('/auth');
        }

        // Cek kecocokan password (mendukung password biasa atau yang sudah di-trim)
        $inputHash = sha1($password);
        $inputHashTrim = sha1(trim($password));

        if ($user->password !== $inputHash && $user->password !== $inputHashTrim) {
            session()->setFlashdata('gagal', 'Password salah! Periksa kembali huruf besar/kecil password Anda.');
            session()->setFlashdata('error', 'Password salah! Periksa kembali huruf besar/kecil password Anda.');
            return redirect()->to('/auth');
        }

        // Login Berhasil -> Set Session
        $sessionData = [
            'userid'       => $user->user_id,
            'level_id'     => $user->level_id,
            'username'     => $user->username,
            'nama_lengkap' => $user->nama_lengkap ?? '',
            'email'        => $user->email ?? '',
            'logged_in'    => true
        ];
        session()->regenerate();
        session()->set($sessionData);

        if ($user->level_id == 1) {
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/dashboard_user');
        }
    }

    public function lock()
    {
        session()->remove('un_lock');
        return redirect()->to('/absensi');
    }

    public function edit_profil($id)
    {
        $data = [
            'name'    => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'email'   => $this->request->getPost('email'),
        ];

        $this->db->table('user')->where('user_id', $id)->update($data);
        session()->setFlashdata('message', 'Data Berhasil diupdate');
        return redirect()->to('/auth/profile');
    }

    public function edit_password($id)
    {
        $user = $this->db->table('user')->where('user_id', $id)->get()->getRow();

        if ($user && sha1($this->request->getPost('lama')) == $user->password) {
            $data = [
                'password' => sha1($this->request->getPost('password')),
            ];
            $this->db->table('user')->where('user_id', $id)->update($data);

            session()->setFlashdata('message', 'Data Password Berhasil diupdate');
            return redirect()->to('/auth/logout');
        } else {
            session()->setFlashdata('error', 'Password Lama Salah');
            return redirect()->to('/auth/profile');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }

    /**
     * Halaman Form Lupa Password
     */
    public function forgot_password()
    {
        if (session()->get('userid')) {
            if (session()->get('level_id') == 1) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/dashboard_user');
            }
        }

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow()
        ];
        return view('forgot_password', $data);
    }

    /**
     * Memproses Pengajuan Lupa Password
     */
    public function forgot_password_process()
    {
        $identity = trim($this->request->getPost('identity') ?? '');

        if (empty($identity)) {
            session()->setFlashdata('error', 'Silakan masukkan Username atau Email Anda.');
            return redirect()->to('/auth/forgot_password');
        }

        // 1. Cari user di tabel `user`
        $user = $this->db->table('user')
            ->groupStart()
                ->where('username', $identity)
                ->orWhere('email', $identity)
                ->orWhere('nama_lengkap', $identity)
            ->groupEnd()
            ->get()
            ->getRow();

        $siswa = null;
        $guru = null;
        $pegawai = null;

        // 2. Jika belum ditemukan, periksa tabel `siswa` (NISN atau Email siswa)
        if (!$user) {
            $siswa = $this->db->table('siswa')
                ->groupStart()
                    ->where('nisn', $identity)
                    ->orWhere('email', $identity)
                ->groupEnd()
                ->get()
                ->getRow();

            if ($siswa) {
                $user = $this->db->table('user')->where('username', $siswa->nisn)->get()->getRow();
            }
        }

        // 3. Jika belum ditemukan, periksa tabel `guru` (NIP guru)
        if (!$user) {
            $guru = $this->db->table('guru')->where('nip', $identity)->get()->getRow();
            if ($guru) {
                $user = $this->db->table('user')->where('username', $guru->nip)->get()->getRow();
            }
        }

        // 4. Jika belum ditemukan, periksa tabel `pegawai` (NIP pegawai)
        if (!$user) {
            $pegawai = $this->db->table('pegawai')->where('nip', $identity)->get()->getRow();
            if ($pegawai) {
                $user = $this->db->table('user')->where('username', $pegawai->nip)->get()->getRow();
            }
        }

        // Jika tidak ditemukan akun sama sekali
        if (!$user) {
            session()->setFlashdata('error', 'Akun dengan Username atau Email tersebut tidak terdaftar dalam sistem.');
            return redirect()->to('/auth/forgot_password');
        }

        // Lengkapi profil jika user dicari dari tabel user langsung
        if (!$siswa && $user->level_id == 4) {
            $siswa = $this->db->table('siswa')->where('nisn', $user->username)->get()->getRow();
        }
        if (!$guru && in_array($user->level_id, [2, 3])) {
            $guru = $this->db->table('guru')->where('nip', $user->username)->get()->getRow();
        }
        if (!$pegawai && in_array($user->level_id, [2, 3])) {
            $pegawai = $this->db->table('pegawai')->where('nip', $user->username)->get()->getRow();
        }

        // Sinkronisasi email ke tabel user jika di tabel user masih kosong tapi ada di tabel siswa
        if (empty($user->email) && !empty($siswa->email)) {
            $this->db->table('user')->where('user_id', $user->user_id)->update(['email' => $siswa->email]);
            $user->email = $siswa->email;
        }

        // Nama penerima
        $targetName = !empty($user->nama_lengkap) ? $user->nama_lengkap : ($siswa->nama_siswa ?? ($guru->nama_guru ?? ($pegawai->nama_pegawai ?? $user->username)));

        // Email tujuan
        $targetEmail = !empty($user->email) ? $user->email : ($siswa->email ?? null);

        // Nomor WA tujuan
        $targetPhone = null;
        if ($siswa) {
            if (!empty($siswa->no_wa_siswa)) {
                $targetPhone = $siswa->no_wa_siswa;
            } elseif (!empty($siswa->no_hp_wali_siswa)) {
                $targetPhone = $siswa->no_hp_wali_siswa;
            }
        } elseif ($guru && !empty($guru->no_hp)) {
            $targetPhone = $guru->no_hp;
        }

        // Buat token reset aman
        $token = bin2hex(random_bytes(32));
        $dateCreated = time();

        // Hapus token lama untuk user ini
        $this->db->table('user_token')->where('username', $user->username)->delete();

        // Simpan token baru
        $this->db->table('user_token')->insert([
            'username'     => $user->username,
            'token'        => $token,
            'date_created' => $dateCreated
        ]);

        $resetLink = base_url('auth/reset_password?username=' . urlencode($user->username) . '&token=' . urlencode($token));
        $settApps  = $this->db->table('app_setting')->where('id', 1)->get()->getRow();
        $appName   = $settApps->nama_aplikasi ?? 'Absensi Sekolah';

        // Deteksi lingkungan localhost / development
        $isLocalhost = (
            in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) ||
            in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) ||
            strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost:') === 0 ||
            ENVIRONMENT === 'development'
        );

        $sentEmail = false;
        $sentWa    = false;
        $configEmail = config('Email');

        // Cek kesiapan konfigurasi SMTP
        $isSmtpConfigured = (
            !empty($configEmail->SMTPHost) &&
            !empty($configEmail->SMTPUser) &&
            !empty($configEmail->SMTPPass)
        );

        // 1. Kirim Email (jika ada email tujuan)
        if (!empty($targetEmail)) {
            // Pada localhost, hanya kirim jika SMTP sudah disetel (karena mail() lokal Windows tidak bisa tembus ke Gmail)
            if ($isSmtpConfigured || (!$isLocalhost && $configEmail->protocol !== 'smtp')) {
                try {
                    $emailService = \Config\Services::email();

                    if ($isSmtpConfigured) {
                        $emailService->initialize([
                            'protocol'   => 'smtp',
                            'SMTPHost'   => $configEmail->SMTPHost,
                            'SMTPPort'   => $configEmail->SMTPPort ?? 587,
                            'SMTPCrypto' => $configEmail->SMTPCrypto ?? 'tls',
                            'SMTPUser'   => $configEmail->SMTPUser,
                            'SMTPPass'   => $configEmail->SMTPPass,
                            'mailType'   => 'html',
                            'charset'    => 'UTF-8',
                        ]);
                    }

                    $fromEmail = !empty($configEmail->fromEmail) ? $configEmail->fromEmail : (!empty($configEmail->SMTPUser) ? $configEmail->SMTPUser : 'no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
                    $fromName  = !empty($configEmail->fromName) ? $configEmail->fromName : $appName;

                    $emailService->setFrom($fromEmail, $fromName);
                    $emailService->setTo($targetEmail);
                    $emailService->setSubject('Reset Password Akun - ' . $appName);
                    $emailService->setMailType('html');

                    $emailMessage = '
                    <div style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 25px;">
                        <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                            <div style="background-color: #123e87; color: #ffffff; padding: 20px; text-align: center;">
                                <h2 style="margin: 0; font-size: 20px;">' . esc($appName) . '</h2>
                                <p style="margin: 5px 0 0 0; color: #ffd500; font-size: 14px;">Permintaan Atur Ulang Kata Sandi</p>
                            </div>
                            <div style="padding: 30px 25px; color: #333333; line-height: 1.6;">
                                <p>Halo <strong>' . esc($targetName) . '</strong>,</p>
                                <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda (Username: <strong>' . esc($user->username) . '</strong>). Silakan klik tombol di bawah ini untuk membuat kata sandi baru:</p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="' . esc($resetLink) . '" style="display: inline-block; background-color: #123e87; color: #ffffff !important; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">Atur Ulang Kata Sandi</a>
                                </div>
                                <p style="font-size: 13px; color: #666;">Atau salin tautan berikut ke browser Anda:</p>
                                <p style="word-break: break-all; font-size: 12px; color: #123e87;"><a href="' . esc($resetLink) . '">' . esc($resetLink) . '</a></p>
                                <p style="font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px;">
                                    *Tautan ini hanya berlaku selama 24 jam. Abaikan email ini jika Anda tidak merasa melakukan permintaan reset password.
                                </p>
                            </div>
                            <div style="background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee;">
                                &copy; ' . date('Y') . ' ' . esc($appName) . '. All rights reserved.
                            </div>
                        </div>
                    </div>';

                    $emailService->setMessage($emailMessage);

                    if ($emailService->send(false)) {
                        $sentEmail = true;
                    } else {
                        log_message('warning', 'Gagal kirim email reset password: ' . $emailService->printDebugger(['headers']));
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Error sending email reset: ' . $e->getMessage());
                }
            }
        }

        // 2. Kirim WhatsApp Fonnte (jika ada nomor & Fonnte aktif)
        if (!empty($targetPhone) && !empty($settApps->token_fonnte) && ($settApps->wa_blast ?? 'Aktif') === 'Aktif') {
            $cleanPhone = preg_replace('/[^0-9]/', '', $targetPhone);
            if (strpos($cleanPhone, '0') === 0) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            } elseif (strpos($cleanPhone, '8') === 0) {
                $cleanPhone = '62' . $cleanPhone;
            }

            $waMessage = "*RESET PASSWORD - " . $appName . "*\n\n"
                       . "Halo *" . $targetName . "*,\n"
                       . "Kami menerima permintaan atur ulang kata sandi untuk akun *" . $user->username . "*.\n\n"
                       . "Silakan klik link berikut untuk membuat kata sandi baru:\n"
                       . $resetLink . "\n\n"
                       . "⚠️ _Tautan hanya berlaku selama 24 jam._\n"
                       . "_Jika Anda tidak merasa melakukan permintaan ini, abaikan pesan ini._\n\n"
                       . "*Notification sent by " . $appName . "*";

            try {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 15,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => [
                        'target'      => $cleanPhone,
                        'message'     => $waMessage,
                        'countryCode' => '62',
                    ],
                    CURLOPT_HTTPHEADER => [
                        'Authorization: ' . $settApps->token_fonnte
                    ],
                ]);
                $resWa = curl_exec($curl);
                $errWa = curl_error($curl);
                if (!$errWa && $resWa) {
                    $jsonWa = json_decode($resWa, true);
                    if (isset($jsonWa['status']) && $jsonWa['status'] == true) {
                        $sentWa = true;
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Error sending WA reset: ' . $e->getMessage());
            }
        }

        // 3. Feedback Pengguna
        $maskedEmail = $targetEmail ? $this->maskEmail($targetEmail) : null;
        $maskedPhone = $targetPhone ? $this->maskPhone($targetPhone) : null;

        // SELALU sediakan reset_link di sesi jika di lingkungan lokal / testing
        if ($isLocalhost) {
            session()->setFlashdata('reset_link', $resetLink);
        }

        $channels = [];
        if ($sentEmail) $channels[] = "Email (" . $maskedEmail . ")";
        if ($sentWa) $channels[] = "WhatsApp (" . $maskedPhone . ")";

        if (!empty($channels)) {
            $msg = "Link reset password telah dikirim ke " . implode(' & ', $channels) . ". Silakan periksa pesan Anda untuk membuat password baru.";
            session()->setFlashdata('message', $msg);
            return redirect()->to('/auth/forgot_password');
        } else {
            if ($isLocalhost) {
                // Di server lokal tanpa SMTP disetel, beri penjelasan ramah dan tombol langsung
                $infoMsg = "Permintaan reset password untuk akun <strong>" . esc($user->username) . "</strong> (" . esc($targetName) . ") berhasil diproses.";
                if ($targetEmail && !$isSmtpConfigured) {
                    $infoMsg .= "<br><small style='opacity: 0.9;'>Catatan: Email asli belum dikirim ke Gmail karena SMTP server belum disetel di server lokal (localhost). Silakan gunakan tombol di bawah untuk membuat kata sandi baru secara langsung.</small>";
                }
                session()->setFlashdata('message', $infoMsg);
            } else {
                if (empty($targetEmail) && empty($targetPhone)) {
                    $err = "Akun <strong>" . esc($user->username) . "</strong> (" . esc($targetName) . ") ditemukan, tetapi belum memiliki Email atau Nomor WhatsApp terdaftar. Silakan hubungi Administrator untuk mereset kata sandi Anda.";
                } else {
                    $avail = [];
                    if ($targetEmail) $avail[] = "Email (" . $maskedEmail . ")";
                    if ($targetPhone) $avail[] = "WhatsApp (" . $maskedPhone . ")";
                    $err = "Akun ditemukan, namun pengiriman pesan ke " . implode(' / ', $avail) . " sedang tidak dapat dikirim saat ini. Silakan hubungi Administrator.";
                }
                session()->setFlashdata('error', $err);
            }
            return redirect()->to('/auth/forgot_password');
        }
    }

    /**
     * Halaman Reset Form Buat Password Baru
     */
    public function reset_password()
    {
        $username = trim($this->request->getGet('username') ?? '');
        $token    = trim($this->request->getGet('token') ?? '');

        if (empty($username) || empty($token)) {
            session()->setFlashdata('error', 'Tautan reset password tidak valid atau tidak lengkap.');
            return redirect()->to('/auth/forgot_password');
        }

        // Cek token di tabel user_token
        $userToken = $this->db->table('user_token')
            ->where('username', $username)
            ->where('token', $token)
            ->get()
            ->getRow();

        if (!$userToken) {
            session()->setFlashdata('error', 'Tautan reset password tidak valid atau sudah pernah digunakan.');
            return redirect()->to('/auth/forgot_password');
        }

        // Cek kadaluarsa (24 jam = 86400 detik)
        if ((time() - (int)$userToken->date_created) > 86400) {
            $this->db->table('user_token')->where('id', $userToken->id)->delete();
            session()->setFlashdata('error', 'Tautan reset password telah kadaluarsa (berlaku 24 jam). Silakan ajukan reset baru.');
            return redirect()->to('/auth/forgot_password');
        }

        // Cek user
        $user = $this->db->table('user')->where('username', $username)->get()->getRow();
        if (!$user) {
            session()->setFlashdata('error', 'User pemilik token tidak ditemukan.');
            return redirect()->to('/auth/forgot_password');
        }

        $data = [
            'username'  => $username,
            'token'     => $token,
            'user'      => $user,
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow()
        ];

        return view('reset_password', $data);
    }

    /**
     * Simpan Password Baru
     */
    public function change_password()
    {
        $username   = trim($this->request->getPost('username') ?? '');
        $token      = trim($this->request->getPost('token') ?? '');
        $password   = trim((string)$this->request->getPost('password') ?? '');
        $konfirmasi = trim((string)$this->request->getPost('konfirmasi_password') ?? '');

        if (empty($username) || empty($token)) {
            session()->setFlashdata('error', 'Permintaan tidak valid.');
            return redirect()->to('/auth/forgot_password');
        }

        if (empty($password)) {
            session()->setFlashdata('error', 'Password baru tidak boleh kosong.');
            return redirect()->to('/auth/reset_password?username=' . urlencode($username) . '&token=' . urlencode($token));
        }

        if (strlen($password) < 4) {
            session()->setFlashdata('error', 'Password baru minimal harus 4 karakter.');
            return redirect()->to('/auth/reset_password?username=' . urlencode($username) . '&token=' . urlencode($token));
        }

        if ($password !== $konfirmasi) {
            session()->setFlashdata('error', 'Konfirmasi password baru tidak cocok. Silakan ulangi.');
            return redirect()->to('/auth/reset_password?username=' . urlencode($username) . '&token=' . urlencode($token));
        }

        // Verifikasi token ulang di database
        $userToken = $this->db->table('user_token')
            ->where('username', $username)
            ->where('token', $token)
            ->get()
            ->getRow();

        if (!$userToken) {
            session()->setFlashdata('error', 'Token reset sudah tidak valid.');
            return redirect()->to('/auth/forgot_password');
        }

        if ((time() - (int)$userToken->date_created) > 86400) {
            $this->db->table('user_token')->where('id', $userToken->id)->delete();
            session()->setFlashdata('error', 'Token reset telah kadaluarsa.');
            return redirect()->to('/auth/forgot_password');
        }

        // Update password user
        $this->db->table('user')->where('username', $username)->update([
            'password' => sha1($password)
        ]);

        // Hapus token yang sudah terpakai
        $this->db->table('user_token')->where('username', $username)->delete();

        session()->setFlashdata('message', 'Password berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.');
        return redirect()->to('/auth');
    }

    /**
     * Helper Sensor Email
     */
    protected function maskEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        $parts = explode('@', $email);
        $name = $parts[0];
        $len = strlen($name);
        if ($len <= 2) {
            $masked = substr($name, 0, 1) . '*';
        } else {
            $masked = substr($name, 0, 2) . str_repeat('*', max(1, $len - 4)) . substr($name, -2);
        }
        return $masked . '@' . $parts[1];
    }

    /**
     * Helper Sensor Nomor Telepon
     */
    protected function maskPhone($phone)
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        $len = strlen($clean);
        if ($len <= 6) {
            return $phone;
        }
        return substr($clean, 0, 4) . '****' . substr($clean, -4);
    }
}
