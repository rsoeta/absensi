<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="panel-title mb-0"><i class="fas fa-volume-up mr-2"></i> PENGATURAN NOTIFIKASI SUARA (KONFIGURASI TAMBAHAN)</h4>
            </div>
            <div class="panel-body">
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Modul ini mengatur efek suara audio yang berbunyi saat proses pemindaian kartu / absensi berlangsung pada <b>Halaman Absensi (Kiosk)</b> maupun <b>Dashboard Pengguna</b>. Anda dapat menggunakan suara default sistem atau mengunggah suara kustom berformat <code>.wav</code>, <code>.mp3</code>, atau <code>.ogg</code>.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 320px;">Jenis Notifikasi & Deskripsi</th>
                                <th style="width: 250px;">Status Suara Aktif</th>
                                <th>Unggah & Kelola Suara</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($konfigurasi_tambahan_data as $konfigurasi_tambahan): 
                                $cfg_name = $konfigurasi_tambahan->config_name;
                                $def_info = $defaults[$cfg_name] ?? ['file' => 'popup.mp3', 'desc' => 'Notifikasi audio absensi.'];
                                $is_custom = (!empty($konfigurasi_tambahan->value) && file_exists(FCPATH . 'assets/audio/' . $konfigurasi_tambahan->value));
                                $active_file = $is_custom ? $konfigurasi_tambahan->value : $def_info['file'];
                                $active_audio_url = base_url('assets/audio/' . $active_file);
                            ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold text-primary mb-1">
                                            <i class="fas fa-bell me-1"></i> <?= esc($konfigurasi_tambahan->config_title_long) ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 12px; line-height: 1.4;">
                                            <?= esc($def_info['desc']) ?>
                                        </div>
                                        <span class="badge bg-light text-secondary border mt-1 font-monospace" style="font-size: 10px;">
                                            Key: <?= esc($cfg_name) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="mb-2">
                                            <?php if ($is_custom): ?>
                                                <span class="badge bg-success text-white px-2 py-1">
                                                    <i class="fas fa-check-circle me-1"></i> Audio Kustom
                                                </span>
                                                <div class="small text-truncate mt-1 text-dark fw-bold" style="max-width: 230px;" title="<?= esc($konfigurasi_tambahan->name_sound) ?>">
                                                    <i class="fas fa-file-audio text-success me-1"></i> <?= esc($konfigurasi_tambahan->name_sound) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary text-white px-2 py-1">
                                                    <i class="fas fa-cog me-1"></i> Bawaan Sistem (Default)
                                                </span>
                                                <div class="small text-truncate mt-1 text-muted font-monospace" style="font-size: 11px;">
                                                    <i class="fas fa-music me-1"></i> <?= esc($def_info['file']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary btn-play-audio" 
                                                data-audio="<?= $active_audio_url ?>" 
                                                title="Putar Audio untuk mendengar suara aktif">
                                            <i class="fas fa-play me-1"></i> Dengarkan Suara
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="input-group">
                                                <input type="file" 
                                                       accept=".wav,.mp3,.ogg,audio/*" 
                                                       class="form-control form-control-sm input-audio-file" 
                                                       data-id="<?= $konfigurasi_tambahan->id_config ?>" />
                                                <button type="button" 
                                                        class="btn btn-sm btn-success btn-upload-sound" 
                                                        data-id="<?= $konfigurasi_tambahan->id_config ?>">
                                                    <i class="fas fa-cloud-upload-alt me-1"></i> Simpan
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted" style="font-size: 11px;">
                                                    <i class="fas fa-info-circle"></i> Format didukung: <code>.wav</code>, <code>.mp3</code>, <code>.ogg</code> (Maks. 5 MB)
                                                </small>
                                                <?php if ($is_custom): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger btn-reset-sound py-0 px-2" 
                                                            style="font-size: 11px;"
                                                            data-id="<?= $konfigurasi_tambahan->id_config ?>" 
                                                            title="Kembalikan ke suara bawaan default sistem">
                                                        <i class="fas fa-undo me-1"></i> Kembalikan ke Default
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var currentAudio = null;
        var currentBtn = null;

        // Putar & Hentikan Audio Preview
        $(document).on('click', '.btn-play-audio', function() {
            var btn = $(this);
            var audioUrl = btn.data('audio');

            if (currentAudio && !currentAudio.paused) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                if (currentBtn) {
                    currentBtn.html('<i class="fas fa-play me-1"></i> Dengarkan Suara').removeClass('btn-danger').addClass('btn-outline-primary');
                }
                if (currentBtn && currentBtn[0] === btn[0]) {
                    currentAudio = null;
                    currentBtn = null;
                    return;
                }
            }

            currentAudio = new Audio(audioUrl);
            currentBtn = btn;

            btn.html('<i class="fas fa-stop me-1"></i> Berhenti').removeClass('btn-outline-primary').addClass('btn-danger');

            currentAudio.play().catch(function(err) {
                console.warn('Gagal memutar audio:', err);
                btn.html('<i class="fas fa-play me-1"></i> Dengarkan Suara').removeClass('btn-danger').addClass('btn-outline-primary');
                Swal.fire('Info', 'Tidak dapat memutar file audio. Pastikan file valid.', 'info');
            });

            currentAudio.addEventListener('ended', function() {
                btn.html('<i class="fas fa-play me-1"></i> Dengarkan Suara').removeClass('btn-danger').addClass('btn-outline-primary');
                currentAudio = null;
                currentBtn = null;
            });
        });

        // Upload Audio Baru
        $(document).on('click', '.btn-upload-sound', function() {
            var id_config = $(this).data('id');
            var row = $(this).closest('tr');
            var fileInput = row.find('.input-audio-file')[0];
            var file = fileInput.files[0];

            if (!file) {
                Swal.fire('Pilih File', 'Silakan pilih file audio (.wav, .mp3, atau .ogg) terlebih dahulu.', 'warning');
                return;
            }

            // Validasi ekstensi
            var allowedExt = ['wav', 'mp3', 'ogg', 'm4a'];
            var fileExt = file.name.split('.').pop().toLowerCase();
            if ($.inArray(fileExt, allowedExt) === -1) {
                Swal.fire('Format Tidak Didukung', 'Format file harus .wav, .mp3, atau .ogg', 'error');
                return;
            }

            // Validasi ukuran maks 5MB
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('File Terlalu Besar', 'Ukuran file audio maksimal adalah 5 MB.', 'warning');
                return;
            }

            var formData = new FormData();
            formData.append('id_config', id_config);
            formData.append('file', file);

            Swal.fire({
                title: 'Menyimpan Audio...',
                text: 'Harap tunggu sedang mengunggah file.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= base_url('konfigurasi_tambahan/update_action') ?>",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: resp.message || 'Audio berhasil disimpan.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', resp.message || 'Gagal menyimpan file audio.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server: ' + error, 'error');
                }
            });
        });

        // Reset Audio ke Default
        $(document).on('click', '.btn-reset-sound', function() {
            var id_config = $(this).data('id');

            Swal.fire({
                title: 'Kembalikan ke Default?',
                text: 'File audio kustom akan dihapus dan notifikasi akan menggunakan suara bawaan sistem.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "<?= base_url('konfigurasi_tambahan/reset_action') ?>",
                        type: 'POST',
                        data: { id_config: id_config },
                        dataType: 'json',
                        success: function(resp) {
                            if (resp.status === 'ok') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Selesai!',
                                    text: resp.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', resp.message || 'Gagal mereset audio.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Terjadi kesalahan pada server: ' + error, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>