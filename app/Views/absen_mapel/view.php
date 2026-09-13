<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php $db = \Config\Database::connect(); ?>
<div id="content" class="app-content">
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">REKAP ABSENSI KELAS</h4>
		</div>
		<div class="panel-body bg-white text-dark">
			<form action="<?= base_url('absen_mapel/laporan_mapel/' . $set_mapel_id) ?>" method="GET" target="_blank" class="mb-4">
				<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf"></i> Cetak PDF</button>
			</form>

			<div class="text-center">
				<img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
				<div style="padding: 0 150px;">
					<h3 class="mb-1"><?= $sett_apps->nama_sekolah ?></h3>
					<p class="mt-0"><?= $sett_apps->alamat_sekolah ?></p>
				</div>
				<hr style="border-top: 2px solid #000;">

				<?php
				$namaMapel = $db->query("SELECT * FROM sett_mapel
                    JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
                    JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
                    JOIN guru ON guru.guru_id = sett_mapel.guru_id
                    WHERE sett_mapel_id = ?", [$set_mapel_id])->getRow();
				?>

				<h4 class="my-4">Laporan Absen Mapel <?= $namaMapel->nama_mapel ?>, Kelas <?= $namaMapel->nama_kelas ?>, Pengajar <?= $namaMapel->nama_guru ?></h4>

				<div class="table-responsive px-4">
					<table class="table table-bordered table-sm text-dark align-middle border-dark">
						<thead class="bg-light">
							<?php
							$tanggalData = $db->query("SELECT * FROM absen_mapel WHERE set_mapel_id = ? GROUP BY tanggal ORDER BY tanggal ASC", [$set_mapel_id])->getResult();
							$jml = count($tanggalData);
							?>
							<tr>
								<th rowspan="2" class="text-center align-middle" style="width: 3%;">NO</th>
								<th rowspan="2" class="text-center align-middle" style="width: 20%;">Nama Siswa</th>
								<?php if ($jml > 0): ?>
									<th colspan="<?= $jml ?>" class="text-center align-middle">Tanggal Pertemuan</th>
								<?php endif; ?>
								<th colspan="5" class="text-center align-middle">Total Keterangan</th>
							</tr>
							<tr>
								<?php $tgl = []; ?>
								<?php foreach ($tanggalData as $value) : ?>
									<?php $tgl[] = $value->tanggal; ?>
									<td class="text-center fw-bold" style="width: 3%;"><?= date('d/m', strtotime($value->tanggal)) ?></td>
								<?php endforeach; ?>
								<td class="text-center fw-bold" style="width: 3%;">H</td>
								<td class="text-center fw-bold" style="width: 3%;">A</td>
								<td class="text-center fw-bold" style="width: 3%;">I</td>
								<td class="text-center fw-bold" style="width: 3%;">S</td>
								<td class="text-center fw-bold" style="width: 3%;">B</td>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1;
							foreach ($siswa as $value) : ?>
								<tr>
									<td class="text-center"><?= $no++ ?></td>
									<td><?= $value->nama_siswa ?></td>

									<!-- Render per tanggal menggunakan Helper (Helper CI4 kita otomatis mereturn tag <td>...</td>) -->
									<?php for ($x = 0; $x < $jml; $x++) : ?>
										<?= cek_absen_mapel($set_mapel_id, $value->siswa_id, $tgl[$x]) ?>
									<?php endfor; ?>

									<td class="text-center"><?= cek_hadir_mapel($set_mapel_id, $value->siswa_id) ?></td>
									<td class="text-center"><?= cek_alpha_mapel($set_mapel_id, $value->siswa_id) ?></td>
									<td class="text-center"><?= cek_ijin_mapel($set_mapel_id, $value->siswa_id) ?></td>
									<td class="text-center"><?= cek_sakit_mapel($set_mapel_id, $value->siswa_id) ?></td>
									<td class="text-center"><?= cek_bolos_mapel($set_mapel_id, $value->siswa_id) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<table class="table table-bordered table-sm mt-4 text-dark border-dark" style="width: 250px;">
						<tr class="table-warning">
							<th class="text-center">Kode</th>
							<th>Keterangan</th>
						</tr>
						<tr>
							<td class="text-center fw-bold">H</td>
							<td>Hadir</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">A</td>
							<td>Alpha</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">I</td>
							<td>Izin</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">S</td>
							<td>Sakit</td>
						</tr>
						<tr>
							<td class="text-center fw-bold">B</td>
							<td>Bolos</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>