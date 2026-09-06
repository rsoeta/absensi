<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA HARI LIBUR</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data hari_libur</h4>
        </div>
        <div class="panel-body">
            <div class="mb-3">
                <a href="<?= base_url('hari_libur/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
            </div>
            <div class="table-responsive">
                <table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($hari_libur_data as $hari_libur): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $hari_libur->tanggal ?></td>
                                <td><?= $hari_libur->keterangan ?></td>
                                <td class="text-center" width="150px">
                                    <a href="<?= base_url('hari_libur/update/' . encrypt_url($hari_libur->hari_libur_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="<?= base_url('hari_libur/delete/' . encrypt_url($hari_libur->hari_libur_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')"><i class="fas fa-trash-alt"></i></a>
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