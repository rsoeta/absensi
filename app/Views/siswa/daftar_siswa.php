<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Photo <span id="cuts"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="photo_siswa" style="max-width:100%; border-radius:10px;" />
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA SISWA</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Daftar Siswa</h4>
		</div>
		<div class="panel-body">
			<form action="<?= base_url('siswa/update_kelas/' . $kelas_id) ?>" method="POST">
				<div class="row mb-3">
					<div class="col-md-5">
						<a href="<?= base_url('siswa/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
						<a href="<?= base_url('siswa/export_excel?id=' . $kelas_id) ?>" class="btn btn-success btn-sm"><i class="far fa-file-excel"></i> Export Excel</a>
						<a href="<?= base_url('siswa') ?>" class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Kembali</a>
					</div>
					<div class="col-md-7 text-end">
						<div class="input-group d-inline-flex w-auto mb-2">
							<select name="kelas_id" class="form-control theSelect mb-0" style="min-width: 150px;">
								<option value="">-- Pindah Kelas --</option>
								<?php foreach ($kelas as $k) : ?>
									<option value="<?= $k->kelas_id ?>"><?= $k->nama_kelas ?></option>
								<?php endforeach; ?>
							</select>
							<button type="submit" name="pindah" value="Y" class="btn btn-primary"><i class="fa fa-save"></i> Terapkan Pindah</button>
						</div>

						<div class="d-flex justify-content-end align-items-center mt-2">
							<div class="me-3">
								<input type="checkbox" name="notify" id="notify" checked>
								<label for="notify" class="text-white">Notifikasi Hapus?</label>
							</div>
							<button type="submit" name="hapus" value="Y" class="btn btn-danger me-2" onclick="return confirm('Yakin hapus data siswa terpilih?');"><i class="fa fa-trash"></i> Hapus Terpilih</button>
							<button type="submit" name="cetak" value="Y" class="btn btn-white" formtarget="_blank"><i class="fa fa-print"></i> Cetak Kartu</button>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover text-white align-middle" style="white-space: nowrap;">
						<thead>
							<tr>
								<th width="1%">No</th>
								<th width="1%"><input type='checkbox' id='checkAll'></th>
								<th>Action</th>
								<th>Photo</th>
								<th>NISN</th>
								<th>Nama Siswa</th>
								<th>L/P</th>
								<th>Kelas</th>
								<th>Alamat</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
								<th>Wali Siswa</th>
								<th>No HP Wali</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1;
							foreach ($siswa_data as $siswa) : ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><input type="checkbox" name="update[]" value="<?= $siswa->siswa_id ?>"></td>
									<td class="text-center">
										<a href="<?= base_url('siswa/cetak/' . encrypt_url($siswa->siswa_id)) ?>" target="_blank" class="btn btn-white btn-sm"><i class="fas fa-print"></i></a>
										<a href="<?= base_url('siswa/update/' . encrypt_url($siswa->siswa_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
									</td>
									<td>
										<?php
										// Memisahkan path default.png agar mengarah ke folder icon sesuai versi CI3
										$file_foto = empty($siswa->photo) ? 'default.png' : $siswa->photo;
										$url_foto = empty($siswa->photo)
											? base_url('assets/img/icon/default.png')
											: base_url('assets/img/siswa/' . $siswa->photo);
										?>
										<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?= $file_foto ?>" data-imgurl="<?= $url_foto ?>" data-nama_siswa="<?= $siswa->nama_siswa ?>">
											<img src="<?= $url_foto ?>" class="rounded h-30px my-n1 mx-n1" style="object-fit:cover; width:30px;" />
										</a>
									</td>
									<td><?= $siswa->nisn ?></td>
									<td><?= $siswa->nama_siswa ?></td>
									<td><?= $siswa->jk_kelamin ?></td>
									<td><?= $siswa->nama_kelas ?? 'N/A' ?></td>
									<td><?= $siswa->alamat ?></td>
									<td><?= $siswa->tempat_lahir ?></td>
									<td><?= $siswa->tanggal_lahir ?></td>
									<td><?= $siswa->nama_wali_siswa ?></td>
									<td><?= $siswa->no_hp_wali_siswa ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Pindahkan fungsi checkbox ke posisi teratas agar aman dari hambatan error library lain
		$('#checkAll').change(function() {
			$('input[name="update[]"]').prop('checked', $(this).is(':checked'));
		});

		$('input[name="update[]"]').click(function() {
			var total_checkboxes = $('input[name="update[]"]').length;
			var total_checkboxes_checked = $('input[name="update[]"]:checked').length;
			$('#checkAll').prop('checked', total_checkboxes_checked === total_checkboxes);
		});

		// Bungkus Select2 dengan validasi agar tidak menyebabkan Fatal Error jika belum dimuat
		if (typeof $.fn.select2 === 'function') {
			$(".theSelect").select2();
		} else {
			console.warn("Library Select2 tidak ditemukan di halaman ini.");
		}

		$(document).on('click', '#view_gambar', function() {
			var nama_siswa = $(this).data('nama_siswa');
			var imgurl = $(this).data('imgurl');
			var photo = $(this).data('photo');

			$('#modal-dialog #cuts').text(nama_siswa);
			$('#modal-dialog #photo_siswa').attr("src", imgurl);
			$('#modal-dialog #download').attr("href", "<?= base_url('siswa/download/') ?>" + photo);
		});
	});
</script>
<?= $this->endSection() ?>