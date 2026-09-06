<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KONFIGURASI TAMBAHAN</h4>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover table-td-valign-middle">
                    <?php foreach ($konfigurasi_tambahan_data as $konfigurasi_tambahan): ?>
                        <tr>
                            <td width="300"><i class="fa fa-music"></i> <?= $konfigurasi_tambahan->config_title_long ?>
                                <p style="color: red; font-size:10px; margin-bottom:0;">*wajib upload .wav</p>
                            </td>
                            <td>
                                <?php if ($konfigurasi_tambahan->config_type == 'FILE_UPLOAD'): ?>
                                    <div class="input-group mb-2">
                                        <input type="file" accept=".wav" class="form-control type_sound" onchange="return validasiEkstensi()" />
                                        <button class="btn btn-success btn-save btn-sm" id="<?= $konfigurasi_tambahan->id_config ?>" data-type="<?= $konfigurasi_tambahan->config_type ?>"><i class="fas fa-save fa-fw"></i></button>
                                    </div>
                                    <p class="mb-0">Digunakan: <b><?= empty($konfigurasi_tambahan->name_sound) ? 'Tidak ada' : $konfigurasi_tambahan->name_sound ?>
                                            <span><button play="<?= $konfigurasi_tambahan->value ?>" class="btn btn-sm btn-indigo btn-play-current-configurated" style="font-size: 8px; padding: 4px;"><i class="fas fa-play"></i></button></span></b></p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var audio = new Audio();

        $(document).on('click', '.btn-play-current-configurated', function() {
            audio = new Audio('<?= base_url('assets/audio/') ?>' + $(this).attr('play'));
            audio.play();
            $(this).html('<i class="fas fa-stop"></i>').attr('class', 'btn btn-sm btn-danger btn-stop-current-configurated');

            var thisel = $(this);
            audio.addEventListener("ended", function() {
                audio.currentTime = 0;
                thisel.html('<i class="fas fa-play"></i>').attr('class', 'btn btn-sm btn-indigo btn-play-current-configurated');
            });
        });

        $(document).on('click', '.btn-stop-current-configurated', function() {
            audio.pause();
            audio.currentTime = 0;
            $(this).html('<i class="fas fa-play"></i>').attr('class', 'btn btn-sm btn-indigo btn-play-current-configurated');
        });

        $(document).on('click', '.btn-save', function() {
            var id_config = $(this).attr('id');
            var type = $(this).attr('data-type');
            var file = $(this).parents('tr').find('input[type="file"]').prop('files')[0];

            if (!file) return alert('Pilih file .wav terlebih dahulu!');

            var form_data = new FormData();
            form_data.append('file', file);
            form_data.append('id_config', id_config);

            Swal.fire({
                title: "Uploading...",
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: "<?= base_url('konfigurasi_tambahan/update_action') ?>",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(resp) {
                    Swal.fire({
                        title: "Success!",
                        text: "File berhasil diupload",
                        icon: 'success',
                        timer: 1000
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Gagal menghubungi server', 'error');
                }
            });
        });
    });

    function validasiEkstensi() {
        var input = event.target;
        if (!input.files[0].type.match('audio/wav')) {
            alert('File harus berformat .wav');
            input.value = '';
            return false;
        }
        return true;
    }
</script>
<?= $this->endSection() ?>