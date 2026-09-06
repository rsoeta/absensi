<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">KELOLA DATA IZIN_SAKIT</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">

				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
					<thead>
						<table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
							<input type="hidden" class="form-control" name="user_id" id="user_id" placeholder="User Id" value="<?php echo $user_id; ?>" />
							<tr>
								<td width='200'>Tanggal </td>
								<td><input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" /></td>
							</tr>
							<?php if ($this->uri->segment(2) == 'create_izin_sakit' || $this->uri->segment(2) == 'create_action_izin_sakit') { ?>
								<tr>
									<td width='200'>Surat Keterangan </td>
									<td><input type="file" class="form-control" name="photo" id="photo" placeholder="photo" required="" value="" onchange="return validasiEkstensi()" />
									</td>
								</tr>
							<?php } else { ?>
								<div class="form-group">
									<tr>
										<td width='200'>Surat Keterangan </td>
										<td>
											<a href="#modal-dialog" data-bs-toggle="modal">
												<iframe style="width: 60%; height:350px" src="<?php echo base_url(); ?>assets/img/izin/<?= $photo ?>" style="width: 150px;height: 150px;border-radius: 5%;"></iframe></a>
											<input type="hidden" name="photo_lama" value="<?= $photo ?>">
											<p style="color: red">Note :Pilih Surat Keterangan Jika Ingin Merubah Surat Keterangan </p>
											<input type="file" class="form-control" name="photo" id="photo" placeholder="photo" value="" onchange="return validasiEkstensi()" />
										</td>

									</tr>
								</div>
							<?php } ?>
							<tr>
								<td>Keterangan </td>
								<td><select name="keterangan" class="form-control theSelect" value="<?= $keterangan ?>">
										<option value="">- Pilih -</option>
										<option value="Izin" <?php echo $keterangan == 'Izin' ? 'selected' : 'null' ?>>Izin</option>
										<option value="Sakit" <?php echo $keterangan == 'Sakit' ? 'selected' : 'null' ?>>Sakit</option>
									</select>
								</td>
							</tr>

							<tr>
								<td width='200'>Deskripsi </td>
								<td> <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="hidden" name="izin_sakit_id" value="<?php echo $izin_sakit_id; ?>" />
									<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?php echo $button ?></button>
									<a href="<?php echo site_url('Dashboard_user/izin_sakit') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
								</td>
							</tr>
					</thead>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".theSelect").select2();
	})
</script>
<script type="text/javascript">
	function validasiEkstensi() {
		var inputFile = document.getElementById('photo');
		var pathFile = inputFile.value;
		var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.pdf|\.doc|\.docx)$/i;
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