<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Siswa Read</h4>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-bordered table-td-valign-middle">
					<tr>
						<td width="200">NISN</td>
						<td><?= $nisn ?></td>
					</tr>
					<tr>
						<td>Nama Siswa</td>
						<td><?= $nama_siswa ?></td>
					</tr>
					<tr>
						<td>Jenis Kelamin</td>
						<td><?= $jk_kelamin ?></td>
					</tr>
					<tr>
						<td>Kelas ID</td>
						<td><?= $kelas_id ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td><?= $alamat ?></td>
					</tr>
					<tr>
						<td>Tempat Lahir</td>
						<td><?= $tempat_lahir ?></td>
					</tr>
					<tr>
						<td>Tanggal Lahir</td>
						<td><?= $tanggal_lahir ?></td>
					</tr>
					<tr>
						<td>Photo</td>
						<td><?= $photo ?></td>
					</tr>
					<tr>
						<td></td>
						<td><a href="<?= base_url('siswa/daftar_siswa?kelas_id=' . $kelas_id) ?>" class="btn btn-default">Cancel</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>