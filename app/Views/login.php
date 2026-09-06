<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Absensi Sekolah</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

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
                <div class="login-cover-img" style="background-image: url(<?= base_url('assets/img/login-bg/citi.jpg') ?>)"></div>
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