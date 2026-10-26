<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $sett_apps->nama_aplikasi ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <!-- Sisipkan kode favicon dinamis di sini -->
    <?php if (isset($sett_apps) && !empty($sett_apps->logo_sekolah)) : ?>
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
    <?php else : ?>
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/default.png') ?>">
    <?php endif; ?>

    <!-- CUSTOM THEME OVERRIDES (EKSTERNAL) -->
    <?php if (isset($sett_apps) && $sett_apps->tema_aplikasi == 'muhammadiyah') : ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/tema_muhammadiyah.css') ?>">
    <?php endif; ?>

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="<?= base_url('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') ?>" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CUSTOM THEME OVERRIDES (EKSTERNAL) -->
    <?php if (isset($sett_apps) && $sett_apps->tema_aplikasi == 'muhammadiyah') : ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/tema_muhammadiyah.css') ?>">
    <?php endif; ?>

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

        /* Memaksa kamera QR agar tidak efek cermin (tulisan tidak terbalik) */
        #reader video {
            transform: none !important;
        }

        /* 1. Kunci posisi awal konten tepat di bawah header (asumsi tinggi header 50px - 60px) */
        #content.app-content {
            padding-top: 55px !important;
            margin-top: 0 !important;
        }

        /* 2. TARIK PAKSA baris panel ke atas */
        #content.app-content>.row {
            margin-top: -70px !important;
            /* JURUS UTAMA: Naikkan angka minus ini (misal -50px atau -60px) jika masih kurang mepet! */
            position: relative;
            z-index: 5;
        }

        /* 3. Sembunyikan elemen siluman bawaan template */
        .theme-panel,
        #page-loader,
        .page-loader,
        .pace {
            display: none !important;
        }

        /* 4. Maksimalkan ukuran ruang Kamera */
        .video-wrapper {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            height: 520px;
            /* Area kamera ekstra luas */
            width: 100%;
        }

        .text-white {
            color: #fff !important;
        }

        /* Styling Pagination & Kontrol DataTables Riwayat Absen agar Selaras dan Jelas */
        #table_riwayat_absen_wrapper .dataTables_paginate .pagination .page-item .page-link {
            color: #333 !important;
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
            padding: 0.35rem 0.7rem !important;
            font-size: 0.85rem !important;
            margin: 0 2px;
            border-radius: 4px;
        }

        #table_riwayat_absen_wrapper .dataTables_paginate .pagination .page-item.active .page-link {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        #table_riwayat_absen_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link {
            color: #999 !important;
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
        }

        #table_riwayat_absen_wrapper .dataTables_info {
            color: #333 !important;
            font-size: 0.85rem !important;
            font-weight: 500;
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

                                <hr class="my-2">
                                <!-- Informasi Jadwal E-Absensi Berdasarkan Hari Berjalan -->
                                <div class="p-2 rounded bg-light border text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-dark text-white"><i class="fas fa-calendar-alt"></i> Hari: <?= $nama_hari ?></span>
                                        <a href="<?= base_url('waktu_absen') ?>" class="badge bg-warning text-dark text-decoration-none" title="Kelola Waktu Absen">
                                            <i class="fas fa-clock"></i> E-Absensi
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered text-center align-middle mb-0 bg-white" style="font-size: 11px;">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Role</th>
                                                    <th>Masuk</th>
                                                    <th>Telat</th>
                                                    <th>Pulang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold text-start">Siswa</td>
                                                    <td><?= !empty($jadwal->jam_masuk_siswa) ? substr($jadwal->jam_masuk_siswa, 0, 5) : '-' ?></td>
                                                    <td><?= isset($jadwal->absen_terlambat_siswa) ? '+' . $jadwal->absen_terlambat_siswa . 'm' : '-' ?></td>
                                                    <td><?= !empty($jadwal->jam_pulang_siswa) ? substr($jadwal->jam_pulang_siswa, 0, 5) : '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-start">Guru/Pegawai</td>
                                                    <td><?= !empty($jadwal->jam_masuk_guru) ? substr($jadwal->jam_masuk_guru, 0, 5) : '-' ?></td>
                                                    <td><?= isset($jadwal->absen_terlambat_guru) ? '+' . $jadwal->absen_terlambat_guru . 'm' : '-' ?></td>
                                                    <td><?= !empty($jadwal->jam_pulang_guru) ? substr($jadwal->jam_pulang_guru, 0, 5) : '-' ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                <div style="width: 100%; max-width: 500px; margin: 0 auto; position: relative; border-radius:10px; overflow:hidden;">
                                    <div id="reader" style="width: 100%;"></div>

                                    <!-- ELEMEN SPINNER PENUTUP (White Background) -->
                                    <div id="scanner-spinner" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.95); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
                                        <!-- Ubah warna spinner menjadi primary (biru) agar kontras dengan putih -->
                                        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem; border-width: 0.35em; margin-bottom: 15px;"></div>

                                        <!-- Ubah warna teks menjadi gelap (#333) -->
                                        <h4 style="color: #333; font-weight: bold; letter-spacing: 1px;">Sistem Memproses...</h4>
                                        <small style="color: #666; font-weight: 500;">Mohon Tunggu Sebentar</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Akhir Sub-Baris Atas -->

                <!-- Sub-Baris Bawah (Tabel Belum Absen) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-user-times text-white"></i> Data Belum Absen Hari Ini (Siswa & GTK)</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Ekstrak daftar kelas dari database atau data siswa yang belum absen
                                $daftar_kelas = [];
                                if (isset($semua_kelas) && !empty($semua_kelas)) {
                                    foreach ($semua_kelas as $k) {
                                        if (!empty($k->nama_kelas) && !in_array($k->nama_kelas, $daftar_kelas)) {
                                            $daftar_kelas[] = $k->nama_kelas;
                                        }
                                    }
                                } elseif (!empty($belum_absen)) {
                                    foreach ($belum_absen as $s) {
                                        $nama_k = $s->nama_kelas ?? '-';
                                        if (!in_array($nama_k, $daftar_kelas)) {
                                            $daftar_kelas[] = $nama_k;
                                        }
                                    }
                                }
                                sort($daftar_kelas);
                                ?>

                                <!-- Area Kolom Pencarian & Filter Kelas -->
                                <div class="row mb-3">
                                    <div class="col-md-7 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                            <input type="text" id="cari_siswa_belum_absen" class="form-control" placeholder="Cari semua kolom (NISN, Nama, Kelas)...">
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
                                            <select id="filter_kelas_belum_absen" class="form-control">
                                                <option value="">-- Semua Kelas / GTK --</option>
                                                <optgroup label="Peran / GTK">
                                                    <option value="Guru">Guru</option>
                                                    <option value="Pegawai">Pegawai</option>
                                                </optgroup>
                                                <optgroup label="Kelas Siswa">
                                                    <?php foreach ($daftar_kelas as $kls) : ?>
                                                        <?php if ($kls != 'Guru' && $kls != 'Pegawai') : ?>
                                                            <option value="<?= $kls ?>"><?= $kls ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div style="overflow-x: auto; max-height: 350px; overflow-y: scroll;">
                                    <table class="table table-bordered table-hover table-striped text-center align-middle" id="tabel_belum_absen">
                                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="18%">NISN/NIP</th>
                                                <th>Nama</th>
                                                <th width="18%">Kelas / Peran</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($belum_absen)) : ?>
                                                <?php $no = 1;
                                                foreach ($belum_absen as $siswa) : ?>
                                                    <!-- Tambahkan class baris-siswa untuk target filter js -->
                                                    <tr class="baris-siswa" id="row-belum-absen-<?= $siswa->nisn ?>" data-nisn="<?= $siswa->nisn ?>" data-nama="<?= $siswa->nama_siswa ?>">
                                                        <td><?= $no++ ?></td>
                                                        <td class="nisn-siswa"><?= $siswa->nisn ?></td>
                                                        <td class="text-start nama-siswa"><?= $siswa->nama_siswa ?></td>
                                                        <td class="kelas-siswa"><?= $siswa->nama_kelas ?? '-' ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary btn-pilih-nisn" style="font-size: 12px; padding: 3px 10px; font-weight: 500;" data-nisn="<?= $siswa->nisn ?>" title="Pilih Siswa">
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
            <!-- SISI KANAN (RIWAYAT DATA ABSEN HARI INI)       -->
            <!-- ============================================== -->
            <div class="col-xl-5">
                <div class="card border-success h-100 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-check text-white"></i> Data Riwayat Absen Hari Ini</h5>
                    </div>
                    <div class="card-body d-flex flex-column">

                        <!-- Area Kolom Pencarian, Filter Kelas/GTK, & Filter Izin/Sakit -->
                        <div class="row mb-3">
                            <div class="col-12 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                    <input type="text" id="cari_siswa_riwayat_absen" class="form-control" placeholder="Cari semua kolom (NISN, Nama, Kelas, Jam, Izin, Sakit)...">
                                </div>
                            </div>
                            <div class="col-md-7 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
                                    <select id="filter_kelas_riwayat_absen" class="form-control">
                                        <option value="">-- Semua Kelas / GTK --</option>
                                        <optgroup label="Peran / GTK">
                                            <option value="Guru">Guru</option>
                                            <option value="Pegawai">Pegawai</option>
                                        </optgroup>
                                        <optgroup label="Kelas Siswa">
                                            <?php foreach ($daftar_kelas as $kls) : ?>
                                                <?php if ($kls != 'Guru' && $kls != 'Pegawai') : ?>
                                                    <option value="<?= $kls ?>"><?= $kls ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-notes-medical"></i></span>
                                    <select id="filter_status_riwayat_absen" class="form-control">
                                        <option value="">-- Semua Status --</option>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Sakit">Sakit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div style="overflow-x: auto; overflow-y: scroll; flex: 1; max-height: calc(100vh - 250px); min-height: 520px;">
                            <table class="table table-bordered table-hover table-striped text-center align-middle" id="table_riwayat_absen" style="width:100%">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="18%">NISN/NIP</th>
                                        <th>Nama</th>
                                        <th width="15%">Kelas</th>
                                        <th width="14%">Masuk</th>
                                        <th width="14%">Pulang</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan di-load secara dinamis oleh AJAX DataTables -->
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

    <!-- PLUGIN DATATABLES JS -->
    <script src="<?= base_url('assets/plugins/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/2.0.2/timeago.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        var baseURL = '<?= base_url() ?>';
        var html5QrcodeScanner;
        var tableRiwayat; // Deklarasikan sebagai variabel global

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
            let isScannerActive = false;

            // 1. Coba jeda kamera dan tampilkan efek Spinner Overlay HANYA jika kamera aktif
            if (typeof html5QrcodeScanner !== 'undefined' && html5QrcodeScanner) {
                try {
                    html5QrcodeScanner.pause();
                    isScannerActive = true;

                    // Cukup panggil fadeIn, tidak perlu .append() HTML lagi
                    $('#scanner-spinner').css('display', 'flex').hide().fadeIn(150);
                } catch (error) {
                    // Abaikan jika error
                }
            }
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

                        // $('#list_data_absen').html(dt.list_absensi);
                        tableRiwayat.ajax.reload(null, false);
                        document.querySelector('.load_time').setAttribute('datetime', iso8601(new Date()));
                        timeago().render(document.querySelectorAll('.need_to_be_rendered'), 'id');

                        // 2. Alert Berhasil (Dengan Nama)
                        Swal.fire({
                            icon: 'success',
                            title: dt.nama,
                            text: dt.message,
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });

                        // 3. Mainkan Suara (Dinamis dari Konfigurasi Tambahan)
                        let audioSrc = dt.audio;
                        if (!audioSrc) {
                            let defFile = (dt.telatkah === 'ya') ? 'audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav' : 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav';
                            audioSrc = baseURL + '/assets/audio/' + defFile;
                        }
                        let audioObj = new Audio(audioSrc);
                        audioObj.play().catch(function(e) { console.warn('Audio play error:', e); });

                        // 4. HAPUS BARIS DARI TABEL BELUM ABSEN SECARA REAL-TIME (Hanya saat absen masuk)
                        if (dt.action === 'masuk') {
                            $('tr[data-nisn="' + dt.kode + '"]').fadeOut(500, function() {
                                $(this).remove();

                                // Cek jika tabel sudah kosong (semua siswa sudah absen)
                                if ($('.baris-siswa').length === 0) {
                                    $('#tabel_belum_absen tbody').html('<tr><td colspan="5" class="text-center text-success fw-bold">Alhamdulillah, semua siswa sudah absen hari ini!</td></tr>');
                                }
                            });
                        }

                    } else if (dt.response === 'holiday') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Hari Libur',
                            text: dt.message,
                            timer: 3000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                    } else {
                        // Mainkan Suara Peringatan / Sudah Absen dari Konfigurasi Tambahan
                        if (dt.audio) {
                            let audioObj = new Audio(dt.audio);
                            audioObj.play().catch(function(e) { console.warn('Audio play error:', e); });
                        }

                        // KUNCI: Gunakan nama (jika ada di JSON response) alih-alih tulisan 'Gagal' statis
                        let judulPenolakan = (dt.nama && dt.nama.trim() !== '') ? dt.nama : 'Peringatan';

                        Swal.fire({
                            icon: 'warning',
                            title: judulPenolakan,
                            text: dt.message,
                            timer: 3000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });

                        if (dt.list_absensi) tableRiwayat.ajax.reload(null, false);
                        // $('#list_data_absen').html(dt.list_absensi);
                    }

                    // 4. Reset Input, Hilangkan Spinner & Lanjut Kamera
                    $('#hasil_scanan').val('').focus();
                    setTimeout(() => {
                        if (isScannerActive) {
                            $('#scanner-spinner').fadeOut(150);
                            try {
                                html5QrcodeScanner.resume();
                            } catch (e) {}
                        }
                    }, 800);
                },
                error: function() {
                    $('#hasil_scanan').val('').focus();
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Terputus',
                        text: 'Gagal menghubungi server.',
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });

                    setTimeout(() => {
                        if (isScannerActive) {
                            $('#scanner-spinner').fadeOut(150);
                            try {
                                html5QrcodeScanner.resume();
                            } catch (e) {}
                        }
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
            // 1. Inisialisasi DataTables Server-Side untuk Riwayat Absen (Mode Scrolling, Paginasi Dinonaktifkan)
            tableRiwayat = $('#table_riwayat_absen').DataTable({
                "processing": true,
                "serverSide": true,
                "paging": false,
                "ajax": {
                    "url": baseURL + "/absensi/get_riwayat_absen_serverside",
                    "type": "POST",
                    "data": function(d) {
                        d.custom_search = $('#cari_siswa_riwayat_absen').val();
                        d.filter_kelas = $('#filter_kelas_riwayat_absen').val();
                        d.filter_status = $('#filter_status_riwayat_absen').val();
                    }
                },
                // Matikan fitur pengurutan (sorting) bawaan UI agar query SQL murni yang bekerja
                "ordering": false,
                "responsive": true,
                "dom": "rt<'d-flex justify-content-between align-items-center mt-2'i>",
                "columnDefs": [
                    { "targets": [0, 1, 3, 4, 5, 6], "className": "text-center align-middle" },
                    { "targets": [2], "className": "text-start align-middle" }
                ],
                // Kustomisasi teks bahasa Indonesia
                "language": {
                    "search": "Cari NISN atau Nama:",
                    "info": "Total Riwayat: _TOTAL_ data",
                    "infoEmpty": "Data kosong",
                    "infoFiltered": "(disaring dari _MAX_ data)",
                    "zeroRecords": "Tidak ada data riwayat absen yang cocok"
                }
            });

            // Filter Pencarian & Kelas Riwayat Absen Real-Time
            var searchRiwayatTimeout;
            $('#cari_siswa_riwayat_absen').on('keyup input', function() {
                clearTimeout(searchRiwayatTimeout);
                searchRiwayatTimeout = setTimeout(function() {
                    tableRiwayat.draw();
                }, 300);
            });

            $('#filter_kelas_riwayat_absen, #filter_status_riwayat_absen').on('change', function() {
                tableRiwayat.draw();
            });

            // 2. Event Listener untuk "Tombol Pilih" di dalam DataTables
            // Menggunakan .on('click') pada tbody karena tombol dibuat secara dinamis oleh AJAX
            $('#table_riwayat_absen tbody').on('click', '.btn-pilih-riwayat', function() {
                var nisn = $(this).data('nisn');
                $('#hasil_scanan').val(nisn).focus();
            });

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

            // Trigger ketika tombol Pilih di tabel Belum Absen diklik
            $('.btn-pilih-nisn').on('click', function() {
                var nisn = $(this).data('nisn'); // Ambil NISN dari tombol

                // HANYA salin ke inputan dan pindahkan fokus kursor tanpa menekan enter
                $('#hasil_scanan').val(nisn).focus();
            });

            // Filter Pencarian & Kelas secara Real-Time (Client-Side) - Fleksibel ke Seluruh Kolom
            $('#cari_siswa_belum_absen, #filter_kelas_belum_absen').on('keyup input change', function() {
                var keyword = $('#cari_siswa_belum_absen').val().toLowerCase().trim();
                var kelasFilter = $('#filter_kelas_belum_absen').val().toLowerCase().trim();

                $('#tabel_belum_absen tbody tr.baris-siswa').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var kelasText = $(this).find('.kelas-siswa').text().toLowerCase().trim();

                    // Cek apakah data cocok dengan teks pencarian di SELURUH KOLOM baris DAN filter kelas
                    var matchKeyword = (keyword === "" || rowText.indexOf(keyword) > -1);
                    var matchKelas = (kelasFilter === "" || kelasText === kelasFilter);

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