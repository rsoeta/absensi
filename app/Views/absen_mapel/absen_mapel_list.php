<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA ABSEN MAPEL</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data Absen Mapel</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="box-body">
                            <div class='row'>
                                <div class='col-md-9 mb-3'>
                                    <a href="<?= base_url('absen_mapel/create') ?>" class="btn btn-danger btn-sm tambah_data">
                                        <i class="fas fa-plus-square"></i> Absen Siswa
                                    </a>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
                                    <thead class="table-light text-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Guru</th>
                                            <th>Kelas - Mapel</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <?php if (session()->get('level_id') != 1) : ?>
                                                <th>Action</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($absen_mapel_data as $absen_mapel) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $absen_mapel->nama_guru ?></td>
                                                <td><?= $absen_mapel->nama_kelas ?> - <?= $absen_mapel->nama_mapel ?></td>
                                                <td><?= $absen_mapel->tanggal ?></td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        <?= ketAbsenMapel($absen_mapel->set_mapel_id, $absen_mapel->tanggal) ?>
                                                    </ul>
                                                </td>
                                                <?php if (session()->get('level_id') != 1) : ?>
                                                    <td>
                                                        <a href="<?= base_url('absen_mapel/delete/' . encrypt_url($absen_mapel->set_mapel_id) . '/' . encrypt_url($absen_mapel->tanggal)) ?>" class="btn btn-danger btn-sm delete_data" onclick="return confirm('Yakin ingin menghapus data absen tanggal ini?')">
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
    </div>
</div>

<?= $this->endSection() ?>