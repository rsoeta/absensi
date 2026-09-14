<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Absensi Sekolah</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <!-- CUSTOM THEME OVERRIDES -->
    <style>
        <?php if (isset($sett_apps) && $sett_apps->tema_aplikasi == 'muhammadiyah') : ?>
        /* TEMA MUHAMMADIYAH (CERAH DENGAN BACKGROUND SIRKUIT) */

        /* 1. Aktifkan Cover dan Panggil Gambar Background Baru */
        .app-cover {
            display: block !important;
            background-image: url('<?= base_url('assets/css/transparent/images/circuit-network-abstract-shape-diagonal-shinny-background_1409-1847.avif') ?>') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }

        /* 2. Beri Overlay Putih Transparan (85%) agar teks tetap terbaca */
        .app-cover:before {
            display: block !important;
            background: rgba(255, 255, 255, 0.85) !important;
        }

        /* 3. Body harus transparan agar .app-cover di bawahnya terlihat */
        body {
            background-color: transparent !important;
        }

        /* 4. Sidebar Menu & Header Atas (Biru Gelap Muhammadiyah) */
        .app-sidebar,
        .app-sidebar-bg {
            background-color: rgba(11, 40, 90, 0.95) !important;
        }

        .app-header {
            background-color: rgba(18, 62, 135, 0.95) !important;
        }

        .app-header .navbar-brand,
        .app-header .navbar-nav>.nav-item>.nav-link {
            color: #ffffff !important;
        }

        /* 5. Teks Menu Sidebar yang Aktif (Kuning) */
        .app-sidebar .menu .menu-item.active>.menu-link {
            color: #ffd500 !important;
            font-weight: bold;
            border-left: 4px solid #ffd500;
            background: rgba(255, 255, 255, 0.1) !important;
        }

        /* 6. Panel Bawaan (Mengubah Header Panel Inverse jadi Biru & Kuning) */
        .panel-inverse>.panel-heading {
            background: #123e87 !important;
            color: #ffffff !important;
            border-bottom: 3px solid #ffd500 !important;
        }

        /* 7. Ubah Background Panel agar putih semi-transparan (tidak hitam) */
        .panel {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid #ddd;
        }

        /* 8. Warna Tombol Utama (Biru) dan Tombol Warning (Kuning) */
        .btn-primary {
            background-color: #123e87 !important;
            border-color: #123e87 !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: #0b285a !important;
        }

        .btn-warning {
            background-color: #ffd500 !important;
            border-color: #ffd500 !important;
            color: #000 !important;
        }

        /* 9. Teks Judul Halaman (Biru) */
        .page-header {
            color: #123e87 !important;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* ========================================================
           10. MEMPERBAIKI TEKS TABEL & DATATABLES YANG MENGHILANG 
           ======================================================== */
        .app-content,
        .panel-body,
        .widget-chart-info,
        .text-white,
        .table,
        .table th,
        .table td,
        .dataTables_wrapper label,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate .paginate_button {
            color: #333333 !important;
            /* Paksa semua teks putih jadi gelap */
        }

        /* 11. Mempertegas Header Tabel (Biru Muhammadiyah) */
        .table thead th {
            background-color: #e9ecef !important;
            color: #123e87 !important;
            font-weight: 700 !important;
            border-bottom: 2px solid #ccc !important;
        }

        /* 12. Memperbaiki Form Kotak Pencarian & Dropdown di DataTables */
        .dataTables_wrapper select,
        .dataTables_wrapper input {
            color: #333 !important;
            background-color: #fff !important;
            border: 1px solid #ccc !important;
            border-radius: 4px;
            padding: 4px 8px;
        }

        .table-hover>tbody>tr:hover>* {
            color: #000 !important;
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        /* ========================================================
           13. MEMPERBAIKI FORM INPUT & SELECT (DROPDOWN BAWAAN)
           ======================================================== */
        .form-control,
        .form-select {
            color: #333333 !important;
            background-color: #ffffff !important;
            border: 1px solid #cccccc !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #123e87 !important;
            box-shadow: 0 0 0 0.25rem rgba(18, 62, 135, 0.25) !important;
        }

        .form-control::placeholder {
            color: #999999 !important;
            opacity: 1 !important;
        }

        select option {
            color: #333333 !important;
            background-color: #ffffff !important;
        }

        /* ========================================================
           14. MEMPERBAIKI PLUGIN SELECT2 (JIKA DIGUNAKAN)
           ======================================================== */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            background-color: #ffffff !important;
            border: 1px solid #cccccc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333333 !important;
        }

        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #cccccc !important;
        }

        .select2-results__option {
            color: #333333 !important;
            background-color: #ffffff !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #123e87 !important;
            color: #ffffff !important;
        }

        /* ========================================================
           15. MEMPERBAIKI PAGINATION BROWSE DATA
           ======================================================== */
        .pagination .page-link {
            color: #123e87 !important;
            background-color: #ffffff !important;
            border: 1px solid #cccccc !important;
        }

        .pagination .page-item.active .page-link {
            color: #ffffff !important;
            background-color: #123e87 !important;
            border-color: #123e87 !important;
        }

        .pagination .page-item.disabled .page-link {
            color: #999999 !important;
            background-color: #f8f9fa !important;
            border-color: #cccccc !important;
        }

        <?php else: ?>
        /* TEMA DEFAULT (DARK MODE BAWAAN) */
        <?php endif; ?>
    </style>

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
</head>

<body class='pace-top'>
    <div class="flash-data" data-flashdata="<?= session()->getFlashdata('message'); ?>"></div>
    <?php if (session()->getFlashdata('gagal')) : ?>
        <div class="alert alert-danger" style="position: absolute; top: 10px; width: 100%; z-index: 9999; text-align: center;">
            <?= session()->getFlashdata('gagal') ?>
        </div>
    <?php endif; ?>

    <div class="app-cover"></div>
    <div id="loader" class="app-loader"><span class="spinner"></span></div>

    <div id="app" class="app">
        <div class="login login-v2 fw-bold">
            <div class="login-cover">
                <div class="login-cover-img" style="background-image: url(<?= base_url('assets/img/login-bg/login-bg-13.jpg') ?>)"></div>
                <div class="login-cover-bg"></div>
            </div>

            <div class="login-container">
                <div class="login-header">
                    <div class="brand">
                        <div class="d-flex align-items-right">
                            <span class="logo"></span>Absensi Sekolah
                        </div>
                    </div>
                    <div class="icon"><i class="fa fa-lock"></i></div>
                </div>

                <div class="login-content">
                    <form action="<?= base_url('auth/process') ?>" method="post">
                        <div class="form-floating mb-20px">
                            <input type="text" required class="form-control fs-13px h-45px border-0" placeholder="Username" id="username" autocomplete="off" name="username" />
                            <label for="username" class="d-flex align-items-center text-gray-300 fs-13px">Username</label>
                        </div>
                        <div class="form-floating mb-20px">
                            <input type="password" required name="password" id="password" class="form-control fs-13px h-45px border-0" placeholder="Password" />
                            <label for="password" class="d-flex align-items-center text-gray-300 fs-13px">Password</label>
                        </div>
                        <div class="form-check mb-20px">
                            <input class="form-check-input border-0" type="checkbox" value="1" id="rememberMe" onclick="myFunction()" />
                            <label class="form-check-label fs-13px text-gray-500" for="rememberMe">Show Password</label>
                        </div>
                        <div class="mb-20px">
                            <button type="submit" name="login" class="btn btn-success d-block w-100 h-45px btn-lg"> LOGIN</button>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center" style="color: #ccc; font-size: 11px;">
                    <span class="logo"></span>Login siswa/Orang tua Wali untuk memantau kehadiran dan Izin menggunakan nomor pada KARTU absensi, silahkan lihat kembali kartu anda. Izin wajib mengunggah surat izin dengan TTD orang Tua. Jika Dokumen yang diupload asal-asalan izin akan kami tolak dan dinyatakan alpha.
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
    </div>

    <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/transparent.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/demo/login-v2.demo.js') ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="<?= base_url('assets/js/dataflash.js') ?>"></script>

    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>