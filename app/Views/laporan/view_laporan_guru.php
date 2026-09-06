<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">REKAP ABSEN GURU</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?= base_url() ?>cetak/laporan" method="GET" target="_blank">
					<input type="hidden" name="user_id" value="<?= $user_id ?>">
					<input type="hidden" name="bulan" value="<?= $bulan ?>">
					<input type="hidden" name="tahun" value="<?= $tahun ?>">
					<input type="hidden" name="area" value="<?= $area ?>">
					<button type="submit" class="btn btn-danger"><i class="fa fa-print"></i> Cetak</button>
				</form>

				<body style="min-height: 1100px;">
					<div class="text-center">

						<img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>"></img>
						<div style="padding-left:150px;padding-right:150px">
							<h3><?= $sett_apps->nama_sekolah  ?></h3>
							<p><?= $sett_apps->alamat_sekolah ?></p>
						</div>
						<hr style="border-top: 2px solid #000">

						<h3>Laporan Absen
							<?php if ($user_id == 'semua_data') {
								echo "Semua Guru";
							} else {
								echo nama_guru($user_id);
							} ?>


							<?= nama_bulan($bulan)  ?> - <?= $tahun ?> </h3>
						<div style="padding-left: 50px; padding-top:30px; padding-bottom:30px;">
							<?php
							$kalender = CAL_GREGORIAN;
							$hari = cal_days_in_month($kalender, $bulan, $tahun);

							$url = 'https://api-harilibur.vercel.app/api?month=' . $bulan . '&year=' . $tahun;
							$content = file_get_contents($url);
							$result_holdaydate  = json_decode($content);
							?>
							<table class="table table-bordered table-sm">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle;">Nama Guru</th>
										<th colspan="<?= $hari ?>">Tanggal</th>
										<th colspan="6">Keterangan</th>
									</tr>
									<tr>
										<?php
										for ($x = 1; $x <= $hari; $x++) { ?>
											<td style="width: 2%;"><?= $x ?></td>
										<?php } ?>
										<td style="width: 2%;">A</td>
										<td style="width: 2%;">S</td>
										<td style="width: 2%;">I</td>
										<td style="width: 2%;">✓</td>
										<td style="background-color: grey;width: 2%;">✓</td>
										<td style="background-color: #5353ec;width: 2%;">B</td>
									</tr>
								</thead>
								<tbody>
									<?php if ($user_id == 'semua_data') {
										$query = $this->db->query("SELECT * from user where level_id='2'");
										foreach ($query->result() as $data) { ?>
											<tr>
												<td><?php echo nama_guru($data->user_id)  ?></td>
												<?php
												for ($x = 1; $x <= $hari; $x++) { ?>
													<?= cek_absen($data->user_id, $tahun . '/' . $bulan . '/' . $x, $result_holdaydate) ?>
												<?php } ?>
												<td><?= cek_alpha($data->user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
												<td><?= cek_sakit($data->user_id, $hari, $bulan, $tahun) ?></td>
												<td><?= cek_izin($data->user_id, $hari, $bulan, $tahun) ?></td>
												<td><?= cek_hadir_tepat($data->user_id, $hari, $bulan, $tahun) ?></td>
												<td><?= cek_terlambat($data->user_id, $hari, $bulan, $tahun) ?></td>
												<td><?= cek_bolos($data->user_id, $hari, $bulan, $tahun) ?></td>
											</tr>
										<?php } ?>
									<?php } else { ?>
										<tr>
											<td><?php echo nama_guru($user_id)  ?></td>
											<?php
											for ($x = 1; $x <= $hari; $x++) { ?>
												<?= cek_absen($user_id, $tahun . '/' . $bulan . '/' . $x, $result_holdaydate) ?>
											<?php } ?>
											<td><?= cek_alpha($user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
											<td><?= cek_sakit($user_id, $hari, $bulan, $tahun) ?></td>
											<td><?= cek_izin($user_id, $hari, $bulan, $tahun) ?></td>
											<td><?= cek_hadir_tepat($user_id, $hari, $bulan, $tahun) ?></td>
											<td><?= cek_terlambat($user_id, $hari, $bulan, $tahun) ?></td>
											<td><?= cek_bolos($user_id, $hari, $bulan, $tahun) ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<table class="table table-bordered table-sm" style="width: 30%;">
								<tr>
									<th class="table-warning">Kode</th>
									<th class="table-warning">Keterangan</th>
								</tr>
								<tr>
									<td>A</td>
									<td>Alpha</td>
								</tr>

								<tr>
									<td>S</td>
									<td>Sakit</td>
								</tr>
								<tr>
									<td>I</td>
									<td>Izin</td>
								</tr>
								<tr>
									<td>✓</td>
									<td>Hadir Tepat Waktu</td>
								</tr>
								<tr>
									<td style="background-color: grey;">✓</td>
									<td>Hadir Terlambat</td>
								</tr>
								<tr>
									<td style="background-color: #5353ec;">B</td>
									<td>Bolos/Masuk Tidak Absen Pulang</td>
								</tr>
								<tr>
									<td style="background-color: yellow;"></td>
									<td>Hari Libur</td>
								</tr>
								<tr>
									<td style="background-color: red;"></td>
									<td>Hari Minggu</td>
								</tr>
							</table>
						</div>

					</div>
				</body>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '.plsholder', function() {
		var dtl = $(this).data('detail')
		alert(dtl)
	})
</script>