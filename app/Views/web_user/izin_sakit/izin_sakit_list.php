	<!-- #modal-dialog -->
	<div class="modal fade" id="modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Photo <span id="cuts"></span></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<iframe src="" id="file_attac" frameborder="0" style="width: 100%;height:450px"></iframe>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
				</div>
			</div>
		</div>
	</div>

	<!-- #modal-dialog2 -->
	<div class="modal fade" id="modal-dialog2">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Update Status</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<form method="POST" action="<?= base_url() ?>izin_sakit/update_status">
						<input type="hidden" name="izin_sakit_id" id="izin_sakit_id" value="" />
						<div class="form-group">
							<select style="color: black; border-color:grey" name="status" id="status" class="form-control" required autofocus>
								<option value="">--Pilih--</option>
								<option value="Approved">Approved</option>
								<option value="Reject">Reject</option>
							</select>
						</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<button class="btn btn-success"> Update Status</button>
				</div>
			</div>
		</div>
	</div>

	<!-- #modal-dialog2 -->
	<div class="modal fade" id="modal-dialog3">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Informasi</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<table style="color: white" class="table table-bordered">
						<tr>
							<td width="25%">Nisn</td>
							<td><span id="modal_nisn"></span></td>
						</tr>
						<tr>
							<td>Nama Siswa</td>
							<td><span id="modal_nama_siswa"></span></td>
						</tr>
						<tr>
							<td>Jenis Kelamin</td>
							<td><span id="modal_jenis_kelamin"></span></td>
						</tr>
						<tr>
							<td>Kelas</td>
							<td><span id="modal_kelas"></span></td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td><span id="modal_alamat"></span></td>
						</tr>
						<tr>
							<td>Tempat Lahir</td>
							<td><span id="modal_tempat_lahir"></span></td>
						</tr>
						<tr>
							<td>Tanggal Lahir</td>
							<td><span id="modal_tanggal_lahir"></span></td>
						</tr>
						<tr>
							<td>Nama Wali Siswa</td>
							<td><span id="modal_wali"></span></td>
						</tr>
						<tr>
							<td>No HP Wali Siswa</td>
							<td><span id="modal_hp"></span></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</div>

	<div id="content" class="app-content">
		<h1 class="page-header">DATA IZIN SAKIT</h1>
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"> </h4>
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
								<div class='row'>
									<div class='col-md-9'>
										<div style="padding-bottom: 10px;">
											<?php echo anchor(site_url('Dashboard_user/create_izin_sakit'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data"'); ?>
										</div>
									</div>
								</div>
								<div class="box-body" style="overflow-x: scroll; ">
									<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
										<thead>
											<tr>
												<th>No</th>
												<th>Nama</th>
												<th>Level</th>
												<th>Tanggal</th>
												<th>Surat Keterangan</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Deskripsi</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody><?php $no = 1;
												foreach ($izin_sakit_data as $izin_sakit) {
												?>
												<tr>
													<td><?= $no++ ?></td>
													<!-- nama user -->
													<?php if ($izin_sakit->level_id == '1') { ?>
														<td>Admin Aplikasi</td>
													<?php } else if ($izin_sakit->level_id == '2') { ?>
														<td><?= nama_guru($izin_sakit->user_id) ?>

														</td>
													<?php } else if ($izin_sakit->level_id == '3') { ?>
														<td><?= nama_pegawai($izin_sakit->user_id) ?></td>
													<?php } else if ($izin_sakit->level_id == '4') { ?>
														<td><?= nama_siswa($izin_sakit->user_id) ?>
															<a id="view_data" href="#modal-dialog3" data-bs-toggle="modal" data-user_id="<?php echo $izin_sakit->user_id ?>">
																<i class="fas fa-info-circle"></i>
															</a>
														</td>
													<?php }  ?>


													<?php if ($izin_sakit->level_id == '1') { ?>
														<td>Admin Aplikasi</td>
													<?php } else if ($izin_sakit->level_id == '2') { ?>
														<td>Guru</td>
													<?php } else if ($izin_sakit->level_id == '3') { ?>
														<td>Pegawai</td>
													<?php } else if ($izin_sakit->level_id == '4') { ?>
														<td>Siswa</td>
													<?php }  ?>
													<td><?php echo $izin_sakit->tanggal ?></td>
													<td class="with-img">
														<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-file="<?php echo $izin_sakit->photo ?>">
															<img src="<?= base_url() ?>assets/img/izin/default.png" class="rounded h-30px my-n1 mx-n1" />
														</a>
													</td>
													<td><?php echo $izin_sakit->keterangan ?></td>
													<?php if ($izin_sakit->status == 'Waiting') { ?>
														<td><span class="badge bg-default rounded-pill">Waiting</span></td>
													<?php } else if ($izin_sakit->status == 'Approved') { ?>
														<td><span class="badge bg-success rounded-pill">Approved</span></td>
													<?php } else { ?>
														<td><span class="badge bg-danger rounded-pill">Reject</span></td>
													<?php } ?>

													<td><?php echo $izin_sakit->deskripsi ?></td>
													<td style="text-align:center">
														<?php if ($izin_sakit->status == 'Waiting') { ?>
															<?php
															echo anchor(site_url('Dashboard_user/update_izin_sakit/' . encrypt_url($izin_sakit->izin_sakit_id)), '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', 'class="btn btn-primary btn-sm update_data"');
															echo '  ';
															echo anchor(site_url('Dashboard_user/delete_izin_sakit/' . encrypt_url($izin_sakit->izin_sakit_id)), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
															?>
														<?php } else { ?>
															<button disabled class="btn btn-primary btn-sm update_data"><i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
															<button disabled class="btn btn-danger btn-sm delete_data" delete=""><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
														<?php } ?>

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
		</div>
		<script>
			$(document).ready(function() {
				$(".theSelect").select2();
			})
		</script>

		<script type="text/javascript">
			$(document).on('click', '#view_gambar', function() {
				var file = $(this).data('file');
				$('#modal-dialog #file_attac').attr("src", "../assets/img/izin/" + file);
				$('#modal-dialog #download').attr("href", "download_izin_sakit/" + file);
			})

			$(document).on('click', '#view_data', function() {
				var user_id = $(this).data('user_id');
				$.ajax({
					url: '<?= base_url() ?>izin_sakit/detail_siswa',
					method: 'POST',
					data: {
						user_id: user_id
					},
					dataType: 'json',
					success: function(result) {
						$('#modal_nisn').text(result['nisn']);
						$('#modal_nama_siswa').text(result['nama_siswa']);
						$('#modal_jenis_kelamin').text(result['jk_kelamin']);
						$('#modal_kelas').text(result['nama_kelas']);
						$('#modal_alamat').text(result['alamat']);
						$('#modal_tempat_lahir').text(result['tempat_lahir']);
						$('#modal_tanggal_lahir').text(result['tanggal_lahir']);
						$('#modal_wali').text(result['nama_wali_siswa']);
						$('#modal_hp').text(result['no_hp_wali_siswa']);
					}
				});
			})
			$(document).on("click", ".open-AddModal", function() {
				var file = $(this).data('file');
				$('#modal-dialog #file_attac').attr("src", "assets/img/izin/" + file);
				$('#modal-dialog #download').attr("href", "izin_sakit/download/" + file);
			});
		</script>