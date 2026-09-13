<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<div class="col-xl-6">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">LAPORAN ABSEN PEGAWAI</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post">
					<table class="table table-bordered table-td-valign-middle text-white align-middle">
						<tr>
							<td width="200">Pilih Pegawai</td>
							<td>
								<select name="user_id" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<option value="semua_data">-- Semua Pegawai --</option>
									<?php foreach ($user_data as $data) : ?>
										<option value="<?= $data->user_id ?>" <?= $user_id == $data->user_id ? 'selected' : '' ?>>
											Pegawai <?= nama_pegawai($data->user_id) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Bulan</td>
							<td>
								<select name="bulan" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php
									$bulans = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
									foreach ($bulans as $num => $nama) : ?>
										<option value="<?= $num ?>" <?= $bulan == $num ? 'selected' : '' ?>><?= $nama ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Tahun</td>
							<td>
								<select name="tahun" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php foreach ($tahun_data as $data) : ?>
										<option value="<?= $data->tahun ?>" <?= $tahun == $data->tahun ? 'selected' : '' ?>><?= $data->tahun ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<button type="submit" class="btn btn-danger"><i class="fas fa-eye"></i> <?= $button ?></button>
							</td>
						</tr>
					</table>
				</form>
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