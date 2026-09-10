<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $sett_apps->nama_aplikasi ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="<?= base_url('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') ?>" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Optimasi SweetAlert2 khusus untuk layar Mobile */
        .swal2-popup {
            font-size: 0.85rem !important;
            width: 85% !important;
            max-width: 320px !important;
            padding: 1.2em !important;
            border-radius: 15px !important;
        }

        .swal2-title {
            font-size: 1.25rem !important;
            margin-bottom: 0.5em !important;
        }

        .swal2-html-container {
            margin: 0.5em 1em 0 !important;
        }

        .swal2-icon {
            width: 3.5em !important;
            height: 3.5em !important;
            margin: 1em auto .5em !important;
        }

        .swal2-icon .swal2-icon-content {
            font-size: 2.5em !important;
        }

        .swal2-actions {
            margin-top: 1em !important;
        }

        .swal2-styled.swal2-confirm {
            padding: 0.5em 1.5em !important;
            font-size: 0.9rem !important;
        }
    </style>
</head>

<body onload="tampilkanwaktu();setInterval('tampilkanwaktu()', 1000);">
    <div class="app-cover"></div>

    <div id="app" class="app app-header-fixed app-sidebar-fixed app-without-sidebar app-with-top-menu">
        <div id="header" class="app-header">
            <div class="navbar-header">
                <a href="#" class="navbar-brand">
                    <span class="navbar-logo"></span>
                    <span><b>ABSENSI</b></span>&nbsp<span style="color: orange;"> <b>DIGITAL</b></span>&nbsp
                    <span style="color: orange;"><b><?= $sett_apps->nama_sekolah ?></b> </span>
                </a>
            </div>
            <div class="navbar-nav">
                <div class="navbar-item dropdown">
                    <a href="<?= base_url('auth/lock') ?>" class="navbar-link dropdown-toggle icon">
                        <i class="fas fa-lock"></i> Lock Halaman
                    </a>
                </div>
            </div>
        </div>

        <div id="content" class="app-content">
            <!-- <?php if ($status_pengumuman == 'Aktif') : ?>
                <div class="alert alert-info" role="alert">
                    <marquee>
                        <h2><?= $text ?></h2>
                    </marquee>
                </div> -->
        <?php endif; ?>

        <!-- BARIS UTAMA -->
        <div class="row mb-4">

            <!-- ============================================== -->
            <!-- SISI KIRI (WAKTU, SCANNER, & TABEL BELUM ABSEN)-->
            <!-- ============================================== -->
            <div class="col-xl-7">

                <!-- Sub-Baris Atas (Waktu & Scanner) -->
                <div class="row">
                    <!-- PANEL 1: WAKTU & INFO -->
                    <div class="col-md-5 mb-3">
                        <div class="panel panel-inverse h-100">
                            <div class="panel-heading">
                                <h1 class="panel-title"><span><b>WAKTU</b></span>&nbsp<span style="color: orange;">SERVER</span></h1>
                            </div>
                            <div class="panel-body text-center">
                                <?php
                                $hari_indo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                                $bulan_indo = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
                                echo $hari_indo[date('l')] . ", " . date('d') . " " . $bulan_indo[date('F')] . " " . date('Y');
                                ?>
                                <h1><span id="clock"></span></h1>

                                <form id="form_input_nisnnip" class="mt-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="hasil_scanan" placeholder="KETIK NISN LALU ENTER" autocomplete="off" autofocus>
                                    </div>
                                </form>

                                <hr>
                                <div class="info-overview-absen">
                                    <!-- Area render foto & nama live -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANEL 2: PEMINDAI QR -->
                    <div class="col-md-7 mb-3">
                        <div class="panel panel-inverse h-100">
                            <div class="panel-heading">
                                <h1 class="panel-title"><span><b>SCAN UNTUK</b></span><span style="color: orange;"> MASUK / PULANG</span></h1>
                            </div>
                            <div class="panel-body text-center">
                                <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto; border-radius:10px; overflow:hidden;"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Akhir Sub-Baris Atas -->

                <!-- Sub-Baris Bawah (Tabel Belum Absen) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-user-times"></i> Data Siswa Belum Absen Hari Ini</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Ekstrak daftar kelas unik dari data siswa yang belum absen
                                $kelas_unik = [];
                                if (!empty($belum_absen)) {
                                    foreach ($belum_absen as $s) {
                                        $nama_k = $s->nama_kelas ?? '-';
                                        if (!in_array($nama_k, $kelas_unik)) {
                                            $kelas_unik[] = $nama_k;
                                        }
                                    }
                                    sort($kelas_unik);
                                }
                                ?>

                                <!-- Area Kolom Pencarian & Filter Kelas -->
                                <div class="row mb-3">
                                    <div class="col-md-7 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                            <input type="text" id="cari_siswa_belum_absen" class="form-control" placeholder="Cari Nama atau NISN...">
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
                                            <select id="filter_kelas_belum_absen" class="form-control">
                                                <option value="">-- Semua Kelas --</option>
                                                <?php foreach ($kelas_unik as $kls) : ?>
                                                    <option value="<?= $kls ?>"><?= $kls ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div style="overflow-x: auto; max-height: 350px; overflow-y: scroll;">
                                    <table class="table table-bordered table-hover table-striped text-center align-middle" id="tabel_belum_absen">
                                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">NISN</th>
                                                <th>Nama Siswa</th>
                                                <th width="20%">Kelas</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($belum_absen)) : ?>
                                                <?php $no = 1;
                                                foreach ($belum_absen as $siswa) : ?>
                                                    <!-- Tambahkan class baris-siswa untuk target filter js -->
                                                    <tr class="baris-siswa">
                                                        <td><?= $no++ ?></td>
                                                        <td class="nisn-siswa"><?= $siswa->nisn ?></td>
                                                        <td class="text-start nama-siswa"><?= $siswa->nama_siswa ?></td>
                                                        <td class="kelas-siswa"><?= $siswa->nama_kelas ?? '-' ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary btn-pilih-nisn" data-nisn="<?= $siswa->nisn ?>">
                                                                <i class="fas fa-hand-pointer"></i> Pilih
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-success fw-bold">
                                                        Alhamdulillah, semua siswa sudah absen hari ini!
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Akhir Sub-Baris Bawah -->

            </div> <!-- AKHIR SISI KIRI -->


            <!-- ============================================== -->
            <!-- SISI KANAN (RIWAYAT DATA ABSEN HARI INI)     -->
            <!-- ============================================== -->
            <div class="col-xl-5">
                <div class="panel panel-inverse h-100">
                    <div class="panel-heading">
                        <h1 class="panel-title"><span><b>DATA ABSEN</b></span><span style="color: orange;"> HARI INI</span></h1>
                    </div>
                    <div class="panel-body">
                        <div class="note note-primary">
                            <div class="note-icon"><i class="fa fa-info"></i></div>
                            <div class="note-content">
                                <h4><b>Selamat Datang!</b></h4>
                                <h5>Hari <?= $nama_hari ?>, </h5>
                                <table style="font-size: 13px;">
                                    <tr>
                                        <td><b>Guru/Pegawai</b></td>
                                        <td>:</td>
                                        <td>Jam Masuk : <b><?= $jam_masuk_p_g ?></b> | Jam Pulang : <b><?= $jam_keluar_p_g ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><b>Murid</b></td>
                                        <td>:</td>
                                        <td>Jam Masuk : <b><?= $jam_masuk_m ?></b> | Jam Pulang : <b><?= $jam_keluar_m ?></b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover text-white align-middle">
                                <thead>
                                    <tr>
                                        <th>NISN/NIP</th> <!-- Kolom Baru -->
                                        <th>Nama</th>
                                        <th>Level</th>
                                        <th>Waktu</th>
                                        <th>Ket</th>
                                        <th>Masuk</th>
                                        <th>Pulang</th>
                                    </tr>
                                </thead>
                                <tbody id="list_data_absen">
                                    <?php
                                    $db = \Config\Database::connect();
                                    $str = '';
                                    foreach ($dataabsen as $absen) {
                                        $getdatauser = $db->table('user')->where('user_id', $absen->user_id)->get()->getRow();
                                        if (!$getdatauser) continue;

                                        $nisn_nip = $getdatauser->username; // Ambil NISN/NIP
                                        $name = 'Unknown';
                                        $level = 'Unknown';
                                        if ($getdatauser->level_id == 1) {
                                            $name = 'Admin Aplikasi';
                                            $level = 'Admin';
                                        }
                                        if ($getdatauser->level_id == 2) {
                                            $guru = $db->table('guru')->where('nip', $getdatauser->username)->get()->getRow();
                                            $name = $guru ? $guru->nama_guru : '-';
                                            $level = 'Guru';
                                        }
                                        if ($getdatauser->level_id == 3) {
                                            $pegawai = $db->table('pegawai')->where('nip', $getdatauser->username)->get()->getRow();
                                            $name = $pegawai ? $pegawai->nama_pegawai : '-';
                                            $level = 'Pegawai';
                                        }
                                        if ($getdatauser->level_id == 4) {
                                            $siswa = $db->table('siswa')->where('nisn', $getdatauser->username)->get()->getRow();
                                            $name = $siswa ? $siswa->nama_siswa : '-';
                                            $level = 'Murid';
                                        }

                                        $sts_m = ($absen->status_masuk == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger"></i>' : '<i class="fas fa-check-circle text-success"></i>';
                                        $sts_k = ($absen->status_pulang == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger"></i>' : (($absen->status_pulang == 'Tepat Waktu') ? '<i class="fas fa-check-circle text-success"></i>' : '');

                                        // Tambahkan {nisn_nip} di kolom paling depan
                                        $str .= "<tr><td>{$nisn_nip}</td><td>{$name}</td><td>{$level}</td><td>{$absen->tanggal}</td><td>{$absen->keterangan}</td><td>{$absen->jam_masuk} {$sts_m}</td><td>{$absen->jam_pulang} {$sts_k}</td></tr>";
                                    }
                                    echo $str;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- AKHIR SISI KANAN -->

        </div> <!-- Akhir Baris Utama -->
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/transparent.min.js') ?>"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/2.0.2/timeago.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        var baseURL = '<?= base_url() ?>';
        var html5QrcodeScanner;

        // Fungsi Jam Server
        function tampilkanwaktu() {
            var waktu = new Date();
            var sh = waktu.getHours().toString().padStart(2, '0');
            var sm = waktu.getMinutes().toString().padStart(2, '0');
            var ss = waktu.getSeconds().toString().padStart(2, '0');
            document.getElementById("clock").innerHTML = sh + ":" + sm + ":" + ss;
        }

        // Format waktu untuk timeago
        function iso8601(date) {
            return date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate() + "T" + date.getUTCHours() + ":" + date.getUTCMinutes() + ":" + date.getUTCSeconds() + "Z";
        }

        // FUNGSI INTI: Proses Absensi (Cepat & Tanpa Jeda Layar)
        function processAbsensi(kode) {
            // Jeda kamera sejenak agar tidak double-scan QR yang sama
            if (html5QrcodeScanner) html5QrcodeScanner.pause();

            $.ajax({
                url: baseURL + '/absensi/get_info_absen',
                type: 'POST',
                dataType: 'json',
                data: {
                    codeny: kode
                },
                success: function(dt) {
                    if (dt.response === 'ok') {
                        // 1. Langsung Render Foto & Nama di Sidebar
                        $('.info-overview-absen').html(`
                    <img src="${baseURL}/assets/img/${dt.type}/${dt.photo}" style="width: 130px;height: 130px;border-radius: 10%;display: block;margin: 0 auto;object-fit: cover;" border="2">
                    <input style="margin-top: 10px;text-align: center; font-weight: bold;" type="text" class="form-control" value="${dt.nama}" readonly>
                    <p style="font-size: 11px; color: gray; text-align: center; margin-top: 5px;">Diproses: <time class="need_to_be_rendered load_time strong">sekarang</time></p>
                `);

                        $('#list_data_absen').html(dt.list_absensi);
                        document.querySelector('.load_time').setAttribute('datetime', iso8601(new Date()));
                        timeago().render(document.querySelectorAll('.need_to_be_rendered'), 'id');

                        // 2. Gunakan Toast yang tidak memblokir layar (Nyaman untuk Mobile/Desktop)
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: dt.nama,
                            text: dt.message,
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });

                        // 3. Mainkan Suara sebagai indikator utama keberhasilan
                        let audioSrc = (dt.telatkah === 'ya') ? 'audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav' : 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav';
                        new Audio(baseURL + '/assets/audio/' + audioSrc).play();

                    } else if (dt.response === 'holiday') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Hari Libur',
                            text: dt.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Gagal',
                            text: dt.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        if (dt.list_absensi) $('#list_data_absen').html(dt.list_absensi);
                    }

                    // 4. Reset Input & Kamera SUPER CEPAT (Hanya jeda 0.8 detik)
                    $('#hasil_scanan').val('').focus();
                    setTimeout(() => {
                        if (html5QrcodeScanner) html5QrcodeScanner.resume();
                    }, 800);
                },
                error: function() {
                    $('#hasil_scanan').val('').focus();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Koneksi Terputus',
                        text: 'Gagal menghubungi server.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        if (html5QrcodeScanner) html5QrcodeScanner.resume();
                    }, 1000);
                }
            });
        }

        // --- BACKGROUND PROCESS WA BLAST FONNTE ---
        function jalankanAntreanWA() {
            $.ajax({
                url: baseURL + '/absensi/proses_wa_fonnte',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'sent') {
                        console.log('Mencoba kirim WA ke: ' + res.target);
                        console.log('Jawaban Server Fonnte: ', res.fonnte_response);

                        if (res.fonnte_response && res.fonnte_response.status === false) {
                            console.error('ALASAN GAGAL: ' + res.fonnte_response.reason);
                        }
                    }
                },
                complete: function() {
                    setTimeout(jalankanAntreanWA, 3000);
                }
            });
        }

        $(document).ready(function() {
            // Inisialisasi Scanner HTML5-QRCode
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    rememberLastUsedCamera: true
                }, false
            );

            // Render Kamera & Tautkan ke fungsi processAbsensi
            html5QrcodeScanner.render(function(decodedText) {
                processAbsensi(decodedText);
            });

            // Trigger saat menekan tombol Enter pada kolom input
            $('#hasil_scanan').on('keypress', function(e) {
                // 13 adalah kode spesifik untuk tombol Enter di keyboard
                if (e.which === 13) {
                    e.preventDefault(); // Mencegah form reload bawaan browser
                    var kdnya = $(this).val().trim();

                    if (kdnya !== '') {
                        processAbsensi(kdnya);
                        $(this).val(''); // Otomatis mengosongkan input setelah di-enter
                    }
                }
            });


            // jalankanAntreanWA();

            // Trigger ketika tombol Pilih di tabel Belum Absen diklik
            $('.btn-pilih-nisn').on('click', function() {
                var nisn = $(this).data('nisn'); // Ambil NISN dari tombol

                // Isi inputan dan langsung eksekusi absen
                $('#hasil_scanan').val(nisn);
                processAbsensi(nisn);

                // Hilangkan baris siswa ini dari tabel secara halus (visual saja)
                $(this).closest('tr').fadeOut('fast');

                // RESET INPUT PENCARIAN & FILTER KELAS
                $('#cari_siswa_belum_absen').val('');
                $('#filter_kelas_belum_absen').val('');

                // Picu ulang event untuk mengembalikan tabel ke kondisi awal (tampil semua)
                $('#cari_siswa_belum_absen').trigger('keyup');
            });

            // Filter Pencarian & Kelas secara Real-Time (Client-Side)
            $('#cari_siswa_belum_absen, #filter_kelas_belum_absen').on('keyup change', function() {
                var keyword = $('#cari_siswa_belum_absen').val().toLowerCase();
                var kelasFilter = $('#filter_kelas_belum_absen').val().toLowerCase();

                $('#tabel_belum_absen tbody tr.baris-siswa').each(function() {
                    var nisn = $(this).find('.nisn-siswa').text().toLowerCase();
                    var nama = $(this).find('.nama-siswa').text().toLowerCase();
                    var kelas = $(this).find('.kelas-siswa').text().toLowerCase();

                    // Cek apakah data cocok dengan teks pencarian DAN filter kelas
                    var matchKeyword = (nisn.indexOf(keyword) > -1 || nama.indexOf(keyword) > -1);
                    var matchKelas = (kelasFilter === "" || kelas === kelasFilter);

                    if (matchKeyword && matchKelas) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

        });
    </script>
</body>

</html>