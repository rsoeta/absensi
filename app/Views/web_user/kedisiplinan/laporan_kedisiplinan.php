	<!-- #modal-dialog -->
	<div class="modal fade" id="modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Photo <span id="cuts"></span></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<img src="" id="photo_siswa" width="100%" />
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
				</div>
			</div>
		</div>
	</div>

	<div id="content" class="app-content">
		<div class="row">
			<div class="col-xl-4 ui-sortable">
				<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">
					<div class="panel-heading ui-sortable-handle">
						<h4 class="panel-title">PILIH TANGGAL</h4>
						<div class="panel-heading-btn">
							<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
						</div>
					</div>
					<div class="panel-body">
						<form action="" method="get" enctype="multipart/form-data">
							<table class="table  table-bordered table-hover table-td-valign-middle">
								<tr>
									<td>Tahun Ajaran
									</td>
									<td>
										<select name="tahun_ajaran_id" class="form-control theSelect" required style="width: 100%;">
											<option value="">-- Pilih -- </option>
											<?php foreach ($tahun_ajaran_data as $data) { ?>
												<?php if ($tahun_ajar == $data->tahun_ajaran_id) { ?>
													<option value="<?php echo $data->tahun_ajaran_id ?>" selected><?php echo $data->nama_tahun_ajaran ?></option>
												<?php } else { ?>
													<option value="<?php echo $data->tahun_ajaran_id ?>"><?php echo $data->nama_tahun_ajaran ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View
											Ranking</button>
										<?php if (isset($_GET['tanggal'])) { ?>
											<a href="<?= base_url() ?>rekap_absen_kelas" class="btn btn-warning"><i class="fas fa-refresh" aria-hidden="true"></i> Reset</a>
										<?php } ?>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
			<div class="col-xl-8 ui-sortable">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title"></h4>
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
										<div class="box-body" style="overflow-x: scroll;">
											<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
												<thead>
													<tr>
														<th>No</th>
														<th>Photo</th>
														<th>Nama Siswa</th>
														<th>Kelas</th>
														<th>Total Hadir</th>
														<th>Total Alpha</th>
														<th>Total Bolos</th>
														<th>Total Izin</th>
														<th>Total Sakit</th>
														<th>Total Poin</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($dataRangking)): ?>
														<?php $no = 1; ?>
														<?php foreach ($dataRangking as $siswa): ?>
															<tr>
																<td><?php echo $no++; ?></td>
																<td>
																	<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" <?php if ($siswa->photo == '' || $siswa->photo == null) { ?> data-photo="default.png" <?php } else { ?> data-photo="<?php echo $siswa->photo ?>" <?php } ?> data-nama_siswa="<?php echo $siswa->nama_siswa ?>">
																		<?php if ($siswa->photo == '' || $siswa->photo == null) { ?>
																			<img src="<?= base_url() ?>assets/img/icon/default.png" class="rounded h-30px my-n1 mx-n1" />
																		<?php } else { ?>
																			<img src="<?= base_url() ?>assets/img/siswa/<?php echo $siswa->photo ?>" class="rounded h-30px my-n1 mx-n1" />
																		<?php } ?>
																	</a>
																</td>
																<td><?php echo htmlspecialchars($siswa->nama_siswa, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->nama_kelas, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_Hadir, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_Alpha, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_Bolos, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_Izin, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_Sakit, ENT_QUOTES, 'UTF-8'); ?></td>
																<td><?php echo htmlspecialchars($siswa->total_poin, ENT_QUOTES, 'UTF-8'); ?></td>
															</tr>
														<?php endforeach; ?>
													<?php else: ?>
														<tr>
															<td colspan="9" class="text-center">Tidak ada data siswa.</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).on('click', '#view_gambar', function() {
				var nama_siswa = $(this).data('nama_siswa');
				var photo = $(this).data('photo');
				$('#modal-dialog #cuts').text(nama_siswa);
				$('#modal-dialog #photo_siswa').attr("src", "../assets/img/siswa/" + photo);
				$('#modal-dialog #download').attr("href", "../siswa/download/" + photo);
			})
		</script>

		<script>
			$(document).ready(function() {
				$(".theSelect").select2();
			})
		</script>