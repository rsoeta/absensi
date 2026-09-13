<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA ABSEN MAPEL PEMINATAN</h1>
    <div class="row">
        <!-- Panel Form -->
        <div class="col-md-4">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Filter Mapel & Tanggal</h4>
                </div>
                <div class="panel-body">
                    <form method="get">
                        <table class="table table-bordered table-td-valign-middle text-white">
                            <tr>
                                <td>Mapel Peminatan</td>
                                <td>
                                    <select name="mapel_peminatan_id" class="form-control theSelect" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($mapel_peminatan as $data) : ?>
                                            <option value="<?= $data->id ?>" <?= $mapel_peminatan_id == $data->id ? 'selected' : '' ?>>
                                                <?= $data->nama_mapel_peminatan ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td><input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>" required /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> Tampilkan Siswa</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Daftar Siswa -->
        <div class="col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Daftar Siswa (Tanggal: <?= date('d M Y', strtotime($tanggal)) ?>)</h4>
                </div>
                <div class="panel-body">
                    <?php if ($mapel_peminatan_id) : ?>
                        <div class="table-responsive">
                            <form action="<?= base_url('absen_mapel_peminatan/create_action') ?>" method="POST">
                                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                                <input type="hidden" name="mapel_peminatan_id" value="<?= $mapel_peminatan_id ?>">

                                <table class="table table-bordered table-hover text-white align-middle text-center">
                                    <thead class="table-light text-dark">
                                        <tr>
                                            <th>No</th>
                                            <th class="text-start">Nama Siswa</th>
                                            <th>H</th>
                                            <th>A</th>
                                            <th>I</th>
                                            <th>S</th>
                                            <th>B</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($siswa_list)): ?>
                                            <tr>
                                                <td colspan="7" class="text-warning">Belum ada siswa yang mendaftar di Mapel Peminatan ini.</td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php $no = 1;
                                        foreach ($siswa_list as $key => $value) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td class="text-start">
                                                    <?= $value->nama_siswa ?>
                                                    <input type="hidden" name="siswa_id[]" value="<?= $value->siswa_id ?>">
                                                </td>
                                                <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="H" <?= cekH_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?> required></td>
                                                <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="A" <?= cekA_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="I" <?= cekI_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="S" <?= cekS_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="B" <?= cekB_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if (!empty($siswa_list)): ?>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Simpan Absen</button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-primary"><i class="fa fa-info-circle"></i> Silakan pilih Mapel Peminatan untuk menampilkan data siswa.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".theSelect").select2();
    });
</script>

<?= $this->endSection() ?>