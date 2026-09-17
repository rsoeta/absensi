<?= $this->extend('web_user/template_user') ?>
<?= $this->section('content') ?>

<?php
$userid = session()->get('userid');
$level_id = session()->get('level_id');
?>

<div id="content" class="app-content p-0">
	<div class="profile">
		<div class="profile-header">
			<div class="profile-header-cover"></div>
			<div class="profile-header-content">
				<div class="profile-header-info">
					<h4 class="mt-0 mb-1">
						<?php if ($level_id == '2') : ?>
							<?= ucwords(strtolower(nama_guru($userid))) ?>
						<?php elseif ($level_id == '3') : ?>
							<?= ucwords(strtolower(nama_pegawai($userid))) ?>
						<?php elseif ($level_id == '4') : ?>
							<?= ucwords(strtolower(nama_siswa($userid))) ?>
						<?php endif; ?>
					</h4>
					<p class="mb-2">
						<?php if ($level_id == '2' || $level_id == '3') : ?>
							NIP : <?= $username ?>
						<?php elseif ($level_id == '4') : ?>
							NISN : <?= $username ?>
						<?php endif; ?>
					</p>
					<a href="javascript:;" class="btn btn-xs btn-yellow">Edit Profile</a>
				</div>
			</div>
			<ul class="profile-header-tab nav nav-tabs">
				<li class="nav-item"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab">Profil & Pengaturan</a></li>
			</ul>
		</div>
	</div>

	<div class="profile-content p-4">
		<!-- Notifikasi Flash Message -->
		<?php if (session()->getFlashdata('message')) : ?>
			<div class="alert alert-success">
				<i class="fas fa-check-circle"></i> <?= session()->getFlashdata('message') ?>
			</div>
		<?php endif; ?>

		<div class="tab-content p-0">
			<div class="tab-pane fade active show" id="profile-about">
				<div class="table-responsive">
					<form action="<?= base_url('dashboard_user/update_profile') ?>" method="post" enctype="multipart/form-data">
						<table class="table table-profile align-middle text-white">
							<tbody>
								<input type="hidden" class="form-control" name="user_id" id="user_id" value="<?= $user_id; ?>" />
								<tr>
									<td class="field" width="20%">Username</td>
									<td><input type="text" readonly class="form-control bg-dark text-white" name="username" id="username" value="<?= $username; ?>" /></td>
								</tr>
								<tr class="highlight">
									<td class="field">Password Baru</td>
									<td>
										<input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru..." />
										<small class="text-danger">* Biarkan kosong jika tidak ingin mengganti password.</small>
									</td>
								</tr>
								<tr class="highlight">
									<td class="field">Foto Profil</td>
									<td>
										<div class="mb-2">
											<?php if ($level_id == '2') : ?>
												<img id="preview-foto" style="width: 150px; height: 150px; border-radius: 5%; object-fit: cover;" src="<?= base_url('assets/img/guru/' . photo_guru($userid)) ?>" alt="Foto Guru" />
												<input type="hidden" name="photo_lama" value="<?= photo_guru($userid) ?>">
											<?php elseif ($level_id == '3') : ?>
												<img id="preview-foto" style="width: 150px; height: 150px; border-radius: 5%; object-fit: cover;" src="<?= base_url('assets/img/pegawai/' . photo_pegawai($userid)) ?>" alt="Foto Pegawai" />
												<input type="hidden" name="photo_lama" value="<?= photo_pegawai($userid) ?>">
											<?php elseif ($level_id == '4') : ?>
												<img id="preview-foto" style="width: 150px; height: 150px; border-radius: 5%; object-fit: cover;" src="<?= base_url('assets/img/siswa/' . photo_siswa($userid)) ?>" alt="Foto Siswa" />
												<input type="hidden" name="photo_lama" value="<?= photo_siswa($userid) ?>">
											<?php endif; ?>
										</div>
										<p class="text-warning mb-1">Catatan: Pilih foto baru jika ingin mengubah foto profil.</p>
										<input type="file" class="form-control" name="photo" id="photo" onchange="return validasiEkstensi()" />
									</td>
								</tr>
								<tr class="highlight">
									<td class="field">&nbsp;</td>
									<td>
										<button type="submit" class="btn btn-primary w-150px"><i class="fas fa-save"></i> Update</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function validasiEkstensi() {
		var inputFile = document.getElementById('photo');
		var pathFile = inputFile.value;
		var ekstensiOk = /(\.jpg|\.jpeg|\.png)$/i;

		if (!ekstensiOk.exec(pathFile)) {
			alert('Silakan upload file yang memiliki ekstensi .jpeg/.jpg/.png');
			inputFile.value = '';
			return false;
		} else {
			// PREVIEW FOTO SECARA REALTIME
			if (inputFile.files && inputFile.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					document.getElementById('preview-foto').src = e.target.result;
				};
				reader.readAsDataURL(inputFile.files[0]);
			}
		}
	}
</script>
<?= $this->endSection() ?>