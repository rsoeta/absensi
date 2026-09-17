<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- Modal Preview Foto Absen -->
<div class="modal fade" id="modal-dialog3">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-dark">
				<h4 class="modal-title text-white"><i class="fa fa-camera"></i> Bukti Selfie</h4>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center bg-light p-2">
				<img src="" id="photo_karyawan" class="img-fluid rounded shadow" style="width: 100%; border: 3px solid #123e87;" />
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header">DATA ABSEN
		<?php
		if ($level_id == '2') echo "GURU";
		elseif ($level_id == '3') echo "PEGAWAI";
		elseif ($level_id == '4') echo "SISWA";
		?>
	</h1>

	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Daftar Kehadiran</h4>
		</div>
		<div class="panel-body">
			<div style="padding-bottom: 15px;">
				<a href="<?= base_url('absen/create/' . $level_id) ?>" class="btn btn-danger btn-sm">
					<i class="fas fa-plus-square"></i> Tambah Data
				</a>
			</div>

			<div class="table-responsive">
				<!-- Menghapus class text-white agar selaras dengan Tema Muhammadiyah cerah -->
				<table id="tabel-data-absen" class="table table-bordered table-hover align-middle text-nowrap">
					<thead>
						<tr class="text-center bg-light">
							<th width="1%">No</th>
							<th>Nama Lengkap</th>
							<th>Tanggal</th>
							<th>Keterangan</th>
							<th>Data Masuk (Jam, Status, Lokasi)</th>
							<th>Data Pulang (Jam, Status, Lokasi)</th>
							<th>Point (M|P)</th>
							<th width="8%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($absen_data as $absen) : ?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td class="fw-bold"><?= $absen->nama_lengkap ?? 'Unknown' ?></td>
								<td class="text-center"><?= date('d-M-Y', strtotime($absen->tanggal)) ?></td>
								<td class="text-center"><?= $absen->keterangan ?></td>

								<!-- KOLOM DATA MASUK COMPACT -->
								<td class="text-center">
									<span class="d-block fw-bold text-primary mb-1"><?= $absen->jam_masuk ?? '-' ?></span>

									<?php if ($absen->status_masuk == 'Terlambat') : ?>
										<span class="badge bg-danger mb-1">Terlambat</span>
									<?php elseif ($absen->status_masuk == 'Tepat Waktu' || $absen->status_masuk == 'Hadir') : ?>
										<span class="badge bg-success mb-1">Tepat Waktu</span>
									<?php else : ?>
										<span class="badge bg-secondary mb-1">Tidak Ada</span>
									<?php endif; ?>

									<div class="d-flex justify-content-center gap-1 mt-1">
										<?php if (!empty($absen->photo_masuk)) : ?>
											<a href="javascript:;" class="btn btn-xs btn-info text-white shadow-sm view_data" data-bs-toggle="modal" data-bs-target="#modal-dialog3" data-photo="<?= $absen->photo_masuk ?>" title="Lihat Selfie Masuk">
												<i class="fas fa-camera"></i>
											</a>
										<?php endif; ?>

										<?php if (!empty($absen->lat_masuk) && !empty($absen->long_masuk)): ?>
											<a href="https://maps.google.com/?q=<?= $absen->lat_masuk ?>,<?= $absen->long_masuk ?>" target="_blank" class="btn btn-xs btn-success shadow-sm" title="Lihat Peta Masuk">
												<i class="fa fa-map-marker-alt"></i>
											</a>
										<?php endif; ?>
									</div>
								</td>

								<!-- KOLOM DATA PULANG COMPACT -->
								<td class="text-center">
									<span class="d-block fw-bold text-primary mb-1"><?= $absen->jam_pulang ?? '-' ?></span>

									<?php if ($absen->status_pulang == 'Terlambat') : ?>
										<span class="badge bg-danger mb-1">Terlambat</span>
									<?php elseif ($absen->status_pulang == 'Tepat Waktu' || $absen->status_pulang == 'Hadir') : ?>
										<span class="badge bg-success mb-1">Tepat Waktu</span>
									<?php else : ?>
										<span class="badge bg-secondary mb-1">Tidak Ada</span>
									<?php endif; ?>

									<div class="d-flex justify-content-center gap-1 mt-1">
										<?php if (!empty($absen->photo_pulang)) : ?>
											<a href="javascript:;" class="btn btn-xs btn-info text-white shadow-sm view_data" data-bs-toggle="modal" data-bs-target="#modal-dialog3" data-photo="<?= $absen->photo_pulang ?>" title="Lihat Selfie Pulang">
												<i class="fas fa-camera"></i>
											</a>
										<?php endif; ?>

										<?php if (!empty($absen->lat_pulang) && !empty($absen->long_pulang)): ?>
											<a href="https://maps.google.com/?q=<?= $absen->lat_pulang ?>,<?= $absen->long_pulang ?>" target="_blank" class="btn btn-xs btn-success shadow-sm" title="Lihat Peta Pulang">
												<i class="fa fa-map-marker-alt"></i>
											</a>
										<?php endif; ?>
									</div>
								</td>

								<td class="text-center"><small class="fw-bold"><?= $absen->point ?> | <?= $absen->point_pulang ?></small></td>

								<td class="text-center">
									<a href="<?= base_url('absen/update/' . $absen->absen_id . '/' . $level_id) ?>" class="btn btn-primary btn-sm mb-1" title="Edit Data"><i class="fas fa-pencil-alt"></i></a>
									<a href="<?= base_url('absen/delete/' . $absen->absen_id . '/' . $level_id) ?>" class="btn btn-danger btn-sm mb-1 btn-delete-absen" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>
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
		// --- SCRIPT PREVIEW FOTO ---
		$(document).on('click', '.view_data', function() {
			var photo = $(this).data('photo');
			// Ubah path menjadi assets/img/absen/ sesuai sistem penyimpanan baru
			$('#modal-dialog3 #photo_karyawan').attr("src", "<?= base_url('assets/img/absen/') ?>" + photo);
		});

		// --- SCRIPT SWEETALERT2 UNTUK TOMBOL HAPUS ---
		$(document).on('click', '.btn-delete-absen', function(e) {
			e.preventDefault();
			var urlHapus = $(this).attr('href');

			Swal.fire({
				title: 'Hapus Data?',
				text: "Data absen ini akan dihapus permanen.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#858796',
				confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
				cancelButtonText: 'Batal',
				customClass: {
					popup: 'swal-mungil'
				}
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = urlHapus;
				}
			});
		});

		// Inisiasi DataTables pada ID baru agar terhindar dari bentrok script bawaan template
		if (typeof $.fn.DataTable === 'function') {
			$('#tabel-data-absen').DataTable({
				deferRender: true,
				responsive: true,
				language: {
					url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
				}
			});
		}
	});
</script>

<?= $this->endSection() ?>