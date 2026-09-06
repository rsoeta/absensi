<div id="content" class="app-content p-0">

	<div class="profile">
		<div class="profile-header">
			<div class="profile-header-cover"></div>
			<div class="profile-header-content">
				<div class="profile-header-info">
					<h4 class="mt-0 mb-1">
						<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
							<?= nama_guru($this->session->userdata('userid')) ?>
						<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
							<?= nama_pegawai($this->session->userdata('userid')) ?>
						<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
							<?= nama_siswa($this->session->userdata('userid')) ?>
						<?php } ?>
					</h4>
					<p class="mb-2">
						<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
							NIP :<?= $username ?>
						<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
							NIP : <?= $username ?>
						<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
							NISN : <?= $username ?>
						<?php } ?>
					</p>
					<a href="#" class="btn btn-xs btn-yellow">Edit Profile</a>
				</div>
			</div>
			<ul class="profile-header-tab nav nav-tabs">
				<li class="nav-item"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab"></a></li>
			</ul>
		</div>
	</div>
	<div class="profile-content">
		<div class="tab-content p-0">
			<div class="tab-pane fade active show" id="profile-about">
				<div class="table-responsive form-inline">
					<form action="<?= base_url() ?>Dashboard_user/update_profile" method="post" enctype="multipart/form-data">
						<table class="table table-profile align-middle">
							<tbody>
								<input type="hidden" class="form-control" name="user_id" id="user_id" placeholder="user_id" value="<?php echo $user_id; ?>" />
								<td class="field">Username</td>
								<td><input type="text" readonly class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" /></td>
								</tr>
								<tr class="highlight">
									<td class="field">Password</td>
									<td><input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" />
										<small style="color: red">(Biarkan kosong jika tidak diganti)</small>
									</td>
								</tr>
								<tr class="divider">
								<tr class="highlight">
									<td class="field">Photo</td>
									<td>
										<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
											<img style="width: 150px;height: 150px;border-radius: 5%;" src="<?= base_url() ?>assets/img/guru/<?= photo_guru($this->session->userdata('userid')) ?>" alt="" />
										<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
											<img style="width: 150px;height: 150px;border-radius: 5%;" src="<?= base_url() ?>assets/img/pegawai/<?= photo_pegawai($this->session->userdata('userid')) ?>" alt="" />
										<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
											<img style="width: 150px;height: 150px;border-radius: 5%;" src="<?= base_url() ?>assets/img/siswa/<?= photo_siswa($this->session->userdata('userid')) ?>" alt="" />
										<?php } ?>
										<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
											<input type="hidden" name="photo_lama" value="<?= photo_guru($this->session->userdata('userid')) ?>">
										<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
											<input type="hidden" name="photo_lama" value="<?= photo_pegawai($this->session->userdata('userid')) ?>">
										<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
											<input type="hidden" name="photo_lama" value="<?= photo_siswa($this->session->userdata('userid')) ?>">
										<?php } ?>
										<p style="color: red">Note :Pilih photo Jika Ingin Merubah photo</p>
										<input type="file" class="form-control" name="photo" id="photo" placeholder="photo" value="" onchange="return validasiEkstensi()" />
									</td>
								</tr>
								<tr class="highlight">
									<td class="field">&nbsp;</td>
									<td class="">
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
			// Preview photo
			if (inputFile.files && inputFile.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					document.getElementById('preview').innerHTML = '<iframe src="' + e.target.result + '" style="height:150px; width:200px"/>';
				};
				reader.readAsDataURL(inputFile.files[0]);
			}
		}
	}
</script>