	<!-- #modal-dialog -->
	<div class="modal fade" id="modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Form Isian Waktu Panggilan <span id="cuts"></span></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<form id="download" action="" method="POST" target="_blank">
						<div class="form-group mb-3">
							<label class="form-label" for="upload_excel">Hari/Tanggal</label>
							<input type="date" class="form-control" name="tgl" required>
						</div>

						<div class="form-group mb-3">
							<label class="form-label" for="upload_excel">Waktu</label>
							<input type="time" class="form-control" name="waktu" required>
						</div>

						<div class="form-group mb-3">
							<label class="form-label" for="upload_excel">Tempat</label>
							<input type="text" class="form-control" name="tempat" required>
						</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>

					<button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<?php
	// $tgl_sekarang = date('2022-02-27');
	$tgl_sekarang = date('Y-m-d');
	$findtahunajaranbetweendatenow = $this->db->query("SELECT * FROM tahun_ajaran WHERE tgl_awal <= '$tgl_sekarang' AND tgl_akhir >= '$tgl_sekarang'")->row();

	?>

	<div id="content" class="app-content">
		<h1 class="page-header"> DAFTAR PANGGILAN</h1>
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php
					if ($findtahunajaranbetweendatenow) {
						echo $findtahunajaranbetweendatenow->nama_tahun_ajaran . ' (' . $findtahunajaranbetweendatenow->tgl_awal . ' - ' . $findtahunajaranbetweendatenow->tgl_akhir . ')';
					} else {
						echo "Tidak ada tahun ajaran berjalan pada tanggal " . $tgl_sekarang;
					}
					?>
				</h4>
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
								<div class="col-md-4">
								</div>

								<br>
								<div class="box-body" style="overflow-x: scroll; ">


									<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
										<thead>
											<tr>
												<th style="width: 5%;">No</th>
												<th>Nisn</th>
												<th>Siswa</th>
												<th>Kelas</th>
												<th>Wali Siswa</th>
												<th>Terakhir Dipanggil</th>
												<th>No HP Wali Siswa</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($surat_panggilan_data as $key => $value) {
											?>
												<tr>
													<td><?= $no++ ?></td>
													<td><?php echo $value->nisn ?></td>
													<td>
														<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?php echo $value->photo ?>" data-nama_siswa="<?php echo $value->nama_siswa ?>">
															<img src="<?= base_url() ?>assets/img/siswa/<?php echo $value->photo ?>" class="rounded h-30px my-n1 mx-n1" />
														</a>&nbsp
														<?php echo $value->nama_siswa ?>
													</td>
													<td><?php echo $value->nama_kelas ?></td>
													<td><?php echo $value->nama_wali_siswa ?></td>
													<td><?php echo $value->tgl_panggil_wali ?></td>
													<td><?php echo $value->no_hp_wali_siswa ?></td>
													<td><?php
														if ($value->status_sp == 1) {
															echo '<span class="badge bg-success">Sudah Dipanggil</span> (' . $value->last_updated . ')';
														} else {
															echo '<span class="badge bg-danger">Belum Dipanggil</span>';
														}
														?></td>
													<td>
														<?php

														if ($value->status_sp == 0) {
														?>
															<a href="<?= base_url() ?>surat_panggilan/update_status_sp/<?= encrypt_url($value->id_surat_panggilan) ?>" class="btn btn-success">
																<i class="fa fa-check"></i>
															<?php
														}

															?>
															<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-id_surat_panggilan="<?php echo $value->id_surat_panggilan ?>" class="btn btn-primary" href=""><i class="fas fa-print"></i></a>
													</td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
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

			$(document).on('click', '#view_gambar', function() {
				var id_surat_panggilan = $(this).data('id_surat_panggilan');
				$('#modal-dialog #download').attr("action", "cetak/surat_panggilan/" + id_surat_panggilan);
			})
		</script>