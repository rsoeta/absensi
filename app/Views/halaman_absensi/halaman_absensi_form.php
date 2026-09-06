<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KELOLA DATA HALAMAN_ABSENSI</h4>
            </div>
            <div class="panel-body">
                <form action="<?= $action ?>" method="post">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width="200">Is Aktif</td>
                            <td><select name="is_aktif" class="form-control theSelect">
                                    <option value="Aktif" <?= $is_aktif == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Non Aktif" <?= $is_aktif == 'Non Aktif' ? 'selected' : '' ?>>Non Aktif</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Password Lock Screen</td>
                            <td><input type="password" class="form-control" name="password_lock_screen" placeholder="Password lock screen" />
                                <small style="color: red">(Biarkan kosong jika tidak diganti)</small>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="hidden" name="halaman_absensi_id" value="<?= $halaman_absensi_id ?>" />
                                <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".theSelect").select2();
    })
</script>
<?= $this->endSection() ?>