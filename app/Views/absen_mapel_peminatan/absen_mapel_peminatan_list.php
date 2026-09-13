<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA ABSEN MAPEL PEMINATAN</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data Absen Mapel Peminatan</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="<?= base_url('absen_mapel_peminatan/create') ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-plus-square"></i> Absen Siswa
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Guru</th>
                                    <th>Mapel Peminatan</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <?php if (session()->get('level_id') != 1) : ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($absen_mapel_peminatan_data as $absen) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $absen->nama_guru ?></td>
                                        <td><?= $absen->nama_mapel_peminatan ?></td>
                                        <td><?= $absen->tanggal ?></td>
                                        <td>
                                            <ul class="mb-0 ps-3">
                                                <?= ketAbsenMapelPeminatan($absen->mapel_peminatan_id, $absen->tanggal) ?>
                                            </ul>
                                        </td>
                                        <?php if (session()->get('level_id') != 1) : ?>
                                            <td>
                                                <a href="<?= base_url('absen_mapel_peminatan/delete/' . encrypt_url($absen->mapel_peminatan_id) . '/' . encrypt_url($absen->tanggal)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>