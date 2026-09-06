<div id="content" class="app-content p-0">

	<div class="profile">
		<div class="profile-header">

			<div class="profile-header-cover"></div>


			<div class="profile-header-content">
				<div class="profile-header-img">
					<!-- <?php $this->fungsi->user_login()->photo; ?>
					<img src="<?= base_url() ?>assets/img/user/<?= $this->fungsi->user_login()->photo; ?>" alt=""> -->
				</div>
				<div class="profile-header-info">
					<!-- <h4 class="mt-0 mb-1"><?= ucfirst($this->fungsi->user_login()->nama_user) ?></h4>
					<p class="mb-2"><?= ucfirst($this->fungsi->user_login()->email) ?></p> -->
					<a href="#" class="btn btn-xs btn-yellow">Edit Profile</a>
				</div>
			</div>

			<ul class="profile-header-tab nav nav-tabs">
				<li class="nav-item"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab">ABOUT</a></li>
			</ul>

		</div>
	</div>


	<div class="profile-content">

		<div class="tab-content p-0">

			<div class="tab-pane fade active show" id="profile-about">

				<div class="table-responsive form-inline">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
						<table class="table table-profile align-middle">
							<thead>
								<tr>
									<th></th>
									<th>
										<!-- <h4><?= ucfirst($this->fungsi->user_login()->nama_user) ?><small><?= ucfirst($this->fungsi->user_login()->email) ?></small></h4> -->
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="highlight">
									<td class="field">Nama User </td>
									<td><input type="text" class="form-control" name="nama_user" id="nama_user" placeholder="Nama User" value="<?php echo $nama_user; ?>" /></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<!-- <input type="hidden" class="form-control" name="user_id" id="user_id" placeholder="user_id" value="<?php echo $user_id; ?>" />
								<input type="hidden" class="form-control" name="level_id" id="level_id" placeholder="level_id" value="<?php echo $level_id; ?>" /> -->
								<!-- <tr class="highlight">
									<td class="field">Username </td>
									<td><input readonly="" type="text" class="form-control" name="username" id="Username" placeholder="Username" value="<?php echo $username; ?>" /></td>
								</tr> -->
								<tr class="divider">
									<td colspan="2"></td>
								</tr>

								<?php if ($this->uri->segment(2) == "create" || $this->uri->segment(2) == "create_action") { ?>
									<tr class="highlight">
										<td class="field">Password </td>
										<td><input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>" /></td>
									</tr>
								<?php } else { ?>
									<tr class="highlight">
										<td class="field">Password </td>
										<td><input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" />
											<small style="color: red">(Biarkan kosong jika tidak diganti)</small>
										</td>
									</tr>
								<?php } ?>


								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr class="highlight">
									<td class="field">Email </td>
									<td><input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" /></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr class="highlight">
									<td class="field">No Hp </td>
									<td><input type="text" class="form-control" name="no_hp_user" id="no_hp_user" placeholder="No Hp User" value="<?php echo $no_hp_user; ?>" /></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr class="highlight">
									<td class="field"'>Alamat User </td><td>
          <textarea class="textarea form-control" id="wysihtml5" name="alamat_user" placeholder="Alamat User" rows="5"><?php echo $alamat_user; ?></textarea>
        </td></tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
			                    <tr class="highlight">
			                        <td class="field">Photo </td>
			                        <td>
			                            <a href="#modal-dialog" data-bs-toggle="modal">
											<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
												<img style="width: 150px;height: 150px;border-radius: 50%;" src="<?= base_url() ?>assets/img/guru/<?= photo_guru($this->session->userdata('userid')) ?>" alt="" />
											<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
												<img style="width: 150px;height: 150px;border-radius: 50%;" src="<?= base_url() ?>assets/img/pegawai/<?= photo_pegawai($this->session->userdata('userid')) ?>" alt="" />
											<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
												<img style="width: 150px;height: 150px;border-radius: 50%;" src="<?= base_url() ?>assets/img/siswa/<?= photo_siswa($this->session->userdata('userid')) ?>" alt="" />
											<?php } ?>
										</a>
			                            <input type="hidden" name="photo_lama" value="<?= $photo ?>">
			                            <p style="color: red">Note :Pilih photo Jika Ingin Merubah photo</p>
			                            <input type="file" class="form-control" name="photo" id="photo" placeholder="photo" value="" onchange="return validasiEkstensi()" />
			                        </td>
			                    </tr>

	<tr class="divider">
		<td colspan="2"></td>
	</tr>

	<tr class="highlight">
		<td class="field">&nbsp;</td>
		<td class="">
			<button type="submit" class="btn btn-primary w-150px"><i class="fas fa-save"></i> <?php echo $button ?></button> 
			<a href="<?php echo site_url('dashboard') ?>" class="btn btn-white border-0 w-150px ms-5px"><i class="fas fa-undo"></i> Cancel</a>
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

<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Photo <?php echo $nama_user; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <img src="<?php echo base_url(); ?>assets/img/user/<?= $photo ?>" width="100%" />
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>