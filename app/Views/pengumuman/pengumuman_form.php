<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="col-xl-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">KELOLA DATA PENGUMUMAN</h4>
            </div>
            <div class="panel-body">
                <form action="<?= $action ?>" method="post">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width='200'>Text</td>
                            <td><textarea class="form-control" rows="3" name="text" required><?= $text ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><select name="status" class="form-control theSelect">
                                    <option value="Aktif" <?= $status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Non Aktif" <?= $status == 'Non Aktif' ? 'selected' : '' ?>>Non Aktif</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="hidden" name="running_text_id" value="<?= $running_text_id ?>" />
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