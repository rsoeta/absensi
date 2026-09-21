<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Kios FR Biometric | <?= $sett_apps->nama_aplikasi ?></title>
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

    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/' . ($sett_apps->logo_sekolah ?? 'default.png')) ?>">
    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <style>
        .video-wrapper {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            height: 350px;
        }

        video {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            transform: scaleX(-1);
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }

        .notif-board {
            min-height: 80px;
            width: 100%;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            transition: all 0.3s;
        }

        /* 1. Kunci posisi awal konten tepat di bawah header (asumsi tinggi header 50px - 60px) */
        #content.app-content {
            padding-top: 55px !important;
            margin-top: 0 !important;
        }

        /* 2. TARIK PAKSA baris panel ke atas */
        #content.app-content>.row {
            margin-top: -90px !important;
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
    </style>
</head>

<body onload="tampilkanwaktu();setInterval('tampilkanwaktu()', 1000);">
    <div class="app-cover"></div>

    <div id="app" class="app app-header-fixed app-without-sidebar app-with-top-menu">
        <div id="header" class="app-header">
            <div class="navbar-header">
                <a href="#" class="navbar-brand">
                    <span><b>ABSENSI</b></span>&nbsp<span style="color: orange;"> <b>DIGITAL</b></span>&nbsp
                    <span style="color: orange;"><b><?= $sett_apps->nama_sekolah ?></b> </span>
                </a>
            </div>

            <!-- Switcher Kios (QR / Wajah) -->
            <div class="navbar-nav ms-auto align-items-center pe-3">
                <div class="btn-group" role="group">
                    <a href="<?= base_url('absensi') ?>" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-qrcode"></i> Kios QR Code
                    </a>
                    <a href="<?= base_url('kios_wajah') ?>" class="btn btn-sm btn-warning active fw-bold text-dark">
                        <i class="fas fa-fingerprint"></i> Kios Wajah AI
                    </a>
                </div>
            </div>
        </div>

        <div id="content" class="app-content">
            <div class="row mb-4">
                <!-- ============================================== -->
                <!-- SISI KIRI (WAKTU, KAMERA AI, & TABEL BELUM ABSEN)-->
                <!-- ============================================== -->
                <div class="col-xl-7">
                    <div class="row">
                        <!-- PANEL KAMERA AI (Lebar Penuh) -->
                        <div class="col-md-12 mb-3">
                            <div class="panel panel-inverse h-100">
                                <!-- Panel Heading dengan Flexbox agar Jam berada di kanan -->
                                <div class="panel-heading d-flex justify-content-between align-items-center">
                                    <h1 class="panel-title mb-0">
                                        <span><b>SCAN WAJAH</b></span><span style="color: orange;"> MASUK / PULANG</span>
                                    </h1>
                                    <div class="text-white fw-bold" style="font-size: 14px; letter-spacing: 0.5px;">
                                        <i class="far fa-calendar-alt text-info"></i> <?= $nama_hari ?>, <?= date('d M Y') ?> &nbsp;|&nbsp;
                                        <i class="far fa-clock text-info"></i> <span id="clock" class="text-warning"></span>
                                    </div>
                                </div>
                                <div class="panel-body text-center p-2">
                                    <div class="video-wrapper">
                                        <video id="video-kios" autoplay muted playsinline></video>
                                        <div id="ai-loading" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark" style="z-index: 10;">
                                            <i class="fa fa-circle-notch fa-spin fa-3x text-primary mb-2"></i>
                                            <h5 class="fw-bold text-white">Memuat AI...</h5>
                                        </div>
                                    </div>
                                    <div id="notif-board" class="notif-board bg-dark border border-secondary text-secondary shadow">
                                        <span id="notif-text"><i class="fa fa-camera"></i> Menunggu Wajah...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABEL BELUM ABSEN -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-user-times"></i> Data Siswa Belum Absen Hari Ini</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Sisipkan persis UI Tabel Pencarian & Filter Kelas dari halaman_absensi.php Anda di sini -->
                                    <div style="overflow-x: auto; max-height: 250px; overflow-y: scroll;">
                                        <table class="table table-bordered table-hover table-striped text-center align-middle">
                                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="20%">NISN</th>
                                                    <th>Nama Siswa</th>
                                                    <th width="20%">Kelas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($belum_absen)) : ?>
                                                    <?php $no = 1;
                                                    foreach ($belum_absen as $siswa) : ?>
                                                        <tr class="baris-siswa" id="row-belum-absen-<?= $siswa->nisn ?>" data-nisn="<?= $siswa->nisn ?>" data-nama="<?= $siswa->nama_siswa ?>">
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $siswa->nisn ?></td>
                                                            <td class="text-start"><?= $siswa->nama_siswa ?></td>
                                                            <td><?= $siswa->nama_kelas ?? '-' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-success fw-bold">Semua siswa sudah absen hari ini!</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- SISI KANAN (RIWAYAT DATA ABSEN HARI INI)     -->
                <!-- ============================================== -->
                <div class="col-xl-5">
                    <div class="panel panel-inverse h-100">
                        <div class="panel-heading">
                            <h1 class="panel-title"><span><b>DATA ABSEN</b></span><span style="color: orange;"> HARI INI</span></h1>
                        </div>
                        <div class="panel-body">
                            <!-- Sisipkan persis note-primary dari halaman_absensi.php Anda di sini -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover text-white align-middle" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>NISN/NIP</th>
                                            <th>Nama</th>
                                            <th>Waktu</th>
                                            <th>Ket</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $db = \Config\Database::connect();
                                        foreach ($dataabsen as $absen) {
                                            $getdatauser = $db->table('user')->where('user_id', $absen->user_id)->get()->getRow();
                                            if (!$getdatauser) continue;

                                            $nisn_nip = $getdatauser->username;
                                            $name = '-';
                                            if ($getdatauser->level_id == 2) {
                                                $name = $db->table('guru')->where('nip', $nisn_nip)->get()->getRow()->nama_guru ?? '-';
                                            }
                                            if ($getdatauser->level_id == 3) {
                                                $name = $db->table('pegawai')->where('nip', $nisn_nip)->get()->getRow()->nama_pegawai ?? '-';
                                            }
                                            if ($getdatauser->level_id == 4) {
                                                $name = $db->table('siswa')->where('nisn', $nisn_nip)->get()->getRow()->nama_siswa ?? '-';
                                            }

                                            $sts_m = ($absen->status_masuk == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger"></i>' : '<i class="fas fa-check-circle text-success"></i>';
                                            $sts_k = ($absen->status_pulang == 'Terlambat') ? '<i class="fas fa-exclamation-circle text-danger"></i>' : (($absen->status_pulang == 'Tepat Waktu') ? '<i class="fas fa-check-circle text-success"></i>' : '');

                                            echo "<tr><td>{$nisn_nip}</td><td>{$name}</td><td>{$absen->tanggal}</td><td>{$absen->keterangan}</td><td>{$absen->jam_masuk} {$sts_m}</td><td>{$absen->jam_pulang} {$sts_k}</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/face-api/dist/face-api.min.js') ?>"></script>

    <script>
        function tampilkanwaktu() {
            var w = new Date();
            var sh = w.getHours().toString().padStart(2, '0');
            var sm = w.getMinutes().toString().padStart(2, '0');
            var ss = w.getSeconds().toString().padStart(2, '0');
            document.getElementById("clock").innerHTML = sh + ":" + sm + ":" + ss;
        }

        const video = document.getElementById('video-kios');
        let isProcessing = false;
        let faceMatcher = null;
        const dbWajah = <?= $face_data ?>;

        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('<?= base_url("assets/face-api/weights") ?>'),
            faceapi.nets.faceLandmark68Net.loadFromUri('<?= base_url("assets/face-api/weights") ?>'),
            faceapi.nets.faceRecognitionNet.loadFromUri('<?= base_url("assets/face-api/weights") ?>')
        ]).then(inisialisasiMatcher);

        async function inisialisasiMatcher() {
            const labeledDescriptors = [];
            dbWajah.forEach(item => {
                const arr = JSON.parse(item.descriptor);
                labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(item.label, [new Float32Array(arr)]));
            });
            if (labeledDescriptors.length > 0) faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.45);

            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => console.error("Kamera ditolak: ", err));
        }

        let isInitialized = false;
        video.addEventListener('play', () => {
            if (isInitialized) return;
            isInitialized = true;

            $('#ai-loading').removeClass('d-flex').hide();
            const canvas = faceapi.createCanvasFromMedia(video);
            $('.video-wrapper').append(canvas);

            // Dapatkan ukuran aktual video yang dirender
            const displaySize = {
                width: video.clientWidth,
                height: video.clientHeight
            };
            faceapi.matchDimensions(canvas, displaySize);

            // Scan wajah setiap 100 milidetik
            setInterval(async () => {
                if (!faceMatcher) return;

                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                results.forEach((result, i) => {
                    const box = resizedDetections[i].detection.box;

                    // 1. Gambar kotak wajah secara manual
                    ctx.strokeStyle = result.label === 'unknown' ? '#dc3545' : '#28a745'; // Merah jika asing, Hijau jika valid
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    // 2. Format teks label & ubah nilai jarak menjadi Persentase Kecocokan
                    let labelText = "TIDAK DIKENAL";
                    if (result.label !== 'unknown') {
                        const parts = result.label.split('_');
                        if (parts.length >= 3) {
                            const nama = result.label.replace(parts[0] + '_' + parts[1] + '_', '');
                            const akurasi = Math.round((1 - result.distance) * 100);
                            labelText = `${nama} (${akurasi}%)`;
                        }
                    }

                    // 3. Tulis teks Anti-Cermin (Reverse Context)
                    ctx.save();
                    // Geser titik nol (0,0) ke pojok kanan kotak di dalam koordinat asli
                    ctx.translate(box.x + box.width, box.y);
                    // Balikkan skala X agar teks mundur, jadi saat terkena cermin CSS, teksnya maju/normal!
                    ctx.scale(-1, 1);

                    ctx.font = 'bold 18px "Segoe UI", sans-serif';
                    const textWidth = ctx.measureText(labelText).width;

                    // Gambar background teks agar mudah dibaca
                    ctx.fillStyle = result.label === 'unknown' ? 'rgba(220, 53, 69, 0.9)' : 'rgba(40, 167, 69, 0.9)';
                    ctx.fillRect(0, -32, textWidth + 20, 32);

                    // Cetak teksnya
                    ctx.fillStyle = '#ffffff';
                    ctx.fillText(labelText, 10, -10);

                    ctx.restore();

                    // 4. Eksekusi absen jika dikenali
                    if (result.label !== 'unknown' && !isProcessing) {
                        tembakAbsen(result.label);
                    }
                });
            }, 100);
        });

        function tembakAbsen(labelID) {
            isProcessing = true;
            $.post('<?= base_url("kios_wajah/proses_absen_otomatis") ?>', {
                label: labelID
            }, function(res) {
                if (res.status === 'success') {
                    $('#notif-board').removeClass('bg-dark bg-warning border-secondary').addClass('bg-success text-white');
                    $('#notif-text').html(`<i class="fa fa-check-circle"></i> Absen ${res.jenis} Berhasil!<br>${res.nama}`);

                    const audio = new Audio('<?= base_url("assets/audio/audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav") ?>');
                    audio.play().catch(e => console.log('Auto-play dicegah browser'));

                    // HAPUS BARIS DARI TABEL BELUM ABSEN SECARA INSTAN
                    $('tr[data-nama="' + res.nama + '"]').fadeOut(400, function() {
                        $(this).remove();
                    });

                    // Reload halaman secara mulus setelah 3 detik untuk update tabel Riwayat Absen
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    $('#notif-board').removeClass('bg-dark bg-success border-secondary').addClass('bg-warning text-dark');
                    $('#notif-text').html(`<i class="fa fa-exclamation-circle"></i> ${res.pesan}`);

                    setTimeout(() => {
                        $('#notif-board').removeClass('bg-success bg-warning text-white text-dark').addClass('bg-dark text-secondary');
                        $('#notif-text').html('<i class="fa fa-camera"></i> Menunggu Wajah...');
                        isProcessing = false;
                    }, 4000);
                }
            }, 'json');
        }
    </script>
</body>

</html>