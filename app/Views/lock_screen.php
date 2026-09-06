<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $sett_apps->nama_aplikasi ?> | Lock Screen</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
</head>

<body class='pace-top'>
    <div class="app-cover"></div>
    <div id="app" class="app">
        <div class="coming-soon">
            <div class="coming-soon-header">
                <div class="bg-cover"></div>
            </div>
            <div class="coming-soon-content">

                <img src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>" style="width: 150px;height: 150px;border-radius: 5%;"></img>
                <div class="brand">
                    <?= $sett_apps->nama_aplikasi ?>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger mx-auto" style="width: 80%; border-radius: 10px;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="desc">
                    Silahkan masukan password untuk membuka halaman absensi.
                    <br><br>
                    <form action="<?= base_url('absensi/un_lock') ?>" method="POST">
                        <div class="input-group input-group-lg mx-auto mb-2">
                            <span class="input-group-text border-0"><i class="fas fa-lock-open"></i></span>
                            <input type="password" name="un_lock" class="form-control fs-13px border-0 shadow-none" placeholder="Password Lock Screen" required />
                            <button type="submit" class="btn fs-13px btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>

        <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
        <script src="<?= base_url('assets/js/demo/coming-soon.demo.js') ?>"></script>
    </div>
</body>

</html>