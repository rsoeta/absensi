<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- Modal Preview Foto Absen -->
<div class="modal fade" id="modal-dialog3">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Foto Absen</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="photo_karyawan" style="max-width: 100%; border-radius: 8px; border: 1px solid #555;" />
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
				<a href="<?= base_url('absen/create/' . encrypt_url($level_id)) ?>" class="btn btn-danger btn-sm">
					<i class="fas fa-plus-square"></i> Tambah Data
				</a>
			</div>

			<div class="table-responsive">
				<table id="data-table-default" class="table table-bordered table-hover align-middle text-white">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Nama</th>
							<th>Tanggal</th>
							<th>Keterangan</th>
							<th>Masuk</th>
							<th>Status Masuk</th>
							<th>Pulang</th>
							<th>Status Pulang</th>
							<th>Geo Location</th>
							<th>Foto Masuk</th>
							<th>Foto Pulang</th>
							<th>Point</th>
							<th width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($absen_data as $absen) : ?>
							<tr>
								<td><?= $no++ ?></td>
								<td class="fw-bold"><?= $absen->nama_lengkap ?? 'Unknown' ?></td>
								<td><?= date('d-M-Y', strtotime($absen->tanggal)) ?></td>
								<td><?= $absen->keterangan ?></td>

								<td><?= $absen->jam_masuk ?? '-' ?></td>
								<td>
									<?php if ($absen->status_masuk == 'Terlambat') : ?>
										<span class="badge bg-danger">Terlambat</span>
									<?php elseif ($absen->status_masuk == 'Tepat Waktu') : ?>
										<span class="badge bg-success">Tepat Waktu</span>
									<?php else : ?>
										<span class="badge bg-secondary">Tidak Ada</span>
									<?php endif; ?>
								</td>

								<td><?= $absen->jam_pulang ?? '-' ?></td>
								<td>
									<?php if ($absen->status_pulang == 'Terlambat') : ?>
										<span class="badge bg-danger">Terlambat</span>
									<?php elseif ($absen->status_pulang == 'Tepat Waktu') : ?>
										<span class="badge bg-success">Tepat Waktu</span>
									<?php else : ?>
										<span class="badge bg-secondary">Tidak Ada</span>
									<?php endif; ?>
								</td>

								<td>
									<?php if (!empty($absen->is_geolocation)): ?>
										<a href="https://maps.google.com/?q=<?= $absen->is_geolocation ?>" target="_blank" class="text-info"><i class="fa fa-map-marker-alt"></i> Lihat Map</a>
									<?php else: ?>
										-
									<?php endif; ?>
								</td>

								<td class="text-center">
									<?php if (!empty($absen->selfie_masuk)) : ?>
										<a href="javascript:;" id="view_data" data-bs-toggle="modal" data-bs-target="#modal-dialog3" data-photo="<?= $absen->selfie_masuk ?>">
											<i class="fas fa-camera text-info"></i> View
										</a>
									<?php else: ?> - <?php endif; ?>
								</td>

								<td class="text-center">
									<?php if (!empty($absen->selfie_keluar)) : ?>
										<a href="javascript:;" id="view_data" data-bs-toggle="modal" data-bs-target="#modal-dialog3" data-photo="<?= $absen->selfie_keluar ?>">
											<i class="fas fa-camera text-warning"></i> View
										</a>
									<?php else: ?> - <?php endif; ?>
								</td>

								<td><small>M: <?= $absen->point ?> | P: <?= $absen->point_pulang ?></small></td>

								<td>
									<a href="<?= base_url('absen/update/' . encrypt_url($absen->absen_id) . '/' . encrypt_url($level_id)) ?>" class="btn btn-primary btn-sm mb-1"><i class="fas fa-pencil-alt"></i></a>
									<a href="<?= base_url('absen/delete/' . encrypt_url($absen->absen_id) . '/' . encrypt_url($level_id)) ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin hapus data absen ini?');"><i class="fas fa-trash-alt"></i></a>
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
		$(document).on('click', '#view_data', function() {
			var photo = $(this).data('photo');
			$('#modal-dialog3 #photo_karyawan').attr("src", "<?= base_url('assets/bukti_absen/') ?>" + photo);
		});
	});
</script>

<?= $this->endSection() ?>