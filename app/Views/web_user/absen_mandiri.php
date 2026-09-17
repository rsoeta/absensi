<?= $this->extend('web_user/template_user') ?>
<?= $this->section('content') ?>

<!-- Pustaka CSS & JS Peta (Hanya dimuat jika diperlukan) -->
<?php if (!$is_manual_disabled && !$is_holiday) : ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="<?= base_url('assets/face-api/dist/face-api.min.js') ?>"></script>
<?php endif; ?>

<div id="content" class="app-content px-3 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0 fw-bold" style="color: #123e87;"><i class="fa fa-user-check me-2"></i>Absensi Kehadiran</h5>
    </div>

    <!-- SKENARIO 1: DINONAKTIFKAN MANUAL OLEH ADMIN -->
    <?php if ($is_manual_disabled) : ?>
        <div class="panel panel-inverse mb-3" style="border-radius: 15px; border: 2px solid #d33; background: #fff;">
            <div class="panel-body text-center p-4">
                <i class="fa fa-lock fa-4x text-danger mb-3"></i>
                <h4 class="text-dark fw-bold">Sistem Dikunci</h4>
                <p class="text-muted mb-0">Fitur Absensi Mandiri saat ini sedang dinonaktifkan oleh Administrator. Silakan hubungi admin jika ini adalah sebuah kesalahan.</p>
            </div>
        </div>

        <!-- SKENARIO 2: HARI LIBUR / TIDAK ADA JADWAL -->
    <?php elseif ($is_holiday) : ?>
        <div class="panel panel-inverse mb-3" style="border-radius: 15px; border: 2px solid #17a2b8; background: #fff;">
            <div class="panel-body text-center p-4">
                <i class="fa fa-calendar-times fa-4x text-info mb-3"></i>
                <h4 class="text-dark fw-bold">Hari Libur</h4>
                <p class="text-muted mb-0">Hari ini (<?= $hari_ini ?>) ditetapkan sebagai hari libur. Tidak ada jadwal absensi yang perlu diisi.</p>
            </div>
        </div>

        <!-- SKENARIO 3: SISTEM AKTIF (TAMPILKAN KAMERA & FORM) -->
    <?php else : ?>
        <div class="panel panel-inverse mb-3" style="border-radius: 15px; border: 2px solid #123e87; overflow: hidden; background: #000;">
            <div class="panel-body p-0 position-relative" style="min-height: 400px; display: grid; place-items: center;">
                <div id="my_camera" style="margin: 0 auto;"></div>
                <div id="map" class="shadow-lg" style="position: absolute; bottom: 12px; right: 12px; width: 110px; height: 140px; border-radius: 10px; border: 2px solid #ffd500; z-index: 10;"></div>
                <div style="position: absolute; bottom: 158px; right: 12px; background: rgba(0,0,0,0.6); color: #ffd500; padding: 2px 8px; border-radius: 5px; font-size: 10px; font-weight: bold; z-index: 10;">
                    <i class="fa fa-satellite-dish text-success"></i> GPS Aktif
                </div>
            </div>
        </div>

        <form id="formAbsen" action="<?= base_url('dashboard_user/proses_absen_mandiri') ?>" method="post">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="image_data" id="image_data">
            <input type="hidden" name="gps_time" id="gps_time">

            <div class="d-grid gap-2">
                <!-- Indikator AI -->
                <div id="ai-status" class="alert alert-secondary text-center fw-bold py-2 mb-2" style="border-radius: 10px;">
                    <i class="fa fa-spinner fa-spin"></i> Memuat AI Pendeteksi Wajah...
                </div>

                <!-- Tombol dikunci dari awal (disabled) -->
                <button type="button" id="btn-absen" class="btn btn-secondary fw-bold shadow-lg" onclick="prosesAbsen()" style="border-radius: 12px; font-size: 16px; padding: 14px;" disabled>
                    <i class="fa fa-lock fa-lg me-2"></i> TOMBOL DIKUNCI
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php if (!$is_manual_disabled && !$is_holiday) : ?>
    <script>
        // 1. Inisialisasi Peta (Tetap sama)
        var map = L.map('map', {
            zoomControl: false
        }).setView([-6.200000, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                $('#latitude').val(lat);
                $('#longitude').val(lng);
                $('#gps_time').val(Math.floor(Date.now() / 1000));
                var latlng = new L.LatLng(lat, lng);
                map.setView(latlng, 17);
                if (marker) map.removeLayer(marker);
                marker = L.marker(latlng).addTo(map);
            }, function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'GPS Ditolak',
                    text: 'Izinkan akses lokasi GPS.'
                });
            }, {
                enableHighAccuracy: true
            });
        }

        // 2. Inisialisasi Webcam
        Webcam.set({
            width: 300,
            height: 400,
            dest_width: 480,
            dest_height: 640,
            image_format: 'jpeg',
            jpeg_quality: 85,
            flip_horiz: true,
            constraints: {
                video: true,
                facingMode: "user"
            }
        });

        // 3. Load Model AI
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('<?= base_url("assets/face-api/weights") ?>'),
            faceapi.nets.faceExpressionNet.loadFromUri('<?= base_url("assets/face-api/weights") ?>')
        ]).then(startVideo);

        function startVideo() {
            Webcam.attach('#my_camera');

            // Tunggu kamera aktif, lalu mulai deteksi
            Webcam.on('live', function() {
                const videoEl = document.querySelector('#my_camera video');
                kunciAbsen('Kamera aktif. Mendeteksi wajah...');

                // Loop deteksi setiap 500ms
                setInterval(async () => {
                    if (videoEl.paused || videoEl.ended) return;

                    const detections = await faceapi.detectAllFaces(videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceExpressions();

                    if (detections.length > 0) {
                        const expressions = detections[0].expressions;
                        // Cek jika probabilitas senyum di atas 60%
                        if (expressions.happy > 0.6) {
                            bukaKunciAbsen();
                        } else {
                            // Jika ada wajah tapi tidak senyum (misal foto diam atau wajah datar)
                            kunciAbsen('Wajah terdeteksi. Silakan TERSENYUM untuk mengaktifkan tombol!');
                        }
                    } else {
                        // Jika wajah keluar dari frame kamera atau ditutupi benda lain
                        kunciAbsen('Wajah tidak ditemukan. Arahkan kamera ke wajah Anda.');
                    }
                }, 500);
            });
        }

        function bukaKunciAbsen() {
            $('#ai-status').removeClass('alert-warning alert-secondary')
                .addClass('alert-success')
                .html('<i class="fa fa-check-circle"></i> Liveness Valid! Silakan klik Rekam.');

            $('#btn-absen').removeClass('btn-secondary')
                .addClass('btn-warning')
                .css('color', '#123e87')
                .prop('disabled', false); // Buka kunci tombol
        }

        function kunciAbsen(pesan) {
            $('#ai-status').removeClass('alert-success alert-secondary')
                .addClass('alert-warning')
                .html('<i class="fa fa-spinner fa-spin"></i> ' + pesan);

            $('#btn-absen').removeClass('btn-warning')
                .addClass('btn-secondary')
                .css('color', '')
                .html('<i class="fa fa-lock fa-lg me-2"></i> TOMBOL DIKUNCI')
                .prop('disabled', true); // Kunci paksa tombol
        }

        function prosesAbsen() {
            var lat = $('#latitude').val();
            if (!lat) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tunggu',
                    text: 'Titik GPS belum terkunci.'
                });
                return;
            }
            Webcam.snap(function(data_uri) {
                $('#image_data').val(data_uri);
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $('#formAbsen').submit();
            });
        }
    </script>

<?php endif; ?>

<?= $this->endSection() ?>