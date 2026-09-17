<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kios Face Recognition | <?= $sett_apps->nama_sekolah ?? 'Sekolah' ?></title>

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            color: white;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .kios-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .video-wrapper {
            position: relative;
            border: 4px solid #1e293b;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        video {
            display: block;
            width: 640px;
            height: 480px;
            object-fit: cover;
            transform: scaleX(-1);
            /* Cermin */
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            transform: scaleX(-1);
            /* Samakan cermin video */
        }

        .notif-board {
            min-height: 120px;
            width: 640px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s;
        }
    </style>
</head>

<body>

    <div class="kios-container">
        <div class="mb-4 text-center">
            <h2 class="fw-bold text-warning"><i class="fa fa-fingerprint"></i> KIOS ABSENSI BIOMETRIK</h2>
            <p class="mb-0 text-light">Silakan tatap kamera untuk merekam kehadiran</p>
        </div>

        <div class="video-wrapper">
            <!-- Tambahkan width dan height secara eksplisit di sini -->
            <video id="video-kios" width="640" height="480" autoplay muted playsinline></video>

            <div id="ai-loading" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark" style="z-index: 10;">
                <i class="fa fa-circle-notch fa-spin fa-4x text-primary mb-3"></i>
                <h4 class="fw-bold text-white">Memuat Mesin AI...</h4>
            </div>
        </div>

        <div id="notif-board" class="notif-board bg-dark border border-secondary text-secondary shadow-lg">
            <span id="notif-text"><i class="fa fa-camera"></i> Bersiap mendeteksi wajah...</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="<?= base_url('assets/face-api/dist/face-api.min.js') ?>"></script>
    <script>
        const video = document.getElementById('video-kios');
        let isProcessing = false; // Penahan cooldown AJAX
        let faceMatcher = null;

        // 1. Muat Database Vektor dari PHP
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
                const float32Arr = new Float32Array(arr);
                labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(item.label, [float32Arr]));
            });

            // Toleransi ketat 0.45 (semakin kecil semakin mirip/akurat, max 0.6)
            if (labeledDescriptors.length > 0) {
                faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.45);
            }
            mulaiKamera();
        }

        function mulaiKamera() {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 640,
                        height: 480
                    }
                })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => {
                    console.error("Kamera ditolak: ", err);
                });
        }

        let isInitialized = false; // Tambahkan penanda agar kanvas tidak berlipat ganda

        video.addEventListener('play', () => {
            if (isInitialized) return; // Hentikan jika sudah pernah diinisialisasi
            isInitialized = true;

            // Paksa cabut class d-flex yang menghalangi jQuery, lalu sembunyikan
            $('#ai-loading').removeClass('d-flex').hide();

            // Buat kanvas bayangan (overlay kotak hijau pada wajah)
            const canvas = faceapi.createCanvasFromMedia(video);
            $('.video-wrapper').append(canvas);

            const displaySize = {
                width: 640,
                height: 480
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
            isProcessing = true; // Kunci sementara

            $.ajax({
                url: '<?= base_url("kios_wajah/proses_absen_otomatis") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    label: labelID
                },
                success: function(res) {
                    if (res.status === 'success') {
                        // Efek visual Sukses (Hijau)
                        $('#notif-board').removeClass('bg-dark border-secondary bg-warning border-warning').addClass('bg-success border-success text-white');
                        $('#notif-text').html(`<i class="fa fa-check-circle fa-2x mb-2 d-block"></i> Absen ${res.jenis} Berhasil!<br>${res.nama} (${res.waktu})`);
                        mainkanSuaraTing();
                    } else if (res.status === 'done' || res.status === 'cooldown') {
                        // Efek visual Peringatan (Kuning)
                        $('#notif-board').removeClass('bg-dark border-secondary bg-success border-success').addClass('bg-warning border-warning text-dark');
                        $('#notif-text').html(`<i class="fa fa-exclamation-circle fa-2x mb-2 d-block"></i> ${res.pesan}`);
                    }

                    // Tahan Kios selama 4 detik sebelum mereset untuk orang berikutnya
                    setTimeout(() => {
                        $('#notif-board').removeClass('bg-success border-success bg-warning border-warning text-white text-dark').addClass('bg-dark border-secondary text-secondary');
                        $('#notif-text').html('<i class="fa fa-camera"></i> Bersiap mendeteksi wajah...');
                        isProcessing = false; // Buka kunci
                    }, 4000);
                }
            });
        }

        function mainkanSuaraTing() {
            // Nada pendek untuk menandakan berhasil (opsional)
            const context = new(window.AudioContext || window.webkitAudioContext)();
            const osc = context.createOscillator();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, context.currentTime); // Nada A5
            osc.connect(context.destination);
            osc.start();
            osc.stop(context.currentTime + 0.1);
        }
    </script>

</body>

</html>