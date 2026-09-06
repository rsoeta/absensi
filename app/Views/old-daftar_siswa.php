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

	<div class="modal fade" id="modal_preview_excel">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form_insert_data_excel" action="<?= base_url() ?>siswa/insert_all_from_excel" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h4 class="modal-title">Konfirmasi Import</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<td>NISN</td>
										<td>Nama Siswa</td>
										<td>Kelas</td>
										<td>Jenis Kelamin</td>
										<td>Alamat</td>
										<td>No HP</td>
										<td>Tempat Lahir</td>
										<td>Tanggal Lahir</td>
										<td>Password</td>
									</tr>
								</thead>
								<tbody id="preview_excel_wrapper">

								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
						<button type="submit" class="btn btn-primary btn-submit-import-excel"><i class="ace-icon fa fa-file-import"></i> Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_import">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a style="width: 100%;padding:10px" class="btn btn-warning" href="<?= base_url() ?>assets/downloads/data_kelas_format.xlsx"><i class="fa fa-download"></i> &nbsp;Download Format Import Siswa</a>
				</div>
				<div class="modal-body">
					<div class="form-group mb-3">
						<label class="form-label" for="file_excel">Upload Import2</label>
						<input type="file" class="form-control" name="file_excel" id="file_excel">
					</div>
					<input type="hidden" id="kelas" value="<?= $kelas_id ?>">
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<button type="button" class="btn btn-warning btn-upload-excel"><i class="ace-icon fa fa-file-import"></i> Upload</button>
				</div>
			</div>
		</div>
	</div>

	<div id="content" class="app-content">
		<h1 class="page-header">KELOLA DATA SISWA</h1>
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">List Data siswa </h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?= base_url() ?>siswa/update_kelas/<?= $kelas_id ?>" method="POST">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="box-body">
									<div class='row'>
										<div class='col-md-5'>
											<div style="padding-bottom: 10px;">
												<?php echo anchor(site_url('siswa/create'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data"'); ?>
												<?php echo anchor(site_url('siswa/export_excel?id=' . $kelas_id), '<i class="far fa-file-excel" aria-hidden="true"></i> Export Excel', 'class="btn btn-success btn-sm export_data"'); ?>
												<a href="<?= base_url('siswa') ?>" class="btn btn-info"><i class="fa fa-undo" aria-hidden="true"></i> Back</a>
											</div>
										</div>
										<div class="col-md-7">
											<div class="input-group">
												<select name="kelas_id" class="form-control theSelect mb-0">
													<option value="">-- Pilih -- </option>
													<?php foreach ($kelas as $key => $data) { ?>
														<option value="<?php echo $data->kelas_id ?>"><?php echo $data->nama_kelas ?></option>
													<?php } ?>
												</select>
												<button type="submit" name="pindah" value="Y" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Pindahkan Terpilih</button>
											</div>
											<div class="d-flex mt-2 justify-content-end">
												&nbsp;
												<div class="position-relative">
													<button type="submit" name="hapus" value="Y" class="btn btn-danger" style="padding-bottom: 2rem; padding-right: 1rem; padding-left: 1rem;" onclick="return confirm('Yakin hapus data ?');"><i class="fa fa-trash" aria-hidden="true"></i> Hapus Terpilih
													</button>
													<div class="d-flex" style="position: absolute;right: 4px;bottom: 8px;">
														<input type="checkbox" name="notify" id="notify" checked>
														<label for="notify">Dengan Notifikasi?</label>
													</div>
												</div>
												&nbsp;<button type="submit" name="cetak" value="Y" class="btn btn-white"><i class="fa fa-print" aria-hidden="true"></i> Cetak Kartu Terpilih</button>
											</div>
										</div>
									</div>
									<br>
									<div class="box-body" style="overflow-x: scroll;  white-space: nowrap; ">
										<table id="" class="table table-bordered table-hover table-td-valign-middle text-white" style="width: 100%;">
											<thead>
												<tr>
													<th style="width: 2%;">No</th>
													<th style="width: 2%;"><input type='checkbox' id='checkAll'> All</th>
													<th>Action</th>
													<th>Photo</th>
													<th>Nisn</th>
													<th>Nama Siswa</th>
													<th>Jk Kelamin</th>
													<th>Kelas</th>
													<th>Alamat</th>
													<th>Tempat Lahir</th>
													<th>Tanggal Lahir</th>
													<th>Umur</th>
													<th>Nama Wali Siswa</th>
													<th>No HP Wali Siswa</th>
													<th>Agama</th>
													<th>NIK</th>
													<th>Penerima PIP</th>
													<th>Asal Sekolah</th>
													<th>No Wa Siswa</th>
													<th>Hobi</th>
													<th>Email</th>
													<th>Nama Ayah</th>
													<th>Pekerjaan Ayah</th>
													<th>Nama Ibu</th>
													<th>Pekerjaan Ibu</th>
													<th>Penerima KPS</th>
												</tr>
											</thead>
											<tbody><?php $no = 1;
													foreach ($siswa_data as $siswa) {
													?>
													<tr>
														<td><?= $no++ ?></td>
														<td><input type="checkbox" name="update[]" value="<?= $siswa->siswa_id ?>"></td>
														<td style="text-align:center" width="130px">
															<?php
															echo anchor(site_url('siswa/cetak/' . encrypt_url($siswa->siswa_id)), '<i class="fas fa-print" aria-hidden="true"></i>', 'class="btn btn-white btn-sm read_data"');
															echo '  ';
															echo anchor(site_url('siswa/update/' . encrypt_url($siswa->siswa_id)), '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', 'class="btn btn-primary btn-sm update_data"');
															echo '  ';
															echo anchor(site_url('siswa/delete/' . encrypt_url($siswa->siswa_id)), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
															?>
														</td>
														<td>
															<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" <?php if ($siswa->photo == '' || $siswa->photo == null) { ?> data-photo="default.png" <?php } else { ?> data-photo="<?php echo $siswa->photo ?>" <?php } ?> data-nama_siswa="<?php echo $siswa->nama_siswa ?>">
																<?php if ($siswa->photo == '' || $siswa->photo == null) { ?>
																	<img src="<?= base_url() ?>assets/img/icon/default.png" class="rounded h-30px my-n1 mx-n1" />
																<?php } else { ?>
																	<img src="<?= base_url() ?>assets/img/siswa/<?php echo $siswa->photo ?>" class="rounded h-30px my-n1 mx-n1" />
																<?php } ?>
															</a>
														</td>
														<td><?php echo $siswa->nisn ?></td>
														<td><?php echo $siswa->nama_siswa ?></td>
														<td><?php echo $siswa->jk_kelamin ?></td>
														<td><?php echo $siswa->nama_kelas ?></td>
														<td><?php echo $siswa->alamat ?></td>
														<td><?php echo $siswa->tempat_lahir ?></td>
														<td><?php echo $siswa->tanggal_lahir ?></td>
														<td><?php echo hitung_umur($siswa->tanggal_lahir) ?></td>
														<td><?php echo $siswa->nama_wali_siswa ?></td>
														<td><?php echo $siswa->no_hp_wali_siswa ?></td>
														<td><?php echo $siswa->agama ?></td>
														<td><?php echo $siswa->nik ?></td>
														<td><?php echo $siswa->penerima_pip ?></td>
														<td><?php echo $siswa->asal_sekolah ?></td>
														<td><?php echo $siswa->no_wa_siswa ?></td>
														<td><?php echo $siswa->hobi ?></td>
														<td><?php echo $siswa->email ?></td>
														<td><?php echo $siswa->nama_ayah ?></td>
														<td><?php echo $siswa->pekerjaan_ayah ?></td>
														<td><?php echo $siswa->nama_ibu ?></td>
														<td><?php echo $siswa->pekerjaan_ibu ?></td>
														<td><?php echo $siswa->penerima_kps ?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
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
		<script type="text/javascript">
			$(document).ready(function() {
				$(".theSelect").select2();
				// Check/Uncheck ALl
				$('#checkAll').change(function() {
					if ($(this).is(':checked')) {
						$('input[name="update[]"]').prop('checked', true);
					} else {
						$('input[name="update[]"]').each(function() {
							$(this).prop('checked', false);
						});
					}
				});

				// Checkbox click
				$('input[name="update[]"]').click(function() {
					var total_checkboxes = $('input[name="update[]"]').length;
					var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

					if (total_checkboxes_checked == total_checkboxes) {
						$('#checkAll').prop('checked', true);
					} else {
						$('#checkAll').prop('checked', false);
					}
				});
			});
		</script>
		<script>
			function upload_excel_preview() {
				$("#modal_import").removeClass("in");
				$(".modal-backdrop").remove();
				$("#modal_import").hide();
				Swal.fire({
					title: 'Loading',
					text: 'Sedang mengupload data',
					type: 'info',
					allowOutsideClick: false,
					showConfirmButton: false
				});
				var formData = new FormData();
				var file = $('#file_excel')[0].files[0];
				var kelas = $('#kelas').val();
				formData.append('file_excel', file);
				formData.append('kelas', kelas);
				$.ajax({
					url: '<?php echo site_url('siswa/preview_excel') ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(data) {
						var dt = JSON.parse(data);
						if (dt.status == 'ok') {
							// trigger modal by javascript
							$('#modal_preview_excel').modal('show');
							$('#preview_excel_wrapper').html(dt.data);
							Swal.close()
							console.log($('.not-allowed-to-insert-excel').length)
							if ($('.not-allowed-to-insert-excel').length >= $('#preview_excel_wrapper tr').length) {
								$('.btn-submit-import-excel').hide();
							}
						} else {
							Swal.fire({
								title: 'Error!',
								text: dt.message,
								type: 'error',
								showConfirmButton: true,
								confirmButtonText: 'Close'
							});
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						if (XMLHttpRequest.readyState == 4) {
							// HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
							Swal.fire({
								title: 'Error',
								text: 'Gagal mengupload data',
								type: 'error',
								allowOutsideClick: false,
								showConfirmButton: true
							});
						} else if (XMLHttpRequest.readyState == 0) {
							Swal.fire({
								title: 'Error',
								text: 'Gagal mengupload data',
								type: 'error',
								allowOutsideClick: false,
								showConfirmButton: true
							});
						} else {

							Swal.fire({
								title: 'Error',
								text: 'Cek koneksi internet',
								type: 'error',
								allowOutsideClick: false,
								showConfirmButton: true
							});
						}
					}
				});
				$('#file_excel').val('');
			}

			$(document).on('click', '.import_data', function() {
				$('#modal_import').modal('show');
			})

			$(document).on('click', '.btn-upload-excel', function() {
				var fileny = $("#file_excel")[0].files.length;
				if (fileny === 0) {
					Swal.fire({
						title: 'Error!',
						text: 'Pilih File terlebih dahulu',
						type: 'error',
						icon: 'error',
						allowOutsideClick: false,
						showConfirmButton: true
					});
				} else {
					upload_excel_preview()
				}
			})
		</script>