<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KELOLA DATA WAKTU ABSEN</h4>
            </div>
            <div class="panel-body">
                <form action="<?= $action ?>" method="post">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width='200'>Nama Hari</td>
                            <td><input type="text" class="form-control" name="nama_hari" value="<?= $nama_hari ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Jam Masuk Guru</td>
                            <td><input type="time" class="form-control" name="jam_masuk_guru" value="<?= $jam_masuk_guru ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Absen Terlambat Guru (Menit)</td>
                            <td><input type="number" min="0" class="form-control" name="absen_terlambat_guru" value="<?= $absen_terlambat_guru ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Jam Masuk Siswa</td>
                            <td><input type="time" class="form-control" name="jam_masuk_siswa" value="<?= $jam_masuk_siswa ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Absen Terlambat Siswa (Menit)</td>
                            <td><input type="number" min="0" class="form-control" name="absen_terlambat_siswa" value="<?= $absen_terlambat_siswa ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Jam Pulang Guru</td>
                            <td><input type="time" class="form-control" name="jam_pulang_guru" value="<?= $jam_pulang_guru ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Jam Pulang Siswa</td>
                            <td><input type="time" class="form-control" name="jam_pulang_siswa" value="<?= $jam_pulang_siswa ?>" required /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="waktu_absen" value="<?= $waktu_absen ?>" />
                                <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                <a href="<?= base_url('waktu_absen') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>