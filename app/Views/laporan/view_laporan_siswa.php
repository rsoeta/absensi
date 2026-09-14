<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php
$db = \Config\Database::connect();

// 1. GENERATE TANGGAL SECARA DINAMIS
$periode_dates = [];
$teks_periode = "";

if ($tipe_filter == 'rentang') {
	// Mode Rentang Tanggal
	$begin = new DateTime($tanggal_mulai);
	$end = new DateTime($tanggal_akhir);
	$end = $end->modify('+1 day'); // Tambah 1 hari agar tanggal akhir ikut terhitung
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval, $end);

	foreach ($daterange as $date) {
		$periode_dates[] = $date->format("Y-m-d");
	}
	$teks_periode = date('d-M-Y', strtotime($tanggal_mulai)) . " s/d " . date('d-M-Y', strtotime($tanggal_akhir));
} else {
	// Mode Bulan (Default Lama)
	$hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
	for ($x = 1; $x <= $hari; $x++) {
		$periode_dates[] = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($x, 2, '0', STR_PAD_LEFT);
	}
	$teks_periode = nama_bulan($bulan) . " - " . $tahun;
}

$total_hari = count($periode_dates);

// 2. FETCH API HARI LIBUR MULTI-BULAN
$result_holdaydate = [];
$months_to_fetch = [];
// Kumpulkan bulan & tahun apa saja yang muncul di periode ini
foreach ($periode_dates as $pd) {
	$m = date('m', strtotime($pd));
	$y = date('Y', strtotime($pd));
	$months_to_fetch["$y-$m"] = ['month' => $m, 'year' => $y];
}

foreach ($months_to_fetch as $my) {
	$url = 'https://api-harilibur.vercel.app/api?month=' . (int)$my['month'] . '&year=' . $my['year'];
	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_code == 200 && $response !== false) {
			$data_libur = json_decode($response);
			if (is_array($data_libur)) {
				$result_holdaydate = array_merge($result_holdaydate, $data_libur);
			}
		}
	}
}
?>

<div id="content" class="app-content">
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">REKAP ABSEN SISWA</h4>
		</div>
		<div class="panel-body bg-white text-dark">
			<!-- <form action="<?= base_url('cetak/laporan') ?>" method="GET" target="_blank" class="mb-4"> -->
			<!-- Ubah dari action="base_url('cetak/laporan')" menjadi: -->
			<form action="<?= base_url('cetak/laporan_view_debug') ?>" method="get" target="_blank">
				<input type="hidden" name="user_id" value="<?= $user_id ?>">
				<input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
				<input type="hidden" name="tipe_filter" value="<?= $tipe_filter ?>">
				<input type="hidden" name="bulan" value="<?= $bulan ?>">
				<input type="hidden" name="tahun" value="<?= $tahun ?>">
				<input type="hidden" name="tanggal_mulai" value="<?= $tanggal_mulai ?>">
				<input type="hidden" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
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

				<h4 class="my-4 text-dark fw-bold">
					Laporan Absen <?= $teks_judul ?> <br>
					<small class="fw-normal text-dark"><?= $teks_periode ?></small>
				</h4>

				<div class="table-responsive px-4">
					<table class="table table-bordered table-sm text-dark align-middle border-dark text-center" style="white-space: nowrap;">
						<thead class="bg-light fw-bold">
							<tr>
								<td rowspan="2" class="align-middle text-start px-2">Nama Siswa</td>
								<td colspan="<?= $total_hari ?>">Tanggal</td>
								<td colspan="6">Keterangan Total</td>
							</tr>
							<tr>
								<?php foreach ($periode_dates as $pd) : ?>
									<td style="width: 25px;"><?= date('d', strtotime($pd)) ?></td>
								<?php endforeach; ?>
								<td style="width: 30px;">A</td>
								<td style="width: 30px;">S</td>
								<td style="width: 30px;">I</td>
								<td style="width: 30px;">✓</td>
								<td style="background-color: grey; color: white; width: 30px;">✓</td>
								<td style="background-color: #5353ec; color: white; width: 30px;">B</td>
							</tr>
						</thead>
						<tbody>
							<?php
							// Query Dinamis Berdasarkan Kelas
							if ($user_id == 'semua_data') {
								if (!empty($kelas_id)) {
									$query = $db->query("SELECT u.user_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' AND s.kelas_id='$kelas_id' ORDER BY s.nama_siswa ASC")->getResult();
								} else {
									$query = $db->query("SELECT u.user_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' ORDER BY s.nama_siswa ASC")->getResult();
								}
							} else {
								// Buat array pura-pura agar bisa di-loop seperti 'semua_data'
								$query = [(object)['user_id' => $user_id]];
							}

							foreach ($query as $data) : ?>
								<tr>
									<td class="text-start fw-bold px-2"><?= nama_siswa($data->user_id) ?></td>

									<!-- Loop Status Harian -->
									<?php foreach ($periode_dates as $pd) : ?>
										<?php
										// Sesuaikan format parameter tanggal ke Y/m/j sesuai requirement asli fungsi Anda ($tahun/$bulan/$x)
										$format_tgl = date('Y/m/j', strtotime($pd));
										?>
										<?= cek_absen($data->user_id, $format_tgl, $result_holdaydate) ?>
									<?php endforeach; ?>

									<?php
									// Ambil tanggal mulai dan akhir dari array kolom tabel yang di-generate
									$tgl_awal = $periode_dates[0];
									$tgl_akhir = end($periode_dates);
									?>
									<!-- Area Hitung Total (Helper) -->
									<td><?= cek_alpha($data->user_id, $tgl_awal, $tgl_akhir, $result_holdaydate) ?></td>
									<td><?= cek_sakit($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
									<td><?= cek_izin($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
									<td><?= cek_hadir_tepat($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
									<td><?= cek_terlambat($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
									<td><?= cek_bolos($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
								</tr>
							<?php endforeach; ?>
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

<!-- <script>
	window.onload = function() {
		// Otomatis memunculkan dialog print browser saat halaman dibuka
		window.print();
	}
</script> -->

<?= $this->endSection() ?>