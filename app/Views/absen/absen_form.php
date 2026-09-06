<?php if ($this->uri->segment(2) == 'create' || $this->uri->segment(2) == 'create_action') {
    $level_id = decrypt_url($this->uri->segment(3));
} else {
    $level_id = decrypt_url($this->uri->segment(4));
} ?>

<div id="content" class="app-content">
    <div class="col-xl-12 ui-sortable">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">KELOLA DATA ABSEN</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"
                        data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i
                            class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i
                            class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="panel-body">

                <form action="<?php echo $action; ?>" method="post">
                    <thead>
                        <table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
                            <input type="hidden" class="form-control" name="level_id" id="level_id"
                                placeholder="level_id" value="<?php echo $level_id; ?>" />
                            <tr>
                                <td>User</td>
                                <td>
                                    <select name="user_id" class="form-control theSelect">
                                        <option value="">-- Pilih -- </option>
                                        <?php foreach ($user_data as $key => $data) { ?>
                                            <?php if ($user_id == $data->user_id) { ?>
                                                <option value="<?php echo $data->user_id ?>" selected>
                                                    <?php if ($data->level_id == '2') { ?>
                                                        Guru <?= nama_guru($data->user_id) ?>
                                                    <?php } else if ($data->level_id == '3') { ?>
                                                        Pegawai <?= nama_pegawai($data->user_id) ?>
                                                    <?php } else if ($data->level_id == '4') { ?>
                                                        <?= $data->username ?> - Siswa <?= nama_siswa($data->user_id) ?>
                                                    <?php } ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="<?php echo $data->user_id ?>">
                                                    <?php if ($data->level_id == '2') { ?>
                                                        Guru <?= nama_guru($data->user_id) ?>
                                                    <?php } else if ($data->level_id == '3') { ?>
                                                        Pegawai <?= nama_pegawai($data->user_id) ?>
                                                    <?php } else if ($data->level_id == '4') { ?>
                                                        <?= $data->username ?> - Siswa <?= nama_siswa($data->user_id) ?>
                                                    <?php } ?>
                                                </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width='200'>Tanggal </td>
                                <td>
                                    <input type="hidden" class="form-control" name="tanggal_lama" id="tanggal_lama"
                                        placeholder="Tanggal" value="<?php echo $tanggal_lama; ?>" />
                                    <input type="date" class="form-control" name="tanggal" id="tanggal"
                                        placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
                                </td>
                            </tr>
                            <input type="hidden" class="form-control" name="level_id" id="level_id"
                                placeholder="Tanggal" value="<?php echo $level_id; ?>" />
                            <tr>
                                <td width='200'>Jam Masuk </td>
                                <td><input type="time" class="form-control" name="jam_masuk" id="jam_masuk"
                                        placeholder="Jam Masuk" value="<?php echo $jam_masuk; ?>" /></td>
                            </tr>
                            <tr>
                                <td width='200'>Jam Pulang </td>
                                <td><input type="time" class="form-control" name="jam_pulang" id="jam_pulang"
                                        placeholder="Jam Pulang" value="<?php echo $jam_pulang; ?>" /></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td><input type="hidden" name="absen_id" value="<?php echo $absen_id; ?>" />
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i>
                                        <?php echo $button ?></button>
                                    <?php if ($level_id == "2") { ?>
                                        <a href="<?php echo site_url('absen/guru') ?>" class="btn btn-info"><i
                                                class="fas fa-undo"></i> Kembali</a>
                                    <?php } else if ($level_id == "3") { ?>
                                        <a href="<?php echo site_url('absen/pegawai') ?>" class="btn btn-info"><i
                                                class="fas fa-undo"></i> Kembali</a>
                                    <?php } else if ($level_id == "4") { ?>
                                        <a href="<?php echo site_url('absen/siswa') ?>" class="btn btn-info"><i
                                                class="fas fa-undo"></i> Kembali</a>
                                    <?php } ?>

                                </td>
                            </tr>
                    </thead>
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