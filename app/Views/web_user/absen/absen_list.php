<?= $this->extend('web_user/template_user') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">DATA ABSEN</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Riwayat Kehadiran</h4>
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
						<div class="box-body" style="overflow-x: scroll;">
							<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
								<thead>
									<tr>
										<th width="5%" class="text-center">No</th>
										<th>Nama</th>
										<th>Tanggal</th>
										<th>Keterangan</th>
										<th>Status Masuk</th>
										<th>Jam Masuk</th>
										<th>Jam Pulang</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1;
									foreach ($absen_data as $absen) : ?>
										<tr>
											<td class="text-center"><?= $no++ ?></td>
											<td>
												<?php if ($absen->level_id == '1') : ?>
													Admin Aplikasi
												<?php elseif ($absen->level_id == '2') : ?>
													<?= ucwords(strtolower(nama_guru($absen->user_id))) ?>
												<?php elseif ($absen->level_id == '3') : ?>
													<?= ucwords(strtolower(nama_pegawai($absen->user_id))) ?>
												<?php elseif ($absen->level_id == '4') : ?>
													<?= ucwords(strtolower(nama_siswa($absen->user_id))) ?>
												<?php else : ?>
													-
												<?php endif; ?>
											</td>
											<td><?= $absen->tanggal ?></td>
											<td><?= $absen->keterangan ?></td>
											<td>
												<?php if ($absen->status_masuk == 'Terlambat') : ?>
													<span class="badge bg-danger rounded-pill">Terlambat</span>
												<?php elseif ($absen->status_masuk == 'Tepat Waktu') : ?>
													<span class="badge bg-success rounded-pill">Tepat Waktu</span>
												<?php else : ?>
													<span class="badge bg-secondary rounded-pill"><?= $absen->status_masuk ?? '-' ?></span>
												<?php endif; ?>
											</td>
											<td><?= $absen->jam_masuk ?? '-' ?></td>
											<td><?= $absen->jam_pulang ?? '-' ?></td>
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
</div>

<?= $this->endSection() ?>