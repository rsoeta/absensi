<?= $this->extend('web_user/template_user') ?>
<?= $this->section('content') ?>

<?php
$request = \Config\Services::request();
$segment2 = $request->getUri()->getTotalSegments() >= 2 ? $request->getUri()->getSegment(2) : '';
?>

<!-- Modal Preview Surat Keterangan (Opsional jika ingin dipakai di form edit) -->
<div class="modal fade" id="modal-dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fs-5">Pratinjau Surat Keterangan</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body p-2">
				<div class="ratio ratio-43" style="min-height: 350px;">
					<iframe src="<?= !empty($photo) ? base_url('assets/assets/img/izin/' . $photo) : '' ?>" frameborder="0" class="w-100 h-100"></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<h1 class="page-header mb-3">KELOLA DATA IZIN SAKIT</h1>
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">Form Pengajuan Izin / Sakit</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" class="form-control" name="user_id" id="user_id" value="<?= $user_id; ?>" />

					<div class="table-responsive">
						<table class="table table-bordered table-hover align-middle text-white">
							<tr>
								<td width='25%' class="fw-bold">Tanggal</td>
								<td>
									<input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= $tanggal; ?>" required />
								</td>
							</tr>

							<?php if ($segment2 == 'create_izin_sakit' || $segment2 == 'create_action_izin_sakit') : ?>
								<tr>
									<td class="fw-bold">Surat Keterangan</td>
									<td>
										<input type="file" class="form-control" name="photo" id="photo" required onchange="return validasiEkstensi()" />
										<small class="text-warning d-block mt-1">Format yang diizinkan: .jpg, .jpeg, .png, .pdf, .doc, .docx</small>
									</td>
								</tr>
							<?php else : ?>
								<tr>
									<td class="fw-bold">Surat Keterangan</td>
									<td>
										<?php if (!empty($photo)) : ?>
											<div class="mb-3">
												<a href="#modal-dialog" data-bs-toggle="modal" class="d-inline-block border rounded p-1 bg-dark">
													<iframe class="rounded" style="width: 100%; max-width: 300px; height: 180px;" src="<?= base_url('assets/assets/img/izin/' . $photo) ?>"></iframe>
												</a>
												<div class="small text-info">Klik untuk memperbesar pratinjau</div>
											</div>
										<?php endif; ?>
										<input type="hidden" name="photo_lama" value="<?= $photo ?>">
										<p class="text-warning mb-1">Note: Pilih file baru jika ingin mengubah surat keterangan.</p>
										<input type="file" class="form-control" name="photo" id="photo" onchange="return validasiEkstensi()" />
									</td>
								</tr>
							<?php endif; ?>

							<tr>
								<td class="fw-bold">Keterangan</td>
								<td>
									<select name="keterangan" class="form-control theSelect w-100" required>
										<option value="">- Pilih Keterangan -</option>
										<option value="Izin" <?= $keterangan == 'Izin' ? 'selected' : '' ?>>Izin</option>
										<option value="Sakit" <?= $keterangan == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
									</select>
								</td>
							</tr>

							<tr>
								<td class="fw-bold">Deskripsi</td>
								<td>
									<textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Masukkan alasan atau deskripsi pengajuan..."><?= $deskripsi; ?></textarea>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="izin_sakit_id" value="<?= $izin_sakit_id; ?>" />
									<div class="d-flex flex-wrap gap-2">
										<button type="submit" class="btn btn-danger btn-sm px-3 py-2"><i class="fas fa-save"></i> <?= $button ?></button>
										<a href="<?= site_url('dashboard_user/izin_sakit') ?>" class="btn btn-info btn-sm px-3 py-2 text-white"><i class="fas fa-undo"></i> Kembali</a>
									</div>
								</td>
							</tr>
						</table>
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
</script>

<script type="text/javascript">
	function validasiEkstensi() {
		var inputFile = document.getElementById('photo');
		var pathFile = inputFile.value;
		var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.pdf|\.doc|\.docx)$/i;
		if (!ekstensiOk.exec(pathFile)) {
			alert('Silakan upload file yang memiliki ekstensi .jpeg/.jpg/.png/.pdf/.doc/.docx');
			inputFile.value = '';
			return false;
		}
	}
</script>
<?= $this->endSection() ?>