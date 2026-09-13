<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">DATA ABSEN MAPEL SISWA</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Riwayat Kehadiran Anda</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Kelas - Mapel</th>
                            <th>Tanggal Pertemuan</th>
                            <th>Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($absen_mapel_data as $value) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $value->nama_guru ?></td>
                                <td><?= $value->nama_kelas ?> - <?= $value->nama_mapel ?></td>
                                <td><?= $value->tanggal ?></td>
                                <td>
                                    <?php
                                    if ($value->keterangan == 'H') echo '<span class="badge bg-success">Hadir</span>';
                                    else if ($value->keterangan == 'I') echo '<span class="badge bg-info">Izin</span>';
                                    else if ($value->keterangan == 'S') echo '<span class="badge bg-warning">Sakit</span>';
                                    else if ($value->keterangan == 'B') echo '<span class="badge bg-primary">Bolos</span>';
                                    else if ($value->keterangan == 'A') echo '<span class="badge bg-danger">Alpha</span>';
                                    else echo $value->keterangan;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>