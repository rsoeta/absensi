<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Lupa Password - <?= esc($sett_apps->nama_aplikasi ?? 'Absensi Sekolah') ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <!-- CUSTOM THEME OVERRIDES -->
    <style>
        <?php if (isset($sett_apps) && $sett_apps->tema_aplikasi == 'muhammadiyah') : ?>

        /* TEMA MUHAMMADIYAH */
        .app-cover {
            display: block !important;
            background-image: url('<?= base_url('assets/css/transparent/images/circuit-network-abstract-shape-diagonal-shinny-background_1409-1847.avif') ?>') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }

        .app-cover:before {
            display: block !important;
            background: rgba(255, 255, 255, 0.85) !important;
        }

        body {
            background-color: transparent !important;
        }

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

        .form-control {
            color: #333333 !important;
            background-color: #ffffff !important;
            border: 1px solid #cccccc !important;
        }

        .form-control:focus {
            border-color: #123e87 !important;
            box-shadow: 0 0 0 0.25rem rgba(18, 62, 135, 0.25) !important;
        }

        .form-control::placeholder {
            color: #999999 !important;
            opacity: 1 !important;
        }

        <?php else: ?>
        /* TEMA DEFAULT */
        <?php endif; ?>
    </style>

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
</head>

<body class='pace-top'>
    <div class="flash-data" data-flashdata="<?= session()->getFlashdata('message'); ?>"></div>
    <div class="flash-data2" data-flashdata2="<?= session()->getFlashdata('error'); ?>"></div>

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
                            <span class="logo"></span><?= esc($sett_apps->nama_aplikasi ?? 'Absensi Sekolah') ?>
                        </div>
                    </div>
                    <div class="icon"><i class="fa fa-unlock-alt"></i></div>
                </div>

                <div class="login-content">
                    <div class="mb-4">
                        <h4 class="mb-2" style="color: #ffd500; font-weight: 700;">Lupa Password</h4>
                        <p class="fs-13px text-gray-400 mb-0">
                            Masukkan Username (NISN / NIP / Username) atau Email Anda yang terdaftar untuk menerima link reset kata sandi.
                        </p>
                    </div>

                    <?php if (session()->getFlashdata('message')) : ?>
                        <div class="alert alert-success alert-dismissible fade show mb-20px" role="alert">
                            <i class="fa fa-check-circle me-1"></i> <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-warning alert-dismissible fade show mb-20px" role="alert">
                            <i class="fa fa-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('reset_link')) : ?>
                        <div class="p-3 mb-20px rounded text-start shadow" style="background: rgba(18, 62, 135, 0.95); border: 2px solid #ffd500; color: #ffffff;">
                            <div class="fw-bold mb-1 text-warning fs-14px">
                                <i class="fa fa-key me-1"></i> Tautan Reset Password Langsung
                            </div>
                            <p class="fs-12px mb-3 text-gray-200">
                                Di server lokal (localhost), Anda dapat langsung mengklik tombol di bawah ini untuk membuat kata sandi baru:
                            </p>
                            <a href="<?= session()->getFlashdata('reset_link') ?>" class="btn btn-warning w-100 fw-bold py-2 fs-13px shadow-sm" style="background-color: #ffd500; border-color: #ffd500; color: #0b285a;">
                                <i class="fa fa-arrow-right me-1"></i> BUAT PASSWORD BARU SEKARANG
                            </a>
                            <div class="mt-2 text-center" style="font-size: 11px;">
                                <a href="<?= session()->getFlashdata('reset_link') ?>" class="text-warning text-break"><?= session()->getFlashdata('reset_link') ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('reset_link')) : ?>
                        <div class="p-3 mb-20px rounded text-start shadow" style="background: rgba(18, 62, 135, 0.95); border: 2px solid #ffd500; color: #ffffff;">
                            <div class="fw-bold mb-1 text-warning fs-14px">
                                <i class="fa fa-key me-1"></i> Tautan Reset Password Langsung
                            </div>
                            <p class="fs-12px mb-3 text-gray-200">
                                Di server lokal (localhost), Anda dapat langsung mengklik tombol di bawah ini untuk membuat kata sandi baru:
                            </p>
                            <a href="<?= session()->getFlashdata('reset_link') ?>" class="btn btn-warning w-100 fw-bold py-2 fs-13px shadow-sm" style="background-color: #ffd500; border-color: #ffd500; color: #0b285a;">
                                <i class="fa fa-arrow-right me-1"></i> BUAT PASSWORD BARU SEKARANG
                            </a>
                            <div class="mt-2 text-center" style="font-size: 11px;">
                                <a href="<?= session()->getFlashdata('reset_link') ?>" class="text-warning text-break"><?= session()->getFlashdata('reset_link') ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/forgot_password_process') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-floating mb-20px">
                            <input type="text" required class="form-control fs-13px h-45px border-0" placeholder="Username atau Email" id="identity" autocomplete="off" name="identity" autofocus />
                            <label for="identity" class="d-flex align-items-center text-gray-300 fs-13px">Username atau Email</label>
                        </div>
                        <div class="mb-20px">
                            <button type="submit" class="btn btn-warning d-block w-100 h-45px btn-lg fw-bold" style="background-color: #ffd500; border-color: #ffd500; color: #0b285a;">
                                <i class="fa fa-paper-plane me-1"></i> KIRIM LINK RESET
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3 mb-20px">
                        <a href="<?= base_url('auth') ?>" class="text-white text-decoration-none fs-13px">
                            <i class="fa fa-arrow-left me-1"></i> Kembali ke Halaman Login
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="color: #ccc; font-size: 11px;">
                    <span class="logo"></span>Pemberitahuan: Link reset akan dikirimkan ke Email atau nomor WhatsApp terdaftar. Jika akun Anda belum memiliki email/WhatsApp terdaftar, silakan hubungi Administrator sekolah atau Wali Kelas.
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
</body>

</html>