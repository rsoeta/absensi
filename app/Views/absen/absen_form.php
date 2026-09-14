<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="col-xl-12 ui-sortable">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1" data-init="true">

            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">KELOLA DATA ABSEN</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <form action="<?= $action; ?>" method="post">

                    <!-- Kumpulkan semua input hidden di luar tabel agar struktur HTML rapi -->
                    <input type="hidden" name="level_id" value="<?= $level_id; ?>" />
                    <input type="hidden" name="tanggal_lama" value="<?= isset($tanggal_lama) ? $tanggal_lama : '' ?>" />
                    <input type="hidden" name="absen_id" value="<?= $absen_id; ?>" />

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <tbody>
                                <tr>
                                    <td width="200">User</td>
                                    <td>
                                        <select name="user_id" class="form-control theSelect" required>
                                            <option value="">-- Pilih User --</option>
                                            <?php foreach ($user_data as $data) : ?>
                                                <option value="<?= $data->user_id ?>" <?= ($user_id == $data->user_id) ? 'selected' : '' ?>>
                                                    <?= $data->username ?> - <?= $data->nama_lengkap ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>
                                        <input type="date" class="form-control" name="tanggal" value="<?= $tanggal; ?>" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>
                                        <select name="keterangan" class="form-control" required>
                                            <option value="Hadir" <?= ($keterangan == 'Hadir') ? 'selected' : '' ?>>Hadir</option>
                                            <option value="Sakit" <?= ($keterangan == 'Sakit') ? 'selected' : '' ?>>Sakit</option>
                                            <option value="Izin" <?= ($keterangan == 'Izin') ? 'selected' : '' ?>>Izin</option>
                                            <option value="Alpa" <?= ($keterangan == 'Alpa') ? 'selected' : '' ?>>Alpa</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jam Masuk</td>
                                    <td>
                                        <input type="time" class="form-control" name="jam_masuk" value="<?= $jam_masuk; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Masuk</td>
                                    <td>
                                        <select name="status_masuk" class="form-control">
                                            <option value="">-- Kosong/Belum Tersedia --</option>
                                            <option value="Tepat Waktu" <?= ($status_masuk == 'Tepat Waktu') ? 'selected' : '' ?>>Tepat Waktu</option>
                                            <option value="Terlambat" <?= ($status_masuk == 'Terlambat') ? 'selected' : '' ?>>Terlambat</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jam Pulang</td>
                                    <td>
                                        <input type="time" class="form-control" name="jam_pulang" value="<?= $jam_pulang; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Pulang</td>
                                    <td>
                                        <select name="status_pulang" class="form-control">
                                            <option value="">-- Kosong/Belum Tersedia --</option>
                                            <option value="Tepat Waktu" <?= ($status_pulang == 'Tepat Waktu') ? 'selected' : '' ?>>Tepat Waktu</option>
                                            <option value="Terlambat" <?= ($status_pulang == 'Terlambat') ? 'selected' : '' ?>>Terlambat</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                        <?php
                                        // Menentukan link kembali secara dinamis berdasarkan level
                                        $link_kembali = base_url('absen/siswa');
                                        if ($level_id == 2) $link_kembali = base_url('absen/guru');
                                        if ($level_id == 3) $link_kembali = base_url('absen/pegawai');
                                        ?>
                                        <a href="<?= $link_kembali ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Bungkus dengan validasi agar tidak error jika library Select2 telat dimuat
        if (typeof $.fn.select2 === 'function') {
            $(".theSelect").select2();
        }
    });
</script>

<?= $this->endSection() ?>