<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- #modal-dialog (Preview Foto Bukti Izin) -->
<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Bukti Surat / Foto</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="file_attac" alt="Bukti Izin" style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 10px;">
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
			</div>
		</div>
	</div>
</div>

<!-- #modal-dialog2 (Update Status Approval) -->
<div class="modal fade" id="modal-dialog2">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="<?= base_url('izin_sakit/update_status') ?>">
				<div class="modal-header">
					<h4 class="modal-title">Update Status Approval</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="izin_sakit_id" id="modal_izin_sakit_id" value="" />
					<div class="form-group mb-3">
						<label class="form-label">Ubah Status Menjadi:</label>
						<select name="status" id="status" class="form-control" required autofocus>
							<option value="">-- Pilih Keputusan --</option>
							<option value="Approved">Approved (Terima)</option>
							<option value="Reject">Reject (Tolak)</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan Status</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- #modal-dialog3 (Detail Informasi Siswa) -->
<div class="modal fade" id="modal-dialog3">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Informasi Detail Siswa</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered text-white align-middle">
						<tr>
							<td width="35%">NISN</td>
							<td><span id="modal_nisn" class="fw-bold"></span></td>
						</tr>
						<tr>
							<td>Nama Siswa</td>
							<td><span id="modal_nama_siswa" class="fw-bold"></span></td>
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
							<td>No HP Wali</td>
							<td><span id="modal_hp" class="text-warning"></span></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA IZIN / SAKIT</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Daftar Pengajuan</h4>
		</div>
		<div class="panel-body">
			<div class="mb-3">
				<a href="<?= base_url('izin_sakit/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
				<a href="<?= base_url('izin_sakit/approved_all_data') ?>" onclick="return confirm('Yakin ingin menyetujui (Approve) SEMUA pengajuan yang berstatus Waiting?');" class="btn btn-success btn-sm"><i class="fa fa-check-double"></i> Approve Semua (Waiting)</a>
			</div>

			<div class="table-responsive">
				<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Nama</th>
							<th>Level</th>
							<th>Tanggal</th>
							<th>Surat Keterangan</th>
							<th>Keterangan</th>
							<th>Status</th>
							<th>Deskripsi</th>
							<th width="15%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$db = \Config\Database::connect();
						foreach ($izin_sakit_data as $izin_sakit) :

							// Ambil data User untuk melacak Nama dan Level
							$user = $db->table('user')->where('user_id', $izin_sakit->user_id)->get()->getRow();
							$nama_lengkap = 'Unknown';
							$nama_level   = 'Unknown';

							if ($user) {
								if ($user->level_id == 1) {
									$nama_lengkap = 'Admin Aplikasi';
									$nama_level = 'Admin';
								} elseif ($user->level_id == 2) {
									$guru = $db->table('guru')->where('nip', $user->username)->get()->getRow();
									$nama_lengkap = $guru ? $guru->nama_guru : '-';
									$nama_level = 'Guru';
								} elseif ($user->level_id == 3) {
									$pegawai = $db->table('pegawai')->where('nip', $user->username)->get()->getRow();
									$nama_lengkap = $pegawai ? $pegawai->nama_pegawai : '-';
									$nama_level = 'Pegawai';
								} elseif ($user->level_id == 4) {
									$siswa = $db->table('siswa')->where('nisn', $user->username)->get()->getRow();
									$nama_lengkap = $siswa ? $siswa->nama_siswa : '-';
									$nama_level = 'Siswa';
								}
							}
						?>
							<tr>
								<td><?= $no++ ?></td>
								<td>
									<?= $nama_lengkap ?>
									<?php if ($nama_level == 'Siswa') : ?>
										<a href="javascript:;" id="view_data" data-bs-toggle="modal" data-bs-target="#modal-dialog3" data-user_id="<?= $izin_sakit->user_id ?>" class="text-info ms-1">
											<i class="fas fa-info-circle"></i>
										</a>
									<?php endif; ?>
								</td>
								<td><?= $nama_level ?></td>
								<td><?= date('d-m-Y', strtotime($izin_sakit->tanggal)) ?></td>
								<td class="text-center">
									<?php if (!empty($izin_sakit->photo)) : ?>
										<a href="javascript:;" id="view_gambar" data-bs-toggle="modal" data-bs-target="#modal-dialog" data-file="<?= $izin_sakit->photo ?>">
											<img src="<?= base_url('assets/img/izin/' . $izin_sakit->photo) ?>" class="rounded" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #555;" />
										</a>
									<?php else: ?>
										<span class="text-muted"><i class="fa fa-image"></i> Kosong</span>
									<?php endif; ?>
								</td>
								<td><?= $izin_sakit->keterangan ?></td>
								<td>
									<?php if ($izin_sakit->status == 'Waiting') : ?>
										<span class="badge bg-warning text-dark rounded-pill">Waiting</span>
									<?php elseif ($izin_sakit->status == 'Approved') : ?>
										<span class="badge bg-success rounded-pill">Approved</span>
									<?php else : ?>
										<span class="badge bg-danger rounded-pill">Reject</span>
									<?php endif; ?>
								</td>
								<td><?= $izin_sakit->deskripsi ?></td>
								<td>
									<?php if ($izin_sakit->status == 'Waiting') : ?>
										<button type="button" class="open-AddModal btn btn-success btn-sm mb-1" data-izin_sakit_id="<?= $izin_sakit->izin_sakit_id ?>" data-bs-toggle="modal" data-bs-target="#modal-dialog2"><i class="fas fa-edit"></i> Keputusan</button>
										<a href="<?= base_url('izin_sakit/update/' . encrypt_url($izin_sakit->izin_sakit_id)) ?>" class="btn btn-primary btn-sm mb-1"><i class="fas fa-pencil-alt"></i></a>
										<a href="<?= base_url('izin_sakit/delete/' . encrypt_url($izin_sakit->izin_sakit_id)) ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin hapus data ini?');"><i class="fas fa-trash-alt"></i></a>
									<?php else : ?>
										<button disabled class="btn btn-secondary btn-sm mb-1"><i class="fas fa-lock"></i> Terkunci</button>
									<?php endif; ?>
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
	$(document).ready(function() {
		if (typeof $.fn.select2 === 'function') {
			$(".theSelect").select2();
		}

		// Preview Foto Bukti
		$(document).on('click', '#view_gambar', function() {
			var file = $(this).data('file');
			$('#modal-dialog #file_attac').attr("src", "<?= base_url('assets/img/izin/') ?>" + file);
			$('#modal-dialog #download').attr("href", "<?= base_url('izin_sakit/download/') ?>" + file);
		});

		// Trigger ID Izin ke Modal Update Status
		$(document).on("click", ".open-AddModal", function() {
			var izin_sakit_id = $(this).data('izin_sakit_id');
			$('#modal_izin_sakit_id').val(izin_sakit_id);
		});

		// AJAX Get Detail Siswa
		$(document).on('click', '#view_data', function() {
			var user_id = $(this).data('user_id');
			$('#modal_nisn, #modal_nama_siswa, #modal_jenis_kelamin, #modal_kelas, #modal_alamat, #modal_tempat_lahir, #modal_tanggal_lahir, #modal_wali, #modal_hp').text('Loading...');

			$.ajax({
				url: '<?= base_url('izin_sakit/detail_siswa') ?>',
				method: 'POST',
				data: {
					user_id: user_id
				},
				dataType: 'json',
				success: function(result) {
					if (result.status === 'success') {
						$('#modal_nisn').text(result.data.nisn);
						$('#modal_nama_siswa').text(result.data.nama_siswa);
						$('#modal_jenis_kelamin').text(result.data.jk_kelamin);
						$('#modal_kelas').text(result.data.nama_kelas);
						$('#modal_alamat').text(result.data.alamat);
						$('#modal_tempat_lahir').text(result.data.tempat_lahir);
						$('#modal_tanggal_lahir').text(result.data.tanggal_lahir);
						$('#modal_wali').text(result.data.nama_wali_siswa);
						$('#modal_hp').text(result.data.no_hp_wali_siswa);
					} else {
						$('#modal_nisn').text('Data tidak ditemukan');
					}
				},
				error: function() {
					$('#modal_nisn').text('Gagal memuat data');
				}
			});
		});
	});
</script>

<?= $this->endSection() ?>