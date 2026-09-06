<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA SETT_MAPEL</h1>
    <div class="row">
        <div class="col-xl-4 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title">Form Guru Mapel</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post">
                        <thead>
                            <table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
                                <tr>

                                    <td>Guru</td>
                                    <td style="width: 100%;">
                                        <select name="guru_id" class="form-control theSelect">
                                            <option value="">-- Pilih -- </option>
                                            <?php foreach ($guru_data as $key => $data) { ?>
                                                <option value="<?php echo $data->guru_id ?>"><?php echo $data->nama_guru ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Mapel</td>
                                    <td style="width: 100%;">
                                        <select name="mapel_id" class="form-control theSelect">
                                            <option value="">-- Pilih -- </option>
                                            <?php foreach ($mapel_data as $key => $data) { ?>
                                                <option value="<?php echo $data->mapel_id ?>"><?php echo $data->nama_mapel ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kelas</td>
                                    <td style="width: 100%;">
                                        <select name="kelas_id" class="form-control theSelect">
                                            <option value="">-- Pilih -- </option>
                                            <?php foreach ($kelas_data as $key => $data) { ?>
                                                <option value="<?php echo $data->kelas_id ?>"><?php echo $data->nama_kelas ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="hidden" name="sett_mapel_id" value="<?php echo $sett_mapel_id; ?>" />
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?php echo $button ?></button>
                                    </td>
                                </tr>
                        </thead>
                        </table>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-xl-8 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="table-basic-7">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title">List Guru Mapel</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Guru</th>
                                    <th>Mapel</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody><?php $no = 1;
                                    foreach ($sett_mapel_data as $sett_mapel) {
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?php echo $sett_mapel->nama_guru ?></td>
                                        <td><?php echo $sett_mapel->nama_mapel ?></td>
                                        <td><?php echo $sett_mapel->nama_kelas ?></td>
                                        <td><?php echo $sett_mapel->nama_tahun_ajaran ?></td>
                                        <td>
                                            <?php
                                            echo anchor(site_url('sett_mapel/delete/' . encrypt_url($sett_mapel->sett_mapel_id)), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
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