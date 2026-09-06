<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">REKAP ABSEN SISWA</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">

				<form action="<?= base_url() ?>absen_mapel_peminatan/laporan_mapel/<?= $mapel_peminatan_id ?>" method="GET" target="_blank">
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
						<?php $mapelPeminatan = $this->db->query("SELECT * from 
						mapel_peminatan
						join guru on guru.guru_id=mapel_peminatan.guru_id
						where id=$mapel_peminatan_id")->row();
						?>

						<h3>Laporan Absen Mapel Peminatan <?= $mapelPeminatan->nama_mapel_peminatan ?>, Pengajar <?= $mapelPeminatan->nama_guru ?></h3>
						<div style="padding-left: 50px; padding-top:30px; padding-bottom:30px;">
							<table class="table table-bordered table-sm">
								<thead>
									<?php $tanggal =  $this->db->query("SELECT * from absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id GROUP BY tanggal");
									$tanggalData = $tanggal->result();
									$jml = $tanggal->num_rows();

									?>
									<tr>
										<th rowspan="2" style="vertical-align: middle;">NO</th>
										<th rowspan="2" style="vertical-align: middle;">Nama siswa</th>
										<th colspan="<?= $jml ?>" style="vertical-align: middle;">Tanggal Pertemuan</th>
										<th colspan="5" style="vertical-align: middle;">Keterangan</th>
									</tr>
									<tr>

										<?php $tgl = array(); ?>
										<?php foreach ($tanggalData as $value) { ?>
											<?php array_push($tgl, $value->tanggal); ?>
											<td style="width: 2%;"><?= date('Y-m-d', strtotime($value->tanggal)) ?></td>
										<?php }  ?>
										<td style="width: 2%;">H</td>
										<td style="width: 2%;">A</td>
										<td style="width: 2%;">I</td>
										<td style="width: 2%;">S</td>
										<td style="width: 2%;">B</td>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1;
									foreach ($siswa as $value) { ?>
										<tr>
											<td><?= $no++ ?></td>
											<td><?= $value->nama_siswa ?></td>
											<?php
											for ($x = 0; $x < $jml; $x++) { ?>
												<?= cek_absen_mapel_peminatan($mapel_peminatan_id, $value->siswa_id, $tgl[$x]) ?>
											<?php } ?>
											<td><?= cek_hadir_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
											<td><?= cek_alpha_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
											<td><?= cek_ijin_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
											<td><?= cek_sakit_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
											<td><?= cek_bolos_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>

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
									<td>H</td>
									<td>Hadir</td>
								</tr>

								<tr>
									<td>A</td>
									<td>Alpha</td>
								</tr>
								<tr>
									<td>I</td>
									<td>Izin</td>
								</tr>
								<tr>
									<td>S</td>
									<td>Sakit</td>
								</tr>
								<tr>
									<td>B</td>
									<td>Bolos</td>
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