<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- #modal-dialog3 -->
<div class="modal fade" id="modal-dialog3">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Informasi</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<table style="color: white" class="table table-bordered">
					<tr>
						<td width="25%">Nisn</td>
						<td><span id="modal_nisn"></span></td>
					</tr>
					<tr>
						<td>Nama Siswa</td>
						<td><span id="modal_nama_siswa"></span></td>
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
						<td>No HP Wali Siswa</td>
						<td><span id="modal_hp"></span></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<div class="row">
		<div class="col-xl-4 ui-sortable">
			<div class="panel panel-inverse" data-sortable-id="form-stuff-1" data-init="true">
				<div class="panel-heading ui-sortable-handle">
					<h4 class="panel-title">PILIH TAHUN AJARAN</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<form action="" method="get" enctype="multipart/form-data">
						<table class="table table-bordered table-hover table-td-valign-middle">
							<tr>
								<td>Tahun Ajaran</td>
								<td>
									<select name="tahun_ajaran_id" class="form-control theSelect" required style="width: 100%;">
										<option value="">-- Pilih -- </option>
										<?php foreach ($tahun_ajaran_data as $data) : ?>
											<option value="<?= $data->tahun_ajaran_id ?>" <?= ($tahun_ajar == $data->tahun_ajaran_id) ? 'selected' : '' ?>>
												<?= $data->nama_tahun_ajaran ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Ranking</button>
									<?php if (isset($_GET['tahun_ajaran_id'])) : ?>
										<a href="<?= base_url('rangking_absen') ?>" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Reset</a>
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>

		<div class="col-xl-8 ui-sortable">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Data Ranking Absensi</h4>
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
							<div class="table-responsive">
								<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
									<thead>
										<tr>
											<th width="1%">Ranking</th>
											<th>Nama</th>
											<th>Kelas</th>
											<th>Point Masuk</th>
											<th>Point Pulang</th>
											<th>Total Point</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1;
										foreach ($dataRangking as $data) : ?>
											<tr>
												<td><?= $no++ ?></td>
												<td><?= $data->nama_siswa ?></td>
												<td><?= $data->nama_kelas ?></td>
												<td><?= $data->point ?> Point</td>
												<td><?= $data->point_pulang ?> Point</td>
												<td><?= $data->total_ea ?> Point</td>
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
</div>

<script>
	$(document).ready(function() {
		if (typeof $.fn.select2 === 'function') {
			$(".theSelect").select2();
		}
	});
</script>

<?= $this->endSection() ?>