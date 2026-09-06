<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="modal fade" id="modal_import">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a style="width: 100%;padding:10px" class="btn btn-warning" href="<?= base_url('assets/downloads/data_kelas_format.xlsx') ?>"><i class="fa fa-download"></i> &nbsp;Download Format Import Siswa</a>
			</div>
			<div class="modal-body">
				<div class="form-group mb-3">
					<label class="form-label">Pilih Kelas</label>
					<select class="form-control" id="kelas">
						<option value="">-- Pilih --</option>
						<?php $db = \Config\Database::connect();
						foreach ($db->table('kelas')->get()->getResult() as $k) : ?>
							<option value="<?= $k->kelas_id ?>"><?= $k->nama_kelas ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group mb-3">
					<label class="form-label">Upload Import</label>
					<input type="file" class="form-control" name="file_excel" id="file_excel" accept=".xls,.xlsx">
				</div>
				<div class="form-group mb-3">
					<div class="form-check mb-2">
						<input class="form-check-input" type="checkbox" value="1" id="notified">
						<label class="form-check-label" for="notified">Siarkan Notifikasi Tambah Siswa?</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				<button type="button" class="btn btn-warning btn-upload-excel"><i class="ace-icon fa fa-file-import"></i> Upload</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_preview_excel">
	<div class="modal-dialog modal-xl">
		<div class="modal-content ">
			<form action="<?= base_url('siswa/insert_all_from_excel') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title">Konfirmasi Import</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>NISN</th>
									<th>Nama Siswa</th>
									<th>L/P</th>
									<th>Kelas</th>
									<th>Alamat</th>
									<th>Tempat Lahir</th>
									<th>Tanggal Lahir</th>
									<th>Wali Siswa</th>
									<th>No HP Wali</th>
									<th>Password</th>
									<th>Agama</th>
									<th>NIK</th>
									<th>PIP</th>
									<th>Asal Sekolah</th>
									<th>No WA</th>
									<th>Hobi</th>
									<th>Email</th>
									<th>Nama Ayah</th>
									<th>Pekerjaan Ayah</th>
									<th>Nama Ibu</th>
									<th>Pekerjaan Ibu</th>
									<th>KPS</th>
								</tr>
							</thead>
							<tbody id="preview_excel_wrapper"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="is_notified" id="is_notified" value="0">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-primary btn-submit-import-excel"><i class="ace-icon fa fa-file-import"></i> Import</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA SISWA</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Daftar Kelas</h4>
		</div>
		<div class="panel-body">
			<div class="mb-3">
				<button type="button" class="btn btn-warning btn-sm import_data"><i class="fas fa-file-import"></i> Import Siswa Massal</button>
			</div>
			<div class="table-responsive">
				<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th>Nama Kelas</th>
							<th width="15%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						$db = \Config\Database::connect();
						foreach ($kelas_data as $kelas) : ?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= $kelas->nama_kelas ?></td>
								<td>
									<a href="<?= base_url('siswa/daftar_siswa?kelas_id=' . $kelas->kelas_id) ?>" class="btn btn-success btn-sm">
										<i class="fas fa-eye"></i> <?= $db->table('siswa')->where('kelas_id', $kelas->kelas_id)->countAllResults() ?> Daftar Siswa
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	function upload_excel_preview() {
		$("#modal_import").modal('hide');
		Swal.fire({
			title: 'Loading',
			text: 'Sedang mengupload data...',
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		var formData = new FormData();
		formData.append('file_excel', $('#file_excel')[0].files[0]);
		formData.append('kelas', $('#kelas').val());
		formData.append('notified', $('#notified').is(':checked') ? true : null);

		$.ajax({
			url: '<?= base_url('siswa/preview_excel') ?>',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				// Perbaikan tipe data agar tidak memicu SyntaxError saat JSON di-parse ganda
				var dt = typeof data === 'string' ? JSON.parse(data) : data;

				if (dt.status == 'ok') {
					Swal.close();
					$('#modal_preview_excel').modal('show');
					$('#preview_excel_wrapper').html(dt.data);
					$('#is_notified').val(dt.notify);

					if ($('.not-allowed-to-insert-excel').length >= $('#preview_excel_wrapper tr').length) {
						$('.btn-submit-import-excel').hide();
					} else {
						$('.btn-submit-import-excel').show();
					}
				} else {
					Swal.fire('Error!', dt.message, 'error');
				}
				$('#file_excel').val('');
			},
			error: function() {
				Swal.fire('Error', 'Gagal menghubungi server.', 'error');
				$('#file_excel').val('');
			}
		});
	}

	$(document).on('click', '.import_data', function() {
		$('#modal_import').modal('show');
	});

	$(document).on('click', '.btn-upload-excel', function() {
		if ($("#file_excel")[0].files.length === 0) {
			Swal.fire('Error!', 'Pilih File Excel terlebih dahulu', 'error');
		} else if (!$('#kelas').val()) {
			Swal.fire('Error!', 'Pilih kelas terlebih dahulu', 'error');
		} else {
			upload_excel_preview();
		}
	});
</script>
<?= $this->endSection() ?>