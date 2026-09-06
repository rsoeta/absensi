<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="modal fade" id="modal_import">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Import Data Pegawai</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-3">
					<a href="<?= base_url('assets/downloads/data_pegawai_format.xlsx') ?>" class="btn btn-warning"><i class="fas fa-download fa-fw"></i> Unduh Format Import</a>
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
				<img src="" id="photo_pegawai" style="max-width: 100%; border-radius:10px;" />
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
			<form id="form_insert_data_excel" action="<?= base_url('pegawai/insert_all_from_excel') ?>" method="post" enctype="multipart/form-data">
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
									<th>Nama Pegawai</th>
									<th>Jenis Kelamin</th>
									<th>Status Pegawai</th>
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
	<h1 class="page-header">KELOLA DATA PEGAWAI</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data pegawai </h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="mb-3">
						<a href="<?= base_url('pegawai/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
						<div class="btn-group">
							<a href="<?= base_url('pegawai/excel') ?>" class="btn btn-success btn-sm"><i class="fas fa-file-export"></i> Export</a>
							<button type="button" class="btn btn-warning btn-sm import_data"><i class="fas fa-file-import"></i> Import</button>
						</div>
						<a href="<?= base_url('pegawai/cetak_semua') ?>" target="_blank" class="btn btn-white btn-sm"><i class="fa fa-print"></i> Cetak Semua Kartu</a>
					</div>

					<div class="table-responsive">
						<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
							<thead>
								<tr>
									<th width="1%">No</th>
									<th>Photo</th>
									<th>NIP</th>
									<th>Nama Pegawai</th>
									<th>L/P</th>
									<th>Status</th>
									<th>Alamat</th>
									<th>No HP</th>
									<th>Tempat Lahir</th>
									<th>Tanggal Lahir</th>
									<th width="12%">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1;
								foreach ($pegawai_data as $pegawai) : ?>
									<tr>
										<td><?= $no++ ?></td>
										<td>
											<?php $foto = empty($pegawai->photo) ? 'default.png' : $pegawai->photo; ?>
											<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?= $foto ?>" data-nama_pegawai="<?= $pegawai->nama_pegawai ?>">
												<img src="<?= base_url('assets/img/pegawai/' . $foto) ?>" class="rounded h-30px my-n1 mx-n1" style="object-fit:cover; width:30px;" />
											</a>
										</td>
										<td><?= $pegawai->nip ?></td>
										<td><?= $pegawai->nama_pegawai ?></td>
										<td><?= $pegawai->jk_kelamin ?></td>
										<td><?= $pegawai->nama_status_pegawai ?></td>
										<td><?= $pegawai->alamat ?></td>
										<td><?= $pegawai->no_hp ?></td>
										<td><?= $pegawai->tempat_lahir ?></td>
										<td><?= $pegawai->tanggal_lahir ?></td>
										<td class="text-center">
											<a href="<?= base_url('pegawai/cetak/' . encrypt_url($pegawai->pegawai_id)) ?>" target="_blank" class="btn btn-white btn-sm"><i class="fas fa-print"></i></a>
											<a href="<?= base_url('pegawai/update/' . encrypt_url($pegawai->pegawai_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
											<a href="<?= base_url('pegawai/delete/' . encrypt_url($pegawai->pegawai_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
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
			url: '<?= base_url('pegawai/preview_excel') ?>',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				var dt = JSON.parse(data);
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
		var nama_pegawai = $(this).data('nama_pegawai');
		var photo = $(this).data('photo');
		$('#modal-dialog #cuts').text(nama_pegawai);
		$('#modal-dialog #photo_pegawai').attr("src", "<?= base_url('assets/img/pegawai/') ?>" + photo);
		$('#modal-dialog #download').attr("href", "<?= base_url('pegawai/download/') ?>" + photo);
	});
</script>
<?= $this->endSection() ?>