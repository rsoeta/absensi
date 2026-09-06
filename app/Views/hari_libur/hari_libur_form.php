<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KELOLA DATA HARI LIBUR</h4>
            </div>
            <div class="panel-body">
                <form action="<?= $action ?>" method="post">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width='200'>Tanggal</td>
                            <td><input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>" required /></td>
                        </tr>
                        <tr>
                            <td width='200'>Keterangan</td>
                            <td><textarea class="form-control" rows="3" name="keterangan" required><?= $keterangan ?></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="hari_libur_id" value="<?= $hari_libur_id ?>" />
                                <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                <a href="<?= base_url('hari_libur') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>