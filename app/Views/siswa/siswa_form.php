<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">KELOLA DATA SISWA</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered table-hover">
								<tr>
									<td width="200">NISN</td>
									<td>
										<input type="text" class="form-control" name="nisn" placeholder="NISN" value="<?= $nisn ?>" required />
										<input type="hidden" name="nisn_lama" value="<?= $nisn_lama ?? '' ?>" />
									</td>
								</tr>
								<tr>
									<td>Nama Siswa</td>
									<td><input type="text" class="form-control" name="nama_siswa" placeholder="Nama Siswa" value="<?= $nama_siswa ?>" required /></td>
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
									<td>Kelas</td>
									<td><select name="kelas_id" class="form-control theSelect" required>
											<option value="">-- Pilih --</option>
											<?php foreach ($kelas as $k) : ?>
												<option value="<?= $k->kelas_id ?>" <?= $kelas_id == $k->kelas_id ? 'selected' : '' ?>><?= $k->nama_kelas ?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td><textarea class="form-control" rows="3" name="alamat" required><?= $alamat ?></textarea></td>
								</tr>
								<tr>
									<td>Tempat Lahir</td>
									<td><input type="text" class="form-control" name="tempat_lahir" value="<?= $tempat_lahir ?>" required /></td>
								</tr>
								<tr>
									<td>Tanggal Lahir</td>
									<td><input type="date" class="form-control" name="tanggal_lahir" value="<?= $tanggal_lahir ?>" required /></td>
								</tr>
								<tr>
									<td>Nama Wali</td>
									<td><input type="text" class="form-control" name="nama_wali_siswa" value="<?= $nama_wali_siswa ?>" required /></td>
								</tr>
								<tr>
									<td>No HP Wali</td>
									<td><input type="text" class="form-control" name="no_hp_wali_siswa" value="<?= $no_hp_wali_siswa ?>" required /></td>
								</tr>

								<?php if (url_is('siswa/create*')) : ?>
									<tr>
										<td>Photo</td>
										<td><input type="file" class="form-control" name="photo" accept=".jpg,.png,.jpeg" onchange="return validasiEkstensi()" /></td>
									</tr>
									<tr>
										<td>Password</td>
										<td><input type="password" class="form-control" name="password" required /></td>
									</tr>
								<?php else : ?>
									<tr>
										<td>Photo</td>
										<td>
											<?php $foto_tampil = empty($photo) ? 'default.png' : $photo; ?>
											<img src="<?= base_url('assets/img/siswa/' . $foto_tampil) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:5px;" class="mb-2">
											<input type="hidden" name="photo_lama" value="<?= $foto_tampil ?>">
											<input type="file" class="form-control" name="photo" accept=".jpg,.png,.jpeg" onchange="return validasiEkstensi()" />
										</td>
									</tr>
									<tr>
										<td>Password</td>
										<td><input type="password" class="form-control" name="password" placeholder="Kosongkan jika tak diubah" /></td>
									</tr>
								<?php endif; ?>
							</table>
						</div>

						<div class="col-md-6">
							<table class="table table-bordered table-hover">
								<tr>
									<td>Agama</td>
									<td><select name="agama" class="form-control theSelect">
											<option value="">- Pilih -</option>
											<?php foreach (['Islam', 'Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm) : ?>
												<option value="<?= $agm ?>" <?= $agama == $agm ? 'selected' : '' ?>><?= $agm ?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>NIK</td>
									<td><input type="text" class="form-control" name="nik" value="<?= $nik ?>" /></td>
								</tr>
								<tr>
									<td>Penerima PIP</td>
									<td><select name="penerima_pip" class="form-control theSelect">
											<option value="">- Pilih -</option>
											<option value="Ya" <?= $penerima_pip == 'Ya' ? 'selected' : '' ?>>Ya</option>
											<option value="Tidak" <?= $penerima_pip == 'Tidak' ? 'selected' : '' ?>>Tidak</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Asal Sekolah</td>
									<td><input type="text" class="form-control" name="asal_sekolah" value="<?= $asal_sekolah ?>" /></td>
								</tr>
								<tr>
									<td>No WA Siswa</td>
									<td><input type="text" class="form-control" name="no_wa_siswa" value="<?= $no_wa_siswa ?>" /></td>
								</tr>
								<tr>
									<td>Hobi</td>
									<td><input type="text" class="form-control" name="hobi" value="<?= $hobi ?>" /></td>
								</tr>
								<tr>
									<td>Email</td>
									<td><input type="email" class="form-control" name="email" value="<?= $email ?>" /></td>
								</tr>
								<tr>
									<td>Nama Ayah</td>
									<td><input type="text" class="form-control" name="nama_ayah" value="<?= $nama_ayah ?>" /></td>
								</tr>
								<tr>
									<td>Pekerjaan Ayah</td>
									<td><input type="text" class="form-control" name="pekerjaan_ayah" value="<?= $pekerjaan_ayah ?>" /></td>
								</tr>
								<tr>
									<td>Nama Ibu</td>
									<td><input type="text" class="form-control" name="nama_ibu" value="<?= $nama_ibu ?>" /></td>
								</tr>
								<tr>
									<td>Pekerjaan Ibu</td>
									<td><input type="text" class="form-control" name="pekerjaan_ibu" value="<?= $pekerjaan_ibu ?>" /></td>
								</tr>
								<tr>
									<td>Penerima KPS</td>
									<td><select name="penerima_kps" class="form-control theSelect">
											<option value="">- Pilih -</option>
											<option value="Ya" <?= $penerima_kps == 'Ya' ? 'selected' : '' ?>>Ya</option>
											<option value="Tidak" <?= $penerima_kps == 'Tidak' ? 'selected' : '' ?>>Tidak</option>
										</select>
									</td>
								</tr>
								<?php if (url_is('siswa/create*')) : ?>
									<tr>
										<td>Notifikasi Wali?</td>
										<td><select name="notif" class="form-control theSelect">
												<option value="Tidak">Tidak</option>
												<option value="Ya">Ya</option>
											</select>
										</td>
									</tr>
								<?php endif; ?>
							</table>
						</div>

						<div class="col-12 mt-3 text-end">
							<input type="hidden" name="siswa_id" value="<?= $siswa_id ?>" />
							<a href="<?= base_url('siswa/daftar_siswa?kelas_id=' . $kelas_id) ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
							<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
						</div>
					</div>
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
			alert('Hanya diperbolehkan .jpeg/.jpg/.png');
			inputFile.value = '';
			return false;
		}
	}
</script>
<?= $this->endSection() ?>