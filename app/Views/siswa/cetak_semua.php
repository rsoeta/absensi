<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8'>
	<title>Cetak Kartu Siswa Massal</title>
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
		<div class="card border" style="width: 30rem;float :left; margin:10px; padding:10px">
			<p style="color: white; margin-top: 13px; right:50px; position: absolute;font-family: Cambria;font-size: 15px;"><strong>MAJELIS PENDIDIKAN DASAR DAN MENENGAH</strong></p>
			<p style="color: white; margin-top: 30px; right:100px; position: absolute;font-family: Cambria;font-size: 16px;"><strong>MUHAMMADIYAH PAKENJENG</strong></p>
			<p style="color: white; margin-top: 50px; right:80px; position: absolute;font-family: Cambria;font-size: 14px;text-transform: uppercase;"><strong><?= $sett_apps->nama_sekolah ?></strong></p>
			<p style="margin-top: 90px; right:130px; position: absolute;font-family: Cambria;font-size: 20px;"><strong>KARTU SISWA</strong></p>

			<table style="margin-top: 130px; position: absolute; right:130px; text-align: right; font-family: Cambria;font-size: 12px;">
				<tr>
					<td>NISN</td>
				</tr>
				<tr>
					<td><?= $s->nisn ?></td>
				</tr>
				<tr>
					<td>Nama</td>
				</tr>
				<tr>
					<td><strong style="font-size: 10px;"><?= $s->nama_siswa ?></strong></td>
				</tr>
				<tr>
					<td>Tempat, Tanggal lahir</td>
				</tr>
				<tr>
					<td><?= $s->tempat_lahir ?>, <?= $s->tanggal_lahir ?></td>
				</tr>
				<tr>
					<td>Alamat <?= $s->alamat ?></td>
				</tr>
			</table>

			<p style="font-family:Verdana; right:50px; margin-top: 256px; text-align:right; padding-left: 10px;font-size: 8px; position: absolute;">Alamat Sekolah : <?= $sett_apps->alamat_sekolah ?> </p>

			<img class="card-img-top" src="<?= base_url('assets/img/kartu/birunom.png') ?>">

			<?php $foto_tampil = empty($s->photo) ? base_url('assets/img/icon/default.png') : base_url('assets/img/siswa/' . $s->photo); ?>
			<img style="border: 1px solid #ffffff;position: absolute;right: 30px;margin-top: 130px; object-fit:cover;" src="<?= $foto_tampil ?>" width="85px" height="100px">
			<img style="position: absolute;margin-left: 35px;margin-top: 130px;" src="<?= base_url('assets/img/qr/siswa/' . $s->qr_code) ?>" width="120px" height="120px">
			<img style="position: absolute;margin-left: 30px;margin-top: 10px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>" width="65px" height="65px">
		</div>
	<?php endforeach; ?>
</body>

</html>