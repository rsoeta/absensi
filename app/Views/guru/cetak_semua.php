<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8'>
	<title>Cetak Kartu Guru Massal</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<style>
		@media print {
			* {
				-webkit-print-color-adjust: exact;
			}
		}

		@page {
			width: 21cm;
			min-height: 29.7cm;
			padding: 2cm;
			margin: 1cm auto;
			border: 1px #D3D3D3 solid;
			border-radius: 5px;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}

		table {
			border-spacing: 0px;
		}

		th,
		td {
			padding: 0px;
		}
	</style>
</head>

<body onload='window.print()' style="font-size: 12px;margin-top:0;position:absolute;">
	<?php foreach ($siswa as $s) : ?>
		<div class="card border" style="width: 30rem; float:left; margin:10px; padding:10px">
			<p style="color: white; margin-top: 10px; right:30px; position: absolute; font-family: Cambria; font-size: 18px; text-transform: uppercase;"><strong><?= $sett_apps->nama_sekolah ?></strong></p>
			<p style="color: white; margin-top: 30px; right:30px; position: absolute; font-family: Cambria; font-size: 25px;"><strong>KARTU GURU</strong></p>
			<table style="margin-top:110px; position: absolute; right:130px; text-align: right; font-family: Cambria; font-size: 12px;">
				<tr>
					<td>NIP</td>
				</tr>
				<tr>
					<td><?= $s->nip ?></td>
				</tr>
				<tr>
					<td>Nama</td>
				</tr>
				<tr>
					<td><strong style="font-size: 12px;"><?= $s->nama_guru ?></strong></td>
				</tr>
				<tr>
					<td>Tempat, Tanggal lahir</td>
				</tr>
				<tr>
					<td><?= $s->tempat_lahir ?>, <?= $s->tanggal_lahir ?></td>
				</tr>
			</table>
			<p style="font-family:Verdana; right:50px; margin-top: 243px; text-align:right; padding-left: 10px;font-size: 8px; position: absolute;">Alamat Sekolah : <?= $sett_apps->alamat_sekolah ?> </p>

			<img class="card-img-top" src="<?= base_url('assets/img/kartu/birunom.png') ?>">
			<?php $foto_tampil = empty($s->photo) ? 'default.png' : $s->photo; ?>
			<img style="border: 1px solid #ffffff;position: absolute;right: 30px;margin-top: 115px; object-fit:cover;" src="<?= base_url('assets/img/guru/' . $foto_tampil) ?>" width="85px" height="100px">
			<img style="position: absolute;margin-left: 35px;margin-top: 115px;" src="<?= base_url('assets/img/qr/guru/' . $s->qr_code) ?>" width="120px" height="120px">
			<img style="position: absolute;margin-left: 30px;margin-top: 10px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>" width="65px" height="65px">
		</div>
	<?php endforeach; ?>
</body>

</html>