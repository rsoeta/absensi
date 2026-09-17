<?= $this->extend('web_user/template_user') ?>
<?= $this->section('content') ?>

<!-- #modal-dialog (Preview Surat Keterangan) -->
<div class="modal fade" id="modal-dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fs-5">Surat Keterangan <span id="cuts"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body p-2">
				<div class="ratio ratio-43" style="min-height: 350px;">
					<iframe src="" id="file_attac" frameborder="0" class="w-100 h-100"></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white btn-sm" data-bs-dismiss="modal">Close</a>
				<a class="btn btn-primary btn-sm" id="download" href=""><i class="fa fa-download"></i> Download</a>
			</div>
		</div>
	</div>
</div>

<!-- #modal-dialog3 (Detail Siswa) -->
<div class="modal fade" id="modal-dialog3" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fs-5">Informasi Siswa</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body table-responsive">
				<table class="table table-bordered text-dark mb-0">
					<tr>
						<td width="35%" class="fw-bold">NISN</td>
						<td><span id="modal_nisn"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Nama Siswa</td>
						<td><span id="modal_nama_siswa"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Jenis Kelamin</td>
						<td><span id="modal_jenis_kelamin"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Kelas</td>
						<td><span id="modal_kelas"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Alamat</td>
						<td><span id="modal_alamat"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Tempat Lahir</td>
						<td><span id="modal_tempat_lahir"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Tanggal Lahir</td>
						<td><span id="modal_tanggal_lahir"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">Nama Wali Siswa</td>
						<td><span id="modal_wali"></span></td>
					</tr>
					<tr>
						<td class="fw-bold">No HP Wali</td>
						<td><span id="modal_hp"></span></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header mb-3">DATA IZIN SAKIT</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Riwayat Pengajuan Izin & Sakit</h4>
			<div class="panel-heading-btn">
				<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="panel-body">
			<div class="row mb-3">
				<div class="col-12">
					<?= anchor(site_url('dashboard_user/create_izin_sakit'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data shadow-sm"'); ?>
				</div>
			</div>

			<!-- Pembungkus responsif agar tabel aman diakses di HP -->
			<div class="table-responsive">
				<table id="data-table-default" class="table table-striped table-bordered table-hover align-middle text-white text-nowrap">
					<thead>
						<tr class="text-center">
							<th width="5%">No</th>
							<th>Nama</th>
							<th>Level</th>
							<th>Tanggal</th>
							<th>Surat Keterangan</th>
							<th>Keterangan</th>
							<th>Status</th>
							<th>Deskripsi</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($izin_sakit_data as $izin_sakit) : ?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td>
									<?php if ($izin_sakit->level_id == '1') : ?>
										Admin Aplikasi
									<?php elseif ($izin_sakit->level_id == '2') : ?>
										<?= ucwords(strtolower(nama_guru($izin_sakit->user_id))) ?>
									<?php elseif ($izin_sakit->level_id == '3') : ?>
										<?= ucwords(strtolower(nama_pegawai($izin_sakit->user_id))) ?>
									<?php elseif ($izin_sakit->level_id == '4') : ?>
										<?= ucwords(strtolower(nama_siswa($izin_sakit->user_id))) ?>
										<a id="view_data" href="#modal-dialog3" data-bs-toggle="modal" data-user_id="<?= $izin_sakit->user_id ?>" class="ms-1">
											<i class="fas fa-info-circle text-info"></i>
										</a>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($izin_sakit->level_id == '1') : ?>
										Admin
									<?php elseif ($izin_sakit->level_id == '2') : ?>
										Guru
									<?php elseif ($izin_sakit->level_id == '3') : ?>
										Pegawai
									<?php elseif ($izin_sakit->level_id == '4') : ?>
										Siswa
									<?php endif; ?>
								</td>
								<td><?= $izin_sakit->tanggal ?></td>
								<td class="with-img text-center">
									<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-file="<?= $izin_sakit->photo ?>">
										<img src="<?= base_url('assets/assets/img/izin/' . ($izin_sakit->photo ? $izin_sakit->photo : 'default.png')) ?>" class="rounded shadow-sm" style="height: 35px; width: 35px; object-fit: cover;" onerror="this.src='<?= base_url('assets/img/logo/default.png') ?>'" />
									</a>
								</td>
								<td><?= $izin_sakit->keterangan ?></td>
								<td class="text-center">
									<?php if ($izin_sakit->status == 'Waiting') : ?>
										<span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Waiting</span>
									<?php elseif ($izin_sakit->status == 'Approved') : ?>
										<span class="badge bg-success px-2 py-1 rounded-pill">Approved</span>
									<?php else : ?>
										<span class="badge bg-danger px-2 py-1 rounded-pill">Reject</span>
									<?php endif; ?>
								</td>
								<td><span class="text-wrap" style="max-width: 200px; display: inline-block;"><?= $izin_sakit->deskripsi ?></span></td>
								<td class="text-center">
									<?php if ($izin_sakit->status == 'Waiting') : ?>
										<div class="btn-group" role="group">
											<?= anchor(site_url('dashboard_user/update_izin_sakit/' . encrypt_url($izin_sakit->izin_sakit_id)), '<i class="fas fa-pencil-alt"></i>', 'class="btn btn-primary btn-sm px-2 py-1" title="Edit"') ?>
											<a href="javascript:void(0);" onclick="konfirmasiHapus('<?= site_url('dashboard_user/delete_izin_sakit/' . encrypt_url($izin_sakit->izin_sakit_id)) ?>')" class="btn btn-danger btn-sm px-2 py-1" title="Hapus">
												<i class="fas fa-trash-alt"></i>
											</a>
										</div>
									<?php else : ?>
										<div class="btn-group" role="group">
											<button disabled class="btn btn-primary btn-sm px-2 py-1"><i class="fas fa-pencil-alt"></i></button>
											<button disabled class="btn btn-danger btn-sm px-2 py-1"><i class="fas fa-trash-alt"></i></button>
										</div>
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
		$(".theSelect").select2();
	});
</script>

<script type="text/javascript">
	$(document).on('click', '#view_gambar', function() {
		var file = $(this).data('file');
		$('#modal-dialog #file_attac').attr("src", "<?= base_url('assets/assets/img/izin/') ?>" + file);
		$('#modal-dialog #download').attr("href", "<?= base_url('dashboard_user/download_izin_sakit/') ?>" + file);
	});

	$(document).on('click', '#view_data', function() {
		var user_id = $(this).data('user_id');
		$.ajax({
			url: '<?= base_url('izin_sakit/detail_siswa') ?>',
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
	});
</script>

<script>
	function konfirmasiHapus(urlDelete) {
		Swal.fire({
			title: 'Hapus Data?',
			text: "Data pengajuan izin/sakit ini akan dihapus permanen!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal',
			width: '90%',
			maxWidth: '400px',
			customClass: {
				popup: 'swal2-compact-popup'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = urlDelete;
			}
		});
	}
</script>
<?= $this->endSection() ?>