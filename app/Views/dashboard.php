<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php $db = \Config\Database::connect(); ?>
<div id="content" class="app-content">
	<div class="row">
		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-gradient-cyan-blue">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-stopwatch fa-fw"></i></div>
				<div class="stats-content">
					<div class="stats-title">ABSENSI HARI INI</div>
					<?php
					$query = "SELECT * FROM `absen` WHERE tanggal = '" . date('Y-m-d') . "' AND (status_masuk = 'Tepat Waktu' OR status_masuk ='Terlambat')";
					$absensi_today = $db->query($query)->getNumRows();
					$absensi_yesterday = $db->table('absen')->where('tanggal', date('Y-m-d', strtotime('-1 days')))->countAllResults();
					?>
					<div class="stats-number"><?= $absensi_today ?></div>
					<?php
					if ($absensi_today > $absensi_yesterday) {
						if ($absensi_yesterday == 0) {
							$absensi_yesterday = 1;
						}
						$percent = ($absensi_today - $absensi_yesterday) / $absensi_yesterday * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Meningkat dari hari kemarin (' . round($percent, 2) . '%)</div>';
					} else if ($absensi_today < $absensi_yesterday) {
						if ($absensi_today == 0) {
							$absensi_today = 1;
						}
						$percent = ($absensi_yesterday - $absensi_today) / $absensi_today * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Turun dari hari kemarin (-' . round($percent, 2) . '%)</div>';
					} else {
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: 100%;"></div></div>';
						echo '<div class="stats-desc">Sama dengan hari kemarin (0%)</div>';
					}
					?>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-gradient-red">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-exclamation-triangle fa-fw"></i></div>
				<div class="stats-content">
					<div class="stats-title">TELAT HARI INI</div>
					<?php
					$terlambat_today = $db->table('absen')->where(['tanggal' => date('Y-m-d'), 'status_masuk' => 'Terlambat'])->countAllResults();
					$terlambat_yesterday = $db->table('absen')->where(['tanggal' => date('Y-m-d', strtotime('-1 days')), 'status_masuk' => 'Terlambat'])->countAllResults();
					?>
					<div class="stats-number"><?= $terlambat_today ?></div>
					<?php
					if ($terlambat_today > $terlambat_yesterday) {
						if ($terlambat_yesterday == 0) {
							$terlambat_yesterday = 1;
						}
						$percent = ($terlambat_today - $terlambat_yesterday) / $terlambat_yesterday * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Meningkat dari hari kemarin (' . round($percent, 2) . '%)</div>';
					} else if ($terlambat_today < $terlambat_yesterday) {
						if ($terlambat_today == 0) {
							$terlambat_today = 1;
						}
						$percent = ($terlambat_yesterday - $terlambat_today) / $terlambat_today * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Turun dari hari kemarin (-' . round($percent, 2) . '%)</div>';
					} else {
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: 100%;"></div></div>';
						echo '<div class="stats-desc">Sama dengan hari kemarin (0%)</div>';
					}
					?>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-gradient-green">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-bell fa-fw"></i></div>
				<div class="stats-content">
					<div class="stats-title">TEPAT WAKTU HARI INI</div>
					<div class="stats-number">
						<?php
						$tepatwaktu_today = $db->table('absen')->where(['tanggal' => date('Y-m-d'), 'status_masuk' => 'Tepat Waktu'])->countAllResults();
						$tepatwaktu_yesterday = $db->table('absen')->where(['tanggal' => date('Y-m-d', strtotime('-1 days')), 'status_masuk' => 'Tepat Waktu'])->countAllResults();
						echo $tepatwaktu_today;
						?>
					</div>
					<?php
					if ($tepatwaktu_today > $tepatwaktu_yesterday) {
						if ($tepatwaktu_yesterday == 0) {
							$tepatwaktu_yesterday = 1;
						}
						$percent = ($tepatwaktu_today - $tepatwaktu_yesterday) / $tepatwaktu_yesterday * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Meningkat dari hari kemarin (' . round($percent, 2) . '%)</div>';
					} else if ($tepatwaktu_today < $tepatwaktu_yesterday) {
						if ($tepatwaktu_today == 0) {
							$tepatwaktu_today = 1;
						}
						$percent = ($tepatwaktu_yesterday - $tepatwaktu_today) / $tepatwaktu_today * 100;
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: ' . $percent . '%;"></div></div>';
						echo '<div class="stats-desc">Turun dari hari kemarin (-' . round($percent, 2) . '%)</div>';
					} else {
						echo '<div class="stats-progress progress"><div class="progress-bar" style="width: 100%;"></div></div>';
						echo '<div class="stats-desc">Sama dengan hari kemarin (0%)</div>';
					}
					?>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-gradient-indigo">
				<div class="stats-icon stats-icon-lg"><i class="fas fa-file-signature fa-fw"></i></div>
				<div class="stats-content">
					<div class="stats-title">PENGAJUAN MASUK (HARI INI)</div>
					<div class="stats-number">
						<?php
						$pengajuan_today = $db->table('izin_sakit')->where('tanggal', date('Y-m-d'))->countAllResults();
						$pengajuan_waitingapproval = $db->table('izin_sakit')->where(['status' => 'Waiting', 'tanggal >=' => date('Y-m-d')])->countAllResults();
						echo $pengajuan_today;
						?>
					</div>
					<div class="stats-progress progress">
						<div class="progress-bar" style="width: 76.3%;"></div>
					</div>
					<div class="stats-desc">
						<?php
						if ($pengajuan_waitingapproval > 0) {
							echo '<a href="' . base_url('izin_sakit') . '" style="text-decoration: none; color: white;">' . $pengajuan_waitingapproval . ' Pengajuan Perlu Persetujuan</a>';
						} else {
							echo 'Tidak ada pengajuan yang perlu disetujui';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-5">
			<div class="widget-chart with-sidebar inverse-mode">
				<div class="widget-chart-content bg-black bg-opacity-50">
					<h4 class="chart-title">
						Perkembangan Absensi
						<small>Data absensi bulan ini</small>
					</h4>
					<canvas id="visitors-line-chart" class="widget-chart-full-width dark-mode" style="height: 260px; width: 100%;"></canvas>
				</div>
				<div class="widget-chart-sidebar bg-black bg-opacity-70">
					<div class="chart-number">
						<?= $absensi_today ?>
						<small>Total Absen Hari ini</small>
					</div>
					<div class="flex-grow-1 d-flex align-items-center">
						<canvas id="visitors-donut-chart" class="dark-mode" style="height: 180px; width: 100%; padding: 1rem;"></canvas>
					</div>
					<ul class="chart-legend chart-legend-owo fs-11px"></ul>
				</div>
			</div>
		</div>

		<div class="col-xl-2">
			<div class="panel panel-inverse" data-sortable-id="index-1">
				<div class="panel-heading">
					<h4 class="panel-title">Absen Geolocation Hari Ini</h4>
				</div>
				<div class="list-group list-group-flush">
					<?php if (!empty($geo)): foreach ($geo as $row) { ?>
							<a href="javascript:;" class="list-group-item rounded-0 list-group-item-action list-group-item-inverse d-flex justify-content-between align-items-center text-ellipsis">
								<?php if ($row->level_id == 2) {
									echo "Guru";
								} else if ($row->level_id == 3) {
									echo "Pegawai";
								} else {
									echo "Siswa";
								} ?>
								<span class="badge bg-gray fs-10px"><?= $row->total ?> Data</span>
							</a>
					<?php }
					endif; ?>
				</div>
			</div>
		</div>

		<div class="col-xl-2">
			<div class="panel panel-inverse" data-sortable-id="index-1">
				<div class="panel-heading">
					<h4 class="panel-title">Jumlah Entitas</h4>
				</div>
				<div class="container" style="height: 170px; text-align: center;">
					<i class="fas fa-book" style="font-size: 8rem; margin: 15px auto;"></i>
				</div>
				<div class="list-group list-group-flush">
					<a href="javascript:;" class="list-group-item rounded-0 list-group-item-action list-group-item-inverse d-flex justify-content-between align-items-center text-ellipsis">
						Guru
						<span class="badge bg-green fs-10px"><?= $db->table('guru')->countAllResults(); ?> Data</span>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action list-group-item-inverse d-flex justify-content-between align-items-center text-ellipsis">
						Pegawai
						<span class="badge bg-warning fs-10px"><?= $db->table('pegawai')->countAllResults(); ?> Data</span>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action list-group-item-inverse d-flex justify-content-between align-items-center text-ellipsis">
						Murid
						<span class="badge bg-indigo fs-10px"><?= $db->table('siswa')->countAllResults(); ?> Data</span>
					</a>
				</div>
			</div>
		</div>

		<div class="col-xl-3">
			<div class="panel panel-inverse" data-sortable-id="index-2">
				<div class="panel-heading">
					<h4 class="panel-title">Rerata Jam Absen</h4>
				</div>
				<div style="padding: 1rem;">
					<select class="form-select form-select-sm select-rerata-jam-filter">
						<option value="jam_masuk">Jam Masuk</option>
						<option value="jam_pulang">Jam Pulang</option>
					</select>
					<p class="mt-1 mb-0" style="text-align: center;">S = Siswa | G = Guru | P = Pegawai</p>
				</div>
				<div class="table-responsive">
					<table class="table table-panel align-middle mb-0">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>S</th>
								<th>G</th>
								<th>P</th>
							</tr>
						</thead>
						<tbody class="wrapper-rerata-jam">
							<?php
							for ($i = 0; $i < 5; $i++) {
								$date = date('Y-m-d', strtotime('-' . $i . ' days'));
							?>
								<tr <?= $i == 0 ?  'style="font-size: 15px; font-weight: bold; font-style: italic;"' : '' ?>>
									<td nowrap=""><?= $date ?></td>
									<td><?php echo $classnyak->get_average_time($date, 4) ?></td>
									<td><?php echo $classnyak->get_average_time($date, 2) ?></td>
									<td><?php echo $classnyak->get_average_time($date, 3) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url('assets/js/demo/dashboard-v2.js') ?>"></script>

<?= $this->endSection() ?>