<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KELOLA DATA IZIN / SAKIT</h4>
            </div>
            <div class="panel-body">
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-white align-middle">
                            <tr>
                                <td width='200'>User</td>
                                <td>
                                    <select name="user_id" class="form-control theSelect" required>
                                        <option value="">-- Pilih User --</option>
                                        <?php foreach ($user_data as $u) : ?>
                                            <option value="<?= $u->user_id ?>" <?= ($user_id == $u->user_id) ? 'selected' : '' ?>>
                                                <?= $u->username ?> - <?= $u->nama_lengkap ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>
                                    <input type="date" class="form-control" name="tanggal" required value="<?= $tanggal; ?>" />
                                    <input type="hidden" name="tanggal_lama" value="<?= $tanggal_lama; ?>" />
                                </td>
                            </tr>

                            <?php if ($button == 'Create') : ?>
                                <tr>
                                    <td>Surat Keterangan</td>
                                    <td>
                                        <input type="file" class="form-control" name="photo" id="photo" required onchange="validasiEkstensi()" accept=".jpg,.jpeg,.png,.pdf" />
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td>Surat Keterangan</td>
                                    <td>
                                        <?php if (!empty($photo)): ?>
                                            <div class="mb-2">
                                                <img src="<?= base_url('assets/img/izin/' . $photo) ?>" style="max-height: 150px; border-radius: 5px; border: 2px solid #555;" alt="Bukti Surat">
                                            </div>
                                        <?php endif; ?>
                                        <input type="hidden" name="photo_lama" value="<?= $photo ?>">
                                        <p class="text-warning mb-1"><small>Note: Pilih File Baru Jika Ingin Merubah Foto / Surat</small></p>
                                        <input type="file" class="form-control" name="photo" id="photo" onchange="validasiEkstensi()" accept=".jpg,.jpeg,.png,.pdf" />
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <select name="keterangan" class="form-control theSelect" required>
                                        <option value="">- Pilih -</option>
                                        <option value="Izin" <?= ($keterangan == 'Izin') ? 'selected' : '' ?>>Izin</option>
                                        <option value="Sakit" <?= ($keterangan == 'Sakit') ? 'selected' : '' ?>>Sakit</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Deskripsi Singkat</td>
                                <td>
                                    <textarea class="form-control" rows="3" name="deskripsi" required><?= $deskripsi; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="hidden" name="izin_sakit_id" value="<?= $izin_sakit_id; ?>" />
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                    <a href="<?= base_url('izin_sakit') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Pastikan library Select2 sudah terbaca
        if (typeof $.fn.select2 === 'function') {
            $(".theSelect").select2({
                placeholder: "-- Ketik Nama / NISN / NIP --",
                allowClear: true,
                width: '100%' // Agar lebar dropdown sejajar dengan form Bootstrap
            });
        } else {
            console.error("Library Select2 belum dimuat di template Anda.");
        }
    });

    function validasiEkstensi() {
        var inputFile = document.getElementById('photo');
        var pathFile = inputFile.value;
        var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        if (!ekstensiOk.exec(pathFile)) {
            alert('Silakan upload file yang memiliki ekstensi .jpeg / .jpg / .png / .pdf');
            inputFile.value = '';
            return false;
        }
    }
</script>

<?= $this->endSection() ?>