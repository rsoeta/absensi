<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="row">
        <div class="col-xl-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">LAPORAN ABSEN MAPEL</h4>
                </div>
                <div class="panel-body">
                    <form action="<?= base_url('absen_mapel/view_laporan') ?>" method="post">
                        <table class="table table-bordered table-td-valign-middle text-white align-middle">
                            <tr>
                                <td width="200">
                                    Pilih Set Mapel <br>
                                    <small class="text-warning"><i>* Guru - Mapel - Kelas</i></small>
                                </td>
                                <td>
                                    <select name="set_mapel_id" class="form-control theSelect" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        <?php foreach ($absen_mapel_data as $absen_mapel) : ?>
                                            <option value="<?= $absen_mapel->set_mapel_id ?>">
                                                <?= $absen_mapel->nama_guru ?> - <?= $absen_mapel->nama_mapel ?> - <?= $absen_mapel->nama_kelas ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> Tampilkan Laporan</button>
                                </td>
                            </tr>
                        </table>
                    </form>
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