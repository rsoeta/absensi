<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php $db = \Config\Database::connect(); ?>
<div id="content" class="app-content">
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">REKAP ABSEN GURU</h4>
		</div>
		<div class="panel-body bg-white text-dark">
			<form action="<?= base_url('cetak/laporan') ?>" method="GET" target="_blank" class="mb-4">
				<input type="hidden" name="user_id" value="<?= $user_id ?>">
				<input type="hidden" name="bulan" value="<?= $bulan ?>">
				<input type="hidden" name="tahun" value="<?= $tahun ?>">
				<input type="hidden" name="area" value="<?= $area ?>">
				<button type="submit" class="btn btn-danger"><i class="fa fa-print"></i> Cetak</button>
			</form>

			<div class="text-center" style="min-height: 800px;">
				<img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
				<div style="padding: 0 150px;">
					<h3 class="mb-1"><?= $sett_apps->nama_sekolah ?></h3>
					<p class="mt-0"><?= $sett_apps->alamat_sekolah ?></p>
				</div>
				<hr style="border-top: 2px solid #000;">

				<h4 class="my-4">
					Laporan Absen <?= ($user_id == 'semua_data') ? "Semua Guru" : nama_guru($user_id) ?> <br>
					<small><?= nama_bulan($bulan) ?> - <?= $tahun ?></small>
				</h4>

				<div class="table-responsive px-4">
					<?php
					$hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
					$result_holdaydate = null;
					$url = 'https://api-harilibur.vercel.app/api?month=' . $bulan . '&year=' . $tahun;

					if (function_exists('curl_init')) {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_TIMEOUT, 5);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$response = curl_exec($ch);
						$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						curl_close($ch);
						if ($http_code == 200 && $response !== false) $result_holdaydate = json_decode($response);
					}
					if ($result_holdaydate === null || !is_array($result_holdaydate)) $result_holdaydate = [];
					?>

					<table class="table table-bordered table-sm text-dark align-middle border-dark text-center">
						<thead class="bg-light fw-bold">
							<tr>
								<td rowspan="2" class="align-middle">Nama Guru</td>
								<td colspan="<?= $hari ?>">Tanggal</td>
								<td colspan="6">Keterangan</td>
							</tr>
							<tr>
								<?php for ($x = 1; $x <= $hari; $x++) : ?>
									<td style="width: 2%;"><?= $x ?></td>
								<?php endfor; ?>
								<td style="width: 2%;">A</td>
								<td style="width: 2%;">S</td>
								<td style="width: 2%;">I</td>
								<td style="width: 2%;">✓</td>
								<td style="background-color: grey; color: white; width: 2%;">✓</td>
								<td style="background-color: #5353ec; color: white; width: 2%;">B</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($user_id == 'semua_data') : ?>
								<?php
								$query = $db->query("SELECT * FROM user WHERE level_id='2'")->getResult();
								foreach ($query as $data) : ?>
									<tr>
										<td class="text-start"><?= nama_guru($data->user_id) ?></td>
										<?php for ($x = 1; $x <= $hari; $x++) : ?>
											<?= cek_absen($data->user_id, "$tahun/$bulan/$x", $result_holdaydate) ?>
										<?php endfor; ?>
										<td><?= cek_alpha($data->user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
										<td><?= cek_sakit($data->user_id, $hari, $bulan, $tahun) ?></td>
										<td><?= cek_izin($data->user_id, $hari, $bulan, $tahun) ?></td>
										<td><?= cek_hadir_tepat($data->user_id, $hari, $bulan, $tahun) ?></td>
										<td><?= cek_terlambat($data->user_id, $hari, $bulan, $tahun) ?></td>
										<td><?= cek_bolos($data->user_id, $hari, $bulan, $tahun) ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td class="text-start"><?= nama_guru($user_id) ?></td>
									<?php for ($x = 1; $x <= $hari; $x++) : ?>
										<?= cek_absen($user_id, "$tahun/$bulan/$x", $result_holdaydate) ?>
									<?php endfor; ?>
									<td><?= cek_alpha($user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
									<td><?= cek_sakit($user_id, $hari, $bulan, $tahun) ?></td>
									<td><?= cek_izin($user_id, $hari, $bulan, $tahun) ?></td>
									<td><?= cek_hadir_tepat($user_id, $hari, $bulan, $tahun) ?></td>
									<td><?= cek_terlambat($user_id, $hari, $bulan, $tahun) ?></td>
									<td><?= cek_bolos($user_id, $hari, $bulan, $tahun) ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>

					<table class="table table-bordered table-sm mt-4 text-dark border-dark" style="width: 350px;">
						<tr class="table-warning">
							<th class="text-center">Kode</th>
							<th>Keterangan</th>
						</tr>
						<tr>
							<td class="text-center fw-bold">A</td>
							<td>Alpha</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">S</td>
							<td>Sakit</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">I</td>
							<td>Izin</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">✓</td>
							<td>Hadir Tepat Waktu</td>
						</tr>
						<tr>
							<td class="text-center fw-bold" style="background-color: grey; color:white;">✓</td>
							<td>Hadir Terlambat</td>
						</tr>
						<tr>
							<td class="text-center fw-bold" style="background-color: #5353ec; color:white;">B</td>
							<td>Bolos/Masuk Tidak Absen Pulang</td>
						</tr>
						<tr>
							<td style="background-color: yellow;"></td>
							<td>Hari Libur Nasional</td>
						</tr>
						<tr>
							<td style="background-color: red;"></td>
							<td>Hari Minggu</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>