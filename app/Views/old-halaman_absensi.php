<!DOCTYPE html>
<html lang="en">
<?php date_default_timezone_set('Asia/Jakarta'); ?>

<head>
    <meta charset="utf-8" />
    <title><?= $sett_apps->nama_aplikasi ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="<?= base_url() ?>assets/css/vendor.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/css/transparent/app.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Optimasi SweetAlert2 khusus untuk layar Mobile */
        .swal2-popup {
            font-size: 0.85rem !important;
            /* Perkecil ukuran font dasar */
            width: 85% !important;
            /* Proporsional di layar HP */
            max-width: 320px !important;
            /* Batas maksimal agar tidak kebesaran di Desktop */
            padding: 1.2em !important;
            border-radius: 15px !important;
            /* Sudut lebih membulat dan elegan */
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

<body>

    <div class="app-cover"></div>
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <div id="app" class="app app-header-fixed app-sidebar-fixed app-without-sidebar app-with-top-menu">

        <div id="header" class="app-header">
            <div class="navbar-header">
                <a href="#" class="navbar-brand"><span class="navbar-logo"></span> <span><b>ABSENSI</b></span>&nbsp<span style="color: orange;"> <b>DIGITAL</b></span>&nbsp<span style="color: orange;"><b><?= $sett_apps->nama_sekolah ?></b> </span></a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-top-menu-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-nav">
                <div class="navbar-item dropdown">
                    <a href="<?= base_url() ?>auth/lock" class="navbar-link dropdown-toggle icon">
                        <i class="fas fa-lock"></i>
                        Lock Halaman
                    </a>
                </div>
            </div>
        </div>

        <div id="content" class="app-content">
            <?php if ($status_pengumuman == 'Aktif') { ?>
                <div class="alert alert-info" role="alert">
                    <marquee>
                        <h2><?= $text ?> </h2>
                    </marquee>
                </div>
            <?php } ?>


            <div class="row mb-4">
                <div class="col-md-2 ui-sortable">
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1" data-init="true">

                        <div class="panel-heading ui-sortable-handle">
                            <h1 class="panel-title"> <span><b>WAKTU</b></span>&nbsp<span style="color: orange;">SERVER</span> </h1>

                        </div>
                        <div class="panel-body">
                            <script type="text/javascript">
                                function tampilkanwaktu() {
                                    var waktu = new Date();
                                    var sh = waktu.getHours() + "";
                                    var sm = waktu.getMinutes() + "";
                                    var ss = waktu.getSeconds() + "";
                                    document.getElementById("clock").innerHTML = (sh.length == 1 ? "0" + sh : sh) + ":" + (sm.length == 1 ? "0" + sm : sm) + ":" + (ss.length == 1 ? "0" + ss : ss);
                                }
                            </script>
                            <center>

                                <body onload="tampilkanwaktu();setInterval('tampilkanwaktu()', 1000);">
                                    <?php
                                    $hari = date('l');
                                    if ($hari == "Sunday") {
                                        echo "Minggu";
                                    } elseif ($hari == "Monday") {
                                        echo "Senin";
                                    } elseif ($hari == "Tuesday") {
                                        echo "Selasa";
                                    } elseif ($hari == "Wednesday") {
                                        echo "Rabu";
                                    } elseif ($hari == "Thursday") {
                                        echo ("Kamis");
                                    } elseif ($hari == "Friday") {
                                        echo "Jum'at";
                                    } elseif ($hari == "Saturday") {
                                        echo "Sabtu";
                                    }
                                    ?>,
                                    <?php
                                    $tgl = date('d');
                                    echo $tgl;
                                    $bulan = date('F');
                                    if ($bulan == "January") {
                                        echo " Januari ";
                                    } elseif ($bulan == "February") {
                                        echo " Februari ";
                                    } elseif ($bulan == "March") {
                                        echo " Maret ";
                                    } elseif ($bulan == "April") {
                                        echo " April ";
                                    } elseif ($bulan == "May") {
                                        echo " Mei ";
                                    } elseif ($bulan == "June") {
                                        echo " Juni ";
                                    } elseif ($bulan == "July") {
                                        echo " Juli ";
                                    } elseif ($bulan == "August") {
                                        echo " Agustus ";
                                    } elseif ($bulan == "September") {
                                        echo " September ";
                                    } elseif ($bulan == "October") {
                                        echo " Oktober ";
                                    } elseif ($bulan == "November") {
                                        echo " November ";
                                    } elseif ($bulan == "December") {
                                        echo " Desember ";
                                    }
                                    $tahun = date('Y');
                                    echo $tahun;
                                    ?>
                                    <h1><span id="clock"></span></h1>
                                </body>
                            </center>
                            <form id="form_input_nisnnip">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="hasil_scanan" name="tb_input_kd" value="" placeholder="KETIK NISN">
                                    <button type="button" class="btn btn-primary"><i class="fas fa-save"></i></button>
                                </div>
                            </form>
                            <br>
                            <hr>
                            <div class="info-overview-absen">

                            </div>
                            <!-- <img src="<?php echo base_url(); ?>assets/img/siswa/tes.png" style="width: 150px;height: 150px;border-radius: 5%;display: block;margin-left: auto;margin-right: auto;" border="2"></img>
							<input style="margin-top: 4px;text-align: center;" type="text" class="form-control" value="Muhammad Saeful Ramdan" readonly> -->
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 ui-sortable">
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-3" data-init="true">

                        <div class="panel-heading ui-sortable-handle">
                            <h1 class="panel-title"><span><b>SCAN UNTUK</b></span><span style="color: orange;"> MASUK</span></h1>
                            <div class="panel-heading-btn">
                            </div>
                        </div>
                        <div class="panel-body">

                            <div class="container" id="QR-Code">
                                <div class="panel-body text-center">
                                    <div class="col-md-6">
                                        <div class="well" style="position: relative;display: inline-block;">
                                            <!-- <canvas width="100%" height="240" id="webcodecam-canvas" style="transform: scale(1, 1);"></canvas> -->
                                            <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                                            <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                                            <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                                        </div>
                                    </div>
                                    <p id="scanned-QR"></p>
                                </div>
                            </div>
                            <div class="input-group">
                                <select class="form-control" id="camera-select"></select>
                                <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="fa fa-play"></span></button>
                                <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="fa fa-pause"></span></button>
                                <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="fa fa-stop"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 ui-sortable">


                    <div class="panel panel-inverse" data-sortable-id="form-stuff-3" data-init="true">

                        <div class="panel-heading ui-sortable-handle">
                            <h1 class="panel-title"><span><b>DATA ABSEN MASUK</b></span><span style="color: orange;"> HARI INI</span></h1>
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
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
                                            <td>Jam Masuk : <b><?= $jam_masuk_m ?></b> | Jam Pulang : <b><?= $jam_keluar_m ?></b> </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-sm table-hover table-td-valign-middle text-white">
                                    <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th>Nama</th>
                                            <th>Level</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_data_absen">
                                        <?php
                                        $str = '';
                                        foreach ($dataabsen as $absen) {
                                            $getdatauser = $this->db->query("SELECT * FROM user WHERE user_id='" . $absen->user_id . "'")->row();
                                            $name = 'null';
                                            $level = 'null';
                                            // var_dump($getdatauser->username);
                                            if ($getdatauser->level_id == 1) {
                                                $name = 'Admin Aplikasi';
                                                $level = 'Admin Aplikasi';
                                            }
                                            if ($getdatauser->level_id == 2) {
                                                $name = nama_guru($getdatauser->user_id);
                                                $level = 'Guru';
                                            }
                                            if ($getdatauser->level_id == 3) {
                                                $name = nama_pegawai($getdatauser->user_id);
                                                $level = 'Pegawai';
                                            }
                                            if ($getdatauser->level_id == 4) {
                                                $name = nama_siswa($getdatauser->user_id);
                                                $level = 'Murid';
                                            }

                                            $sts_m = '';
                                            $sts_k = '';

                                            if ($absen->status_masuk == 'Terlambat') {
                                                $sts_m = '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>';
                                            }
                                            if ($absen->status_masuk == 'Tepat Waktu') {
                                                $sts_m = '<i class="fas fa-check-circle" style="color: #04c142;"></i>';
                                            }
                                            if ($absen->status_pulang == 'Terlambat') {
                                                $sts_k = '<i class="fas fa-exclamation-circle" style="color: #ff3502;"></i>';
                                            }
                                            if ($absen->status_pulang == 'Tepat Waktu') {

                                                $sts_k = '<i class="fas fa-check-circle" style="color: #04c142;"></i>';
                                            }

                                            $str .= '<tr>
														<td>' . $name . '</td>
														<td>' . $level . '</td>
														<td>' . $absen->tanggal . '</td>
														<td>' . $absen->keterangan . '</td>
														<td>' . $absen->jam_masuk . ' <span>' . $sts_m . '</span></td>
														<td>' . $absen->jam_pulang . ' <span>' . $sts_k . '</span></td>
													</tr>';
                                        }

                                        echo $str;
                                        ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/2.0.2/timeago.min.js" integrity="sha512-sl01o/gVwybF1FNzqO4NDRDNPJDupfN0o2+tMm4K2/nr35FjGlxlvXZ6kK6faa9zhXbnfLIXioHnExuwJdlTMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        // create base url from php
        var baseURL = '<?php echo base_url(); ?>';
        // create site url from php
        var siteURL = '<?php echo site_url(); ?>';

        function iso8601(date) {
            return date.getUTCFullYear() +
                "-" + (date.getUTCMonth() + 1) +
                "-" + date.getUTCDate() +
                "T" + date.getUTCHours() +
                ":" + date.getUTCMinutes() +
                ":" + date.getUTCSeconds() + "Z";
        }

        function do_absen(codeny) {
            // Menampilkan loading UI yang sudah diperkecil
            Swal.fire({
                title: 'Memproses...',
                html: '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal2-popup'
                }
            });

            $.ajax({
                // Di CI4, pemanggilan controller menyesuaikan route dasar
                url: baseURL + '/absensi/get_info_absen',
                type: 'POST',
                data: {
                    codeny: codeny
                },
                success: function(data) {
                    // CI4 '$this->response->setJSON()' sudah mengirim format object, tidak perlu JSON.parse()
                    var dt = typeof data === 'string' ? JSON.parse(data) : data;
                    var elementinfowrapper = $('.info-overview-absen');
                    var telatkah = dt.telatkah;

                    if (dt.response == 'ok') {
                        var tipe = dt.type;
                        var gambarny = dt.photo;
                        var namany = dt.nama;
                        var stamp = 'p';

                        elementinfowrapper.html(`
					<img src="${baseURL}/assets/img/${tipe}/${gambarny}" style="width: 130px;height: 130px;border-radius: 10%;display: block;margin-left: auto;margin-right: auto;object-fit: cover;" border="2"></img>
					<input style="margin-top: 10px;text-align: center; font-weight: bold;" type="text" class="form-control" value="${namany}" readonly>
					<p style="font-size: 11px; color: gray; text-align: center; margin-top: 5px;">Absen: <time class="need_to_be_rendered load_time strong">${stamp}</time></p>
				`);

                        $('#list_data_absen').html(dt.list_absensi);
                        document.querySelector('.load_time').setAttribute('datetime', iso8601(new Date()));
                        timeago().render(document.querySelectorAll('.need_to_be_rendered'), 'id');

                        // Toast notifikasi diletakkan di tengah bawah agar pas untuk jempol
                        const ToastMobile = Swal.mixin({
                            toast: true,
                            position: 'bottom-center',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        ToastMobile.fire({
                            icon: 'success',
                            title: 'Absensi Berhasil'
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: dt.message,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#d33'
                        });
                        $('#list_data_absen').html(dt.list_absensi);
                    }

                    $('#hasil_scanan').val('').focus();

                    // Memutar audio notifikasi
                    let audiony = new Audio();
                    if (telatkah == 'ya') {
                        audiony = new Audio(baseURL + '/assets/audio/audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav');
                    } else {
                        audiony = new Audio(baseURL + '/assets/audio/audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav');
                    }
                    audiony.play();
                },
                error: function(e) {
                    $('#hasil_scanan').val('').focus();
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Terputus',
                        text: 'Gagal menghubungi server.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }

        $(document).on('submit', '#form_input_nisnnip', function(e) {
            e.preventDefault()

            var kdnya = $('#hasil_scanan').val()

            do_absen(kdnya)
        })
    </script>
    <!-- Load Library HTML5-QRCode Modern -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            // Fungsi ketika QR Code berhasil terbaca
            function onScanSuccess(decodedText, decodedResult) {
                // Hentikan sementara kamera agar tidak scan berulang kali dalam 1 detik
                html5QrcodeScanner.pause();

                // Tampilkan indikator loading dengan SweetAlert2
                Swal.fire({
                    title: 'Memproses Absensi...',
                    text: 'Membaca data: ' + decodedText,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Tembak data ke Controller Absensi
                $.ajax({
                    url: '<?= base_url('absensi/get_info_absen') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        codeny: decodedText
                    },
                    success: function(res) {
                        if (res.response === 'ok') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                html: `<b>${res.nama}</b><br>Tercatat sebagai ${res.type}<br><small>${res.message}</small>`,
                                timer: 2500,
                                showConfirmButton: false
                            });

                            // Update tabel riwayat absensi secara live jika ada
                            if (res.list_absensi) {
                                $('#tabel-riwayat-absen').html(res.list_absensi);
                            }
                        } else if (res.response === 'holiday') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Hari Libur',
                                text: res.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message
                            });
                        }

                        // Nyalakan kembali kamera setelah 3 detik
                        setTimeout(() => {
                            html5QrcodeScanner.resume();
                        }, 3000);
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Terputus',
                            text: 'Gagal menghubungi server'
                        });
                        setTimeout(() => {
                            html5QrcodeScanner.resume();
                        }, 3000);
                    }
                });
            }

            // Konfigurasi Kamera (Fokus otomatis, ukuran kotak scan, kamera belakang)
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    rememberLastUsedCamera: true
                },
                false
            );

            // Mulai render kamera
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/filereader.js'); ?>"></script>
    <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/qrcodelib.js'); ?>"></script> -->
    <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/webcodecamjquery.js'); ?>"></script> -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/mainjquery.js'); ?>"></script>


    <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/transparent.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/d3/d3.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/nvd3/build/nv.d3.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js') ?>"></script>
    <!-- <script src="<?= base_url('assets/js/demo/dashboard-v2.js') ?>"></script> -->
    <script src="<?= base_url('assets/js/rocket-loader.min.js') ?>" data-cf-settings="beba54df5f87d24c2458d535-|49" defer=""></script>
    <script src="<?= base_url('assets/plugins/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script>
</body>

</html>

<script>
    $('#data-table-default').DataTable({
        responsive: true
    });
</script>