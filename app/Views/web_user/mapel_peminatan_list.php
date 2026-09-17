<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">DATA MAPEL PEMINATAN</h1>

    <!-- Notifikasi Jika Siswa Mencoba Nakal Akses URL Edit/Hapus -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <b><i class="fas fa-ban"></i> Akses Ditolak:</b> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Daftar Mapel Peminatan</h4>
        </div>
        <div class="panel-body">
            <div class="note note-info mb-3">
                <i class="fas fa-info-circle"></i> <b>Informasi:</b> Anda masuk sebagai Siswa. Anda hanya diizinkan untuk melihat daftar Mata Pelajaran Peminatan ini.
            </div>

            <div class="table-responsive">
                <table id="tabel-mapel" class="table table-bordered table-hover text-white align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Mapel Peminatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($mapel_peminatan_data as $mapel) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold"><?= $mapel->nama_mapel_peminatan ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if (typeof $.fn.DataTable === 'function') {
            $('#tabel-mapel').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>