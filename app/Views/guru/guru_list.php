<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="modal fade" id="modal_import">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Import Data Guru</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-3">
					<a href="<?= base_url('assets/downloads/data_guru_format.xlsx') ?>" class="btn btn-warning"><i class="fas fa-download fa-fw"></i> Unduh Format Import</a>
				</div>
				<div class="form-group mb-3">
					<label class="form-label" for="upload_excel">Upload Import</label>
					<input type="file" class="form-control" name="file_excel" id="file_excel">
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				<button type="button" class="btn btn-warning btn-upload-excel"><i class="ace-icon fa fa-file-import"></i> Upload</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Photo <span id="cuts"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="photo_guru" style="max-width: 100%; border-radius:10px;" />
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Batal</a>
				<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Konfirmasi</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_preview_excel">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<form id="form_insert_data_excel" action="<?= base_url('guru/insert_all_from_excel') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title">Konfirmasi Import</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>NIP</th>
									<th>Nama Guru</th>
									<th>Jenis Kelamin</th>
									<th>Status Guru</th>
									<th>Alamat</th>
									<th>No HP</th>
									<th>Tempat Lahir</th>
									<th>Tanggal Lahir</th>
									<th>Password</th>
								</tr>
							</thead>
							<tbody id="preview_excel_wrapper"></tbody>
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

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA GURU</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data guru </h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="mb-3">
						<a href="<?= base_url('guru/create') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
						<div class="btn-group">
							<a href="<?= base_url('guru/excel') ?>" class="btn btn-success btn-sm"><i class="fas fa-file-export"></i> Export</a>
							<button type="button" class="btn btn-warning btn-sm import_data"><i class="fas fa-file-import"></i> Import</button>
						</div>
					</div>

					<form action="<?= base_url('guru/update_guru') ?>" method="POST">
						<div class="mb-3">
							<button type="submit" name="hapus" value="Y" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data terpilih?');"><i class="fa fa-trash"></i> Hapus Terpilih</button>
							<button type="submit" name="cetak" value="Y" class="btn btn-white btn-sm" formtarget="_blank"><i class="fa fa-print"></i> Cetak Kartu Terpilih</button>
						</div>

						<div class="table-responsive">
							<table id="data-table-default3" class="table table-bordered table-hover text-white align-middle">
								<thead>
									<tr>
										<th width="1%">No</th>
										<th width="1%"><input type='checkbox' id='checkAll'></th>
										<th>Photo</th>
										<th>NIP</th>
										<th>Nama Guru</th>
										<th>L/P</th>
										<th>Status</th>
										<th>Alamat</th>
										<th>No HP</th>
										<th>Tempat Lahir</th>
										<th>Tanggal Lahir</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1;
									foreach ($guru_data as $guru) : ?>
										<tr>
											<td><?= $no++ ?></td>
											<td><input type="checkbox" name="update[]" value="<?= $guru->guru_id ?>"></td>
											<td>
												<?php $foto = empty($guru->photo) ? 'default.png' : $guru->photo; ?>
												<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?= $foto ?>" data-nama_guru="<?= $guru->nama_guru ?>">
													<img src="<?= base_url('assets/img/guru/' . $foto) ?>" class="rounded h-30px my-n1 mx-n1" style="object-fit:cover; width:30px;" />
												</a>
											</td>
											<td><?= $guru->nip ?></td>
											<td><?= $guru->nama_guru ?></td>
											<td><?= $guru->jk_kelamin ?></td>
											<td><?= $guru->nama_status_guru ?? $guru->status_guru_id ?></td>
											<td><?= $guru->alamat ?></td>
											<td><?= $guru->no_hp ?></td>
											<td><?= $guru->tempat_lahir ?></td>
											<td><?= $guru->tanggal_lahir ?></td>
											<td class="text-center">
												<a href="<?= base_url('guru/cetak/' . encrypt_url($guru->guru_id)) ?>" target="_blank" class="btn btn-white btn-sm"><i class="fas fa-print"></i></a>
												<a href="<?= base_url('guru/update/' . encrypt_url($guru->guru_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
												<a href="<?= base_url('guru/delete/' . encrypt_url($guru->guru_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')"><i class="fas fa-trash-alt"></i></a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('#checkAll').change(function() {
		$('input[name="update[]"]').prop('checked', $(this).is(':checked'));
	});

	function upload_excel_preview() {
		$("#modal_import").modal('hide');
		Swal.fire({
			title: 'Loading',
			text: 'Sedang mengupload data',
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		var formData = new FormData();
		var file = $('#file_excel')[0].files[0];
		formData.append('file_excel', file);

		$.ajax({
			url: '<?= base_url('guru/preview_excel') ?>',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				// Cek apakah data masih berupa string (teks) atau sudah otomatis jadi Object
				var dt = typeof data === 'string' ? JSON.parse(data) : data;

				if (dt.status == 'ok') {
					Swal.close();
					$('#modal_preview_excel').modal('show');
					$('#preview_excel_wrapper').html(dt.data);
					if ($('.not-allowed-to-insert-excel').length >= $('#preview_excel_wrapper tr').length) {
						$('.btn-submit-import-excel').hide();
					} else {
						$('.btn-submit-import-excel').show();
					}
				} else {
					Swal.fire('Error', dt.message, 'error');
				}
				$('#file_excel').val('');
			},
			error: function() {
				Swal.fire('Error', 'Gagal mengupload data. Cek koneksi internet.', 'error');
				$('#file_excel').val('');
			}
		});
	}

	$(document).on('click', '.import_data', function() {
		$('#modal_import').modal('show');
	});

	$(document).on('click', '.btn-upload-excel', function() {
		if ($("#file_excel")[0].files.length === 0) {
			Swal.fire('Error!', 'Pilih File terlebih dahulu', 'error');
		} else {
			upload_excel_preview();
		}
	});

	$(document).on('click', '#view_gambar', function() {
		var nama_guru = $(this).data('nama_guru');
		var photo = $(this).data('photo');
		$('#modal-dialog #cuts').text(nama_guru);
		$('#modal-dialog #photo_guru').attr("src", "<?= base_url('assets/img/guru/') ?>" + photo);
		$('#modal-dialog #download').attr("href", "<?= base_url('guru/download/') ?>" + photo);
	});
</script>
<?= $this->endSection() ?>