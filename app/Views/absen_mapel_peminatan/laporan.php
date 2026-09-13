<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="row">
        <div class="col-xl-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">LAPORAN ABSEN MAPEL PEMINATAN</h4>
                </div>
                <div class="panel-body">
                    <form action="<?= base_url('absen_mapel_peminatan/view_laporan') ?>" method="post">
                        <table class="table table-bordered table-td-valign-middle text-white align-middle">
                            <tr>
                                <td width="200">
                                    Set Mapel Peminatan <br>
                                    <small class="text-warning"><i>* Guru - Mapel</i></small>
                                </td>
                                <td>
                                    <select name="mapel_peminatan_id" class="form-control theSelect" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($mapel_peminatan_data as $mapel) : ?>
                                            <option value="<?= $mapel->id ?>">
                                                <?= $mapel->nama_guru ?> - <?= $mapel->nama_mapel_peminatan ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Absen</button>
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