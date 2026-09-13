<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">Form Input Absen Mapel</h1>
	<div class="row">
		<!-- Kolom Form Filter -->
		<div class="col-xl-5">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Pilih Kelas & Mapel</h4>
				</div>
				<div class="panel-body">
					<form method="get">
						<table class="table table-bordered table-td-valign-middle text-white">
							<tr>
								<td>Kelas - Mapel</td>
								<td style="width: 70%;">
									<select name="m" class="form-control theSelect" required>
										<option value="">-- Pilih --</option>
										<?php $m_val = request()->getGet('m'); ?>
										<?php foreach ($sett_mapel_data as $sett_mapel) : ?>
											<option value="<?= $sett_mapel->sett_mapel_id ?>" <?= ($m_val == $sett_mapel->sett_mapel_id) ? 'selected' : '' ?>>
												<?= $sett_mapel->nama_kelas ?> - <?= $sett_mapel->nama_mapel ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td>
									<input type="date" class="form-control" name="tanggal" required value="<?= request()->getGet('tanggal') ?? date('Y-m-d') ?>" />
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> Tampilkan Siswa</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>

		<!-- Kolom List Data Siswa -->
		<div class="col-xl-7">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Daftar Kehadiran Siswa</h4>
				</div>
				<div class="panel-body">
					<?php if (request()->getGet('m') && request()->getGet('tanggal')) : ?>
						<?php
						$db = \Config\Database::connect();
						$set_mapel_id = request()->getGet('m');
						$tgl_input = request()->getGet('tanggal');
						$kelas = $db->table('sett_mapel')->where('sett_mapel_id', $set_mapel_id)->get()->getRow();
						$kelas_id = $kelas->kelas_id;
						$data_siswa = $db->table('siswa')->where('kelas_id', $kelas_id)->get()->getResult();
						?>

						<div class="table-responsive">
							<form action="<?= base_url('absen_mapel/doInput') ?>" method="POST">
								<input type="hidden" readonly name="tanggal" value="<?= $tgl_input ?>">
								<input type="hidden" readonly name="sett_mapel_id" value="<?= $set_mapel_id ?>">

								<table class="table table-bordered table-hover text-white align-middle text-center">
									<thead class="table-light text-dark">
										<tr>
											<th>No</th>
											<th class="text-start">Nama Siswa</th>
											<th>H</th>
											<th>A</th>
											<th>I</th>
											<th>S</th>
											<th>B</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1;
										foreach ($data_siswa as $key => $value) : ?>
											<tr>
												<td><?= $no++ ?></td>
												<td class="text-start">
													<?= $value->nama_siswa ?>
													<input type="hidden" name="siswa_id[]" value="<?= $value->siswa_id ?>">
												</td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="H" <?= cekH($set_mapel_id, $tgl_input, $value->siswa_id) ?> required></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="A" <?= cekA($set_mapel_id, $tgl_input, $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="I" <?= cekI($set_mapel_id, $tgl_input, $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="S" <?= cekS($set_mapel_id, $tgl_input, $value->siswa_id) ?>></td>
												<td><input class="form-check-input" type="radio" name="keterangan[<?= $key ?>]" value="B" <?= cekB($set_mapel_id, $tgl_input, $value->siswa_id) ?>></td>
											</tr>
										<?php endforeach; ?>
										<tr>
											<td colspan="7" class="text-center pt-3">
												<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Simpan Absen</button>
												<a href="<?= base_url('absen_mapel') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
											</td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
					<?php else : ?>
						<div class="alert alert-primary"><i class="fa fa-info-circle"></i> Silakan pilih Kelas, Mapel, dan Tanggal terlebih dahulu untuk menampilkan daftar siswa.</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(".theSelect").select2();
	});
</script>

<?= $this->endSection() ?>