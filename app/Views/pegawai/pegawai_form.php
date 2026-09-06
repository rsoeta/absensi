<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">KELOLA DATA PEGAWAI</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
					<table class="table table-bordered table-hover table-td-valign-middle">
						<tr>
							<td width='200'>NIP</td>
							<td>
								<input type="text" class="form-control" name="nip" id="nip" placeholder="NIP" value="<?= $nip ?>" required />
								<input type="hidden" name="nip_lama" value="<?= $nip_lama ?? '' ?>" />
							</td>
						</tr>
						<tr>
							<td>Nama Pegawai</td>
							<td><input type="text" class="form-control" name="nama_pegawai" id="nama_pegawai" placeholder="Nama Pegawai" value="<?= $nama_pegawai ?>" required /></td>
						</tr>
						<tr>
							<td>Jenis Kelamin</td>
							<td><select name="jk_kelamin" class="form-control theSelect" required>
									<option value="">- Pilih -</option>
									<option value="Laki Laki" <?= $jk_kelamin == 'Laki Laki' ? 'selected' : '' ?>>Laki Laki</option>
									<option value="Perempuan" <?= $jk_kelamin == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Status Pegawai</td>
							<td>
								<select name="status_pegawai_id" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php foreach ($status_pegawai as $data) : ?>
										<option value="<?= $data->status_pegawai_id ?>" <?= $status_pegawai_id == $data->status_pegawai_id ? 'selected' : '' ?>><?= $data->nama_status_pegawai ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td><input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?= $alamat ?>" required /></td>
						</tr>
						<tr>
							<td>No HP</td>
							<td><input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No Hp" value="<?= $no_hp ?>" required /></td>
						</tr>
						<tr>
							<td>Tempat Lahir</td>
							<td><input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="<?= $tempat_lahir ?>" required /></td>
						</tr>
						<tr>
							<td>Tanggal Lahir</td>
							<td><input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="<?= $tanggal_lahir ?>" required /></td>
						</tr>

						<?php if (url_is('pegawai/create*')) : ?>
							<tr>
								<td>Photo</td>
								<td><input type="file" class="form-control" name="photo" id="photo" accept=".jpg,.jpeg,.png" onchange="return validasiEkstensi()" /></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type="password" class="form-control" name="password" id="password" placeholder="Password" required /></td>
							</tr>
						<?php else : ?>
							<tr>
								<td>Photo</td>
								<td>
									<?php $foto_tampil = empty($photo) ? 'default.png' : $photo; ?>
									<img src="<?= base_url('assets/img/pegawai/' . $foto_tampil) ?>" style="width: 150px;height: 150px;border-radius: 5%; object-fit:cover;" class="mb-2">
									<input type="hidden" name="photo_lama" value="<?= $foto_tampil ?>">
									<p style="color: red; font-size:10px;">*Pilih photo baru jika ingin merubah photo</p>
									<input type="file" class="form-control" name="photo" id="photo" accept=".jpg,.jpeg,.png" onchange="return validasiEkstensi()" />
								</td>
							</tr>
							<tr>
								<td>Password</td>
								<td>
									<input type="password" class="form-control" name="password" id="password" placeholder="Password Baru" />
									<small style="color: red">(Biarkan kosong jika tidak diganti)</small>
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td></td>
							<td>
								<input type="hidden" name="pegawai_id" value="<?= $pegawai_id ?>" />
								<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
								<a href="<?= base_url('pegawai') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
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

	function validasiEkstensi() {
		var inputFile = document.getElementById('photo');
		var pathFile = inputFile.value;
		var ekstensiOk = /(\.jpg|\.jpeg|\.png)$/i;
		if (!ekstensiOk.exec(pathFile)) {
			alert('Silakan upload file yang memiliki ekstensi .jpeg/.jpg/.png');
			inputFile.value = '';
			return false;
		}
	}
</script>
<?= $this->endSection() ?>