<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA NOTIFIKASI SISWA</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data Notifikasi Siswa </h4>
        </div>
        <div class="panel-body">

            <!-- Alert Notifikasi -->
            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-dark">
                    <thead class="table-light">
                        <tr>
                            <th width="1%">No</th>
                            <th width="12%">Remark</th>
                            <th>Nama Siswa</th>
                            <th>Deskripsi</th>
                            <th width="10%">Status Baca</th>
                            <th width="15%">Tanggal</th>
                            <th width="1%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($notif_siswa_data as $notif) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php if ($notif->deksripsi == 'ditambahkan') : ?>
                                        <button style="width:100%" type="button" class="btn btn-sm btn-success btn-block"><i class="fa fa-plus"></i> Ditambahkan</button>
                                    <?php else : ?>
                                        <button style="width:100%" type="button" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"></i> Dihapus</button>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= $notif->nama_siswa ?></td>
                                <td><?= $notif->nama_siswa ?> Berhasil <?= $notif->deksripsi ?></td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white"><i class="fas fa-check-double"></i> <?= $notif->status_baca ?></span>
                                </td>
                                <td><?= date('d F Y H:i:s', strtotime($notif->tanggal)) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('notif_siswa/delete/' . encrypt_url($notif->notif_siswa_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus riwayat notifikasi ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
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