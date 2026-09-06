<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">LAPORAN ABSEN GURU</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">

				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
					<thead>
						<table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
							<tr>
								<td width='200'>Guru </td>
								<td>
									<select name="user_id" class="form-control theSelect">
										<option value="">-- Pilih --</option>
										<option value="semua_data">-- Semua Guru --</option>
										<?php foreach ($user_data as $key => $data) { ?>
											<?php if ($user_id == $data->user_id) { ?>
												<option value="<?php echo $data->user_id ?>" selected>
													<?php if ($data->level_id == '2') { ?>
														Guru <?= nama_guru($data->user_id) ?>
													<?php } else if ($data->level_id == '3') { ?>
														Pegawai <?= nama_pegawai($data->user_id) ?>
													<?php } else if ($data->level_id == '4') { ?>
														Siswa <?= nama_siswa($data->user_id) ?>
													<?php } ?>
												</option>
											<?php } else { ?>
												<option value="<?php echo $data->user_id ?>">
													<?php if ($data->level_id == '2') { ?>
														Guru <?= nama_guru($data->user_id) ?>
													<?php } else if ($data->level_id == '3') { ?>
														Pegawai <?= nama_pegawai($data->user_id) ?>
													<?php } else if ($data->level_id == '4') { ?>
														Siswa <?= nama_siswa($data->user_id) ?>
													<?php } ?>
												</option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>

							</tr>
							<tr>
								<td width='200'>Bulan </td>
								<td>
									<select name="bulan" id="bulan" class="form-control theSelect">
										<option value="">-- Pilih --</option>
										<option value="1" <?php echo $bulan == '1' ? 'selected' : 'null' ?>>Januari</option>
										<option value="2" <?php echo $bulan == '2' ? 'selected' : 'null' ?>>Februari</option>
										<option value="3" <?php echo $bulan == '3' ? 'selected' : 'null' ?>>Maret</option>
										<option value="4" <?php echo $bulan == '4' ? 'selected' : 'null' ?>>April</option>
										<option value="5" <?php echo $bulan == '5' ? 'selected' : 'null' ?>>Mei</option>
										<option value="6" <?php echo $bulan == '6' ? 'selected' : 'null' ?>>Juni</option>
										<option value="7" <?php echo $bulan == '7' ? 'selected' : 'null' ?>>Juli</option>
										<option value="8" <?php echo $bulan == '8' ? 'selected' : 'null' ?>>Agustus</option>
										<option value="9" <?php echo $bulan == '9' ? 'selected' : 'null' ?>>September</option>
										<option value="10" <?php echo $bulan == '10' ? 'selected' : 'null' ?>>Oktober</option>
										<option value="11" <?php echo $bulan == '11' ? 'selected' : 'null' ?>>November</option>
										<option value="12" <?php echo $bulan == '12' ? 'selected' : 'null' ?>>Desember</option>
									</select>
								</td>
							</tr>
							<tr>
								<td width='200'>Tahun </td>
								<td>
									<select name="tahun" id="tahun" class="form-control theSelect">
										<option value="">-- Pilih -- </option>
										<?php foreach ($tahun_data as $key => $data) { ?>
											<?php if ($tahun == $data->tahun) { ?>
												<option value="<?php echo $data->tahun ?>" selected><?php echo $data->tahun ?></option>
											<?php } else { ?>
												<option value="<?php echo $data->tahun ?>"><?php echo $data->tahun ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							<tr>
								<td></td>
								<td>
									<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?php echo $button ?></button>
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