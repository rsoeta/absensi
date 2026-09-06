<div id="content" class="app-content">
	<h1 class="page-header">Absen Mapel</h1>
	<div class="row">
		<div class="col-xl-5 ui-sortable">
			<div class="panel panel-inverse" data-sortable-id="table-basic-1">
				<div class="panel-heading ui-sortable-handle">
					<h4 class="panel-title">Form Absen Mapel</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<form method="get">
						<thead>
							<table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
								<tr>
									<td>Kelas - Mapel</td>
									<td style="width: 70%;">
										<select name="m" class="form-control theSelect" required>
											<option value="">-- Pilih -- </option>
											<?php
											foreach ($sett_mapel_data as $sett_mapel) { ?>
												<?php if (isset($_GET['m'])) { ?>
													<?php if ($_GET['m'] == $sett_mapel->sett_mapel_id) { ?>
														<option value="<?php echo $sett_mapel->sett_mapel_id ?>" selected><?= $sett_mapel->nama_kelas ?> - <?= $sett_mapel->nama_mapel ?></option>
													<?php } else { ?>
														<option value="<?= $sett_mapel->sett_mapel_id ?>"><?= $sett_mapel->nama_kelas ?> - <?= $sett_mapel->nama_mapel ?> </option>
													<?php } ?>
												<?php } else { ?>
													<option value="<?= $sett_mapel->sett_mapel_id ?>"><?= $sett_mapel->nama_kelas ?> - <?= $sett_mapel->nama_mapel ?> </option>
												<?php } ?>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Tanggal</td>
									<td><input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" <?php if (isset($_GET['tanggal'])) { ?> value="<?= $_GET['tanggal'] ?>" <?php } ?> /></td>
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
		<div class="col-xl-7 ui-sortable">
			<div class="panel panel-inverse" data-sortable-id="table-basic-7">
				<div class="panel-heading ui-sortable-handle">
					<h4 class="panel-title">List Siswa</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<div class="panel-body">

					<?php if (isset($_GET['m']) && isset($_GET['tanggal'])) { ?>

						<?php
						$set_mapel_id = $_GET['m'];
						$kelas = $this->db->query("SELECT * from sett_mapel where sett_mapel_id ='$set_mapel_id'")->row();
						$kelas_id = $kelas->kelas_id;
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
									<form action="<?= base_url() ?>absen_mapel/doInput" method="POST">
										<input type="hidden" readonly name="tanggal" value="<?= $_GET['tanggal'] ?>">
										<input type="hidden" readonly name="sett_mapel_id" value="<?= $_GET['m'] ?>">
										<?php $data = $this->db->query("SELECT * from siswa where kelas_id ='$kelas_id'")->result();  ?>
										<?php $no = 1;
										foreach ($data as $key => $value) { ?>
											<tr>
												<td><?= $no++ ?></td>
												<td><?= $value->nama_siswa ?>
													<input type="hidden" readonly name="siswa_id[]" value="<?= $value->siswa_id ?>">
												</td>
												<td ><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="H"  <?= cekH($_GET['m'], $_GET['tanggal'], $value->siswa_id) ?> required></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="A" <?= cekA($_GET['m'], $_GET['tanggal'], $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="I" <?= cekI($_GET['m'], $_GET['tanggal'], $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="S" <?= cekS($_GET['m'], $_GET['tanggal'], $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" id="" value="B" <?= cekB($_GET['m'], $_GET['tanggal'], $value->siswa_id) ?>></td>
											</tr>
										<?php } ?>
										<tr>
											<td colspan="6" class="text-center">
												<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Simpan</button>
												<a href="<?php echo site_url('absen_mapel') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
											</td>
										</tr>
								</tbody>
								</form>
							</table>
						</div>
					<?php } else { ?>
						<div class="alert alert-primary fade show"><i class="fa fa-info-circle" aria-hidden="true"></i> Silahkan isi form kelas mapel terlebih dahulu untuk melihat daftar siswa</div>
					<?php } ?>
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