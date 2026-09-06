<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA ABSEN_MAPEL_PEMINATAN</h1>
    <div class="row">
        <div class="col-md-4">
        <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Tanggal</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="box-body">
																<form method="get">
																	<thead>
																		<table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
																			<tr>
																				<td>Mapel Peminatan</td>
																				<td style="width: 70%;">
																					<select name="mapel_peminatan_id" class="form-control theSelect">
																							<option value="">-- Pilih -- </option>
																							<?php foreach ($mapel_peminatan as $key => $data) { ?>
																									<option value="<?php echo $data->id ?>"
																									<?php echo $mapel_peminatan_id == $data->id ? 'selected' : '' ?>
																									><?php echo $data->nama_mapel_peminatan ?></option>
																							<?php } ?>
																					</select>
																				</td>
																			</tr>
																			<tr>
																				<td>Tanggal</td>
																				<td><input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" /></td>
																			</tr>

																			<tr>
																				<td></td>
																				<td>
																					<button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Siswa</button>
																				</td>
																			</tr>
																	</thead>
																	</table>
																</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">List Siswa Mapel Peminatan tanggal <?= $tanggal ?></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="box-body">
                                    <div class="box-body" style="overflow-x: scroll; min-height: 30vh;">
                                        <?php
                                            if($mapel_peminatan_id) {
                                                ?>
                                                    <div class="table-responsive">
                                                        <table id="data-table-default" class="table table-bordered table-striped table-td-valign-middle text-white">
                                                            <thead>
                                                                <tr>
                                                                    <th >No</th>
                                                                    <th style="width: 50%;">Nama Siswa</th>
                                                                    <th >H</th>
                                                                    <th>A</th>
                                                                    <th>I</th>
                                                                    <th>S</th>
                                                                    <th>B</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <form action="<?= base_url() ?>absen_mapel_peminatan/create_action" method="POST">
                                                                    <input type="hidden" readonly name="tanggal" value="<?= $tanggal ?>">
                                                                    <input type="hidden" readonly name="mapel_peminatan_id" value="<?= $mapel_peminatan_id ?>">
                                                                    <?php $no = 1;
                                                                    foreach ($siswa_list as $key => $value) { ?>
                                                                        <tr>
                                                                            <td><?= $no++ ?></td>
                                                                            <td><?= $value->nama_siswa ?>
                                                                                <input type="hidden" readonly name="siswa_id[]" value="<?= $value->siswa_id ?>">
                                                                            </td>
                                                                            <td ><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="H"  <?= cekH_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?> required></td>
                                                                            <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="A" <?= cekA_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                                            <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="I" <?= cekI_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                                            <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="S" <?= cekS_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                                            <td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="B" <?= cekB_mapel_peminatan($mapel_peminatan_id, $tanggal, $value->siswa_id) ?>></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <tr>
                                                                        <td colspan="7" class="text-center">
                                                                            <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Simpan</button>
                                                                        </td>
                                                                    </tr>
                                                            </tbody>
                                                            </form>
                                                        </table>
                                                    </div>
                                                <?php
                                            } else {
																							?>
																								<div class="alert alert-primary fade show"><i class="fa fa-info-circle" aria-hidden="true"></i> Silahkan isi form kelas mapel peminatan terlebih dahulu untuk melihat daftar siswa</div>
																							<?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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