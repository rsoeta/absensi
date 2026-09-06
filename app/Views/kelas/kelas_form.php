<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">KELOLA DATA KELAS</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action ?>" method="post">
					<table class="table table-bordered table-hover table-td-valign-middle">
						<tr>
							<td width="200">Jenjang</td>
							<td>
								<select name="jenjang_id" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php foreach ($jenjang as $data) : ?>
										<option value="<?= $data->jenjang_id ?>" <?= $jenjang_id == $data->jenjang_id ? 'selected' : '' ?>><?= $data->nama_jenjang ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Nama Kelas</td>
							<td><input type="text" class="form-control" name="nama_kelas" id="nama_kelas" placeholder="Nama Kelas" value="<?= $nama_kelas ?>" required /></td>
						</tr>
						<tr>
							<td>Walikelas</td>
							<td>
								<select name="walikelas" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php foreach ($guru as $data) : ?>
										<option value="<?= $data->guru_id ?>" <?= $walikelas == $data->guru_id ? 'selected' : '' ?>><?= $data->nama_guru ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" name="kelas_id" value="<?= $kelas_id ?>" />
								<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
								<a href="<?= base_url('kelas') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
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
	})
</script>
<?= $this->endSection() ?>