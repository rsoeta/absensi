<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA WAKTU ABSEN</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data waktu_absen </h4>
        </div>
        <div class="panel-body">
            <!-- Tambahkan blok tombol ini -->
            <div style="padding-bottom: 15px;">
                <a href="<?= base_url('waktu_absen/create') ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-plus-square"></i> Tambah Data
                </a>
            </div>

            <div class="table-responsive">
                <table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Hari</th>
                            <th>Jam Masuk Guru</th>
                            <th>Telat Guru (Menit)</th>
                            <th>Jam Masuk Siswa</th>
                            <th>Telat Siswa (Menit)</th>
                            <th>Jam Pulang Guru</th>
                            <th>Jam Pulang Siswa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($waktu_absen_data as $waktu_absen): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $waktu_absen->nama_hari ?></td>
                                <td><?= $waktu_absen->jam_masuk_guru ?></td>
                                <td><?= $waktu_absen->absen_terlambat_guru ?></td>
                                <td><?= $waktu_absen->jam_masuk_siswa ?></td>
                                <td><?= $waktu_absen->absen_terlambat_siswa ?></td>
                                <td><?= $waktu_absen->jam_pulang_guru ?></td>
                                <td><?= $waktu_absen->jam_pulang_siswa ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('waktu_absen/update/' . encrypt_url($waktu_absen->waktu_absen)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
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