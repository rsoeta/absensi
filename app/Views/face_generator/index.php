<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content px-3 py-3">
    <h1 class="page-header mb-3"><i class="fa fa-brain me-2"></i>AI Face Master Generator</h1>

    <div class="panel panel-inverse shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <div class="panel-heading" style="background: #123e87; color: white;">
            <h4 class="panel-title">Ekstraksi Vektor Wajah (128-Dimensi)</h4>
        </div>
        <div class="panel-body text-center p-4">

            <div id="status-ai" class="alert alert-warning fw-bold mb-4">
                <i class="fa fa-spinner fa-spin"></i> Sedang memuat Model Kecerdasan Buatan (AI)...
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm p-3">
                        <h5 class="text-dark">Guru</h5>
                        <h2 class="text-primary mb-0"><?= count($guru) ?></h2>
                        <small class="text-muted">Menunggu Ekstraksi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm p-3">
                        <h5 class="text-dark">Pegawai</h5>
                        <h2 class="text-success mb-0"><?= count($pegawai) ?></h2>
                        <small class="text-muted">Menunggu Ekstraksi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm p-3">
                        <h5 class="text-dark">Siswa</h5>
                        <h2 class="text-danger mb-0"><?= count($siswa) ?></h2>
                        <small class="text-muted">Menunggu Ekstraksi</small>
                    </div>
                </div>
            </div>

            <div class="progress mb-3" style="height: 25px; border-radius: 10px; display: none;" id="progress-container">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success fw-bold" id="progress-bar" style="width: 0%;">0%</div>
            </div>

            <p id="log-proses" class="text-muted fw-bold"></p>

            <button type="button" id="btn-mulai" class="btn btn-primary btn-lg fw-bold shadow-lg" style="border-radius: 10px;" disabled onclick="mulaiEkstraksi()">
                <i class="fa fa-play-circle me-2"></i> MULAI PROSES EKSTRAKSI
            </button>

            <!-- Tempat merender gambar sembunyi untuk dibaca AI -->
            <img id="img-hidden" style="display: none; max-width: 500px;" crossorigin="anonymous" />

        </div>
    </div>
</div>

<script src="<?= base_url('assets/face-api/dist/face-api.min.js') ?>"></script>
<script>
    // Menyusun antrean data (Task Queue)
    const antrean = [];

    <?php foreach ($guru as $g): ?>
        antrean.push({
            jenis: 'guru',
            id: <?= $g->guru_id ?>,
            nama: '<?= addslashes($g->nama_guru) ?>',
            foto: '<?= base_url("assets/img/guru/" . $g->photo) ?>'
        });
    <?php endforeach; ?>
    <?php foreach ($pegawai as $p): ?>
        antrean.push({
            jenis: 'pegawai',
            id: <?= $p->pegawai_id ?>,
            nama: '<?= addslashes($p->nama_pegawai) ?>',
            foto: '<?= base_url("assets/img/pegawai/" . $p->photo) ?>'
        });
    <?php endforeach; ?>
    <?php foreach ($siswa as $s): ?>
        antrean.push({
            jenis: 'siswa',
            id: <?= $s->siswa_id ?>,
            nama: '<?= addslashes($s->nama_siswa) ?>',
            foto: '<?= base_url("assets/img/siswa/" . $s->photo) ?>'
        });
    <?php endforeach; ?>

    // Memuat 3 Model Wajib untuk Face Recognition
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('<?= base_url("assets/face-api/weights") ?>'),
        faceapi.nets.faceLandmark68Net.loadFromUri('<?= base_url("assets/face-api/weights") ?>'),
        faceapi.nets.faceRecognitionNet.loadFromUri('<?= base_url("assets/face-api/weights") ?>')
    ]).then(() => {
        $('#status-ai').removeClass('alert-warning').addClass('alert-success').html('<i class="fa fa-check-circle"></i> AI Siap Beroperasi!');

        if (antrean.length > 0) {
            $('#btn-mulai').prop('disabled', false);
        } else {
            $('#log-proses').text("Semua data foto sudah diekstrak. Tidak ada antrean.");
        }
    });

    async function mulaiEkstraksi() {
        $('#btn-mulai').hide();
        $('#progress-container').show();
        let total = antrean.length;
        let berhasil = 0;
        let gagal = 0;

        for (let i = 0; i < total; i++) {
            let data = antrean[i];
            $('#log-proses').text(`Menganalisa [${i+1}/${total}]: ${data.nama}...`);

            let img = document.getElementById('img-hidden');
            img.src = data.foto;

            // Tunggu gambar selesai di-load browser
            await new Promise((resolve) => {
                img.onload = resolve;
                img.onerror = resolve; // Lewati jika gambar broken
            });

            try {
                // Deteksi wajah, titik landmark, dan ekstrak ke Vektor 128D
                const deteksi = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

                if (deteksi) {
                    // Jadikan Vektor 128D sebagai Array Murni dan jadikan JSON String
                    const descriptorJSON = JSON.stringify(Array.from(deteksi.descriptor));

                    // Simpan ke Database
                    await $.post('<?= base_url("face_generator/save_descriptor") ?>', {
                        jenis: data.jenis,
                        id: data.id,
                        descriptor: descriptorJSON
                    });
                    berhasil++;
                } else {
                    gagal++; // Wajah tidak terdeteksi dalam foto profil
                }
            } catch (err) {
                gagal++;
            }

            // Update Progress Bar
            let persen = Math.round(((i + 1) / total) * 100);
            $('#progress-bar').css('width', persen + '%').text(persen + '%');
        }

        $('#log-proses').html(`<span class="text-success">Selesai! Berhasil: ${berhasil} | Wajah tak terbaca: ${gagal}</span>`);
        Swal.fire('Proses Selesai', `Berhasil mengekstrak ${berhasil} wajah dari total ${total} antrean.`, 'success');
    }
</script>

<?= $this->endSection() ?>