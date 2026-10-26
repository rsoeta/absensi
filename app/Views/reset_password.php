<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Atur Ulang Password - <?= esc($sett_apps->nama_aplikasi ?? 'Absensi Sekolah') ?></title>
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

        .toggle-password-btn {
            cursor: pointer;
            z-index: 10;
            color: #6c757d;
            padding: 8px 12px;
            transition: color 0.2s ease-in-out;
            user-select: none;
        }

        .toggle-password-btn:hover {
            color: #123e87 !important;
        }
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
                    <div class="icon"><i class="fa fa-key"></i></div>
                </div>

                <div class="login-content">
                    <div class="mb-4">
                        <h4 class="mb-2" style="color: #ffd500; font-weight: 700;">Atur Ulang Kata Sandi</h4>
                        <p class="fs-13px text-gray-300 mb-0">
                            Silakan masukkan kata sandi baru untuk akun: <br>
                            <span class="badge bg-primary fs-12px mt-1"><i class="fa fa-user me-1"></i> <?= esc($username) ?> <?= !empty($user->nama_lengkap) ? '(' . esc($user->nama_lengkap) . ')' : '' ?></span>
                        </p>
                    </div>

                    <?php if (session()->getFlashdata('message')) : ?>
                        <div class="alert alert-success alert-dismissible fade show mb-20px" role="alert">
                            <i class="fa fa-check-circle me-1"></i> <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-20px" role="alert">
                            <i class="fa fa-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/change_password') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="username" value="<?= esc($username) ?>" />
                        <input type="hidden" name="token" value="<?= esc($token) ?>" />

                        <!-- Input Password Baru -->
                        <div class="position-relative mb-20px">
                            <div class="form-floating">
                                <input type="password" required minlength="4" class="form-control fs-13px h-45px border-0 pe-5" placeholder="Password Baru" id="password" autocomplete="new-password" name="password" autofocus />
                                <label for="password" class="d-flex align-items-center text-gray-400 fs-13px">Password Baru (min. 4 karakter)</label>
                            </div>
                            <span class="position-absolute end-0 top-50 translate-middle-y toggle-password-btn" id="eye-password" onclick="toggleVisibility('password', this)" title="Tampilkan Password">
                                <i class="fa fa-eye fs-16px"></i>
                            </span>
                        </div>

                        <!-- Input Konfirmasi Password Baru -->
                        <div class="position-relative mb-20px">
                            <div class="form-floating">
                                <input type="password" required minlength="4" class="form-control fs-13px h-45px border-0 pe-5" placeholder="Konfirmasi Password Baru" id="konfirmasi_password" autocomplete="new-password" name="konfirmasi_password" />
                                <label for="konfirmasi_password" class="d-flex align-items-center text-gray-400 fs-13px">Konfirmasi Password Baru</label>
                            </div>
                            <span class="position-absolute end-0 top-50 translate-middle-y toggle-password-btn" id="eye-konfirmasi" onclick="toggleVisibility('konfirmasi_password', this)" title="Tampilkan Password">
                                <i class="fa fa-eye fs-16px"></i>
                            </span>
                        </div>

                        <div class="form-check mb-20px">
                            <input class="form-check-input border-0" type="checkbox" value="1" id="showPass" onclick="togglePass()" />
                            <label class="form-check-label fs-13px text-gray-400" for="showPass">Tampilkan Semua Password</label>
                        </div>

                        <div class="mb-20px">
                            <button type="submit" class="btn btn-success d-block w-100 h-45px btn-lg fw-bold">
                                <i class="fa fa-check-circle me-1"></i> SIMPAN PASSWORD BARU
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3 mb-20px">
                        <a href="<?= base_url('auth') ?>" class="text-white text-decoration-none fs-13px">
                            <i class="fa fa-times-circle me-1"></i> Batal dan Kembali ke Login
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="color: #ccc; font-size: 11px;">
                    <span class="logo"></span>Tips Keamanan: Gunakan kombinasi huruf dan angka agar kata sandi Anda tidak mudah ditebak oleh orang lain.
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
        function toggleVisibility(inputId, el) {
            var input = document.getElementById(inputId);
            var icon = el.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.className = "fa fa-eye-slash fs-16px";
                el.setAttribute("title", "Sembunyikan Password");
            } else {
                input.type = "password";
                icon.className = "fa fa-eye fs-16px";
                el.setAttribute("title", "Tampilkan Password");
            }
        }

        function togglePass() {
            var show = document.getElementById("showPass").checked;
            var p1 = document.getElementById("password");
            var p2 = document.getElementById("konfirmasi_password");
            var icon1 = document.querySelector("#eye-password i");
            var icon2 = document.querySelector("#eye-konfirmasi i");

            p1.type = show ? "text" : "password";
            p2.type = show ? "text" : "password";

            if (icon1) icon1.className = show ? "fa fa-eye-slash fs-16px" : "fa fa-eye fs-16px";
            if (icon2) icon2.className = show ? "fa fa-eye-slash fs-16px" : "fa fa-eye fs-16px";
        }
    </script>
</body>

</html>
