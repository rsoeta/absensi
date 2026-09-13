<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Photo <span id="cuts"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="photo_siswa" style="max-width: 100%; border-radius: 8px;" />
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				<a class="btn btn-primary" id="download" href="" download><i class="fas fa-download"></i> Download</a>
			</div>
		</div>
	</div>
</div>

<div id="content" class="app-content">
	<div class="row">
		<!-- Panel Filter Tanggal -->
		<div class="col-xl-4">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">FILTER TAHUN AJARAN</h4>
				</div>
				<div class="panel-body">
					<!-- Action dinamis berdasarkan Controller mana yang memanggil -->
					<form action="<?= $action_url ?>" method="get">
						<table class="table table-bordered table-td-valign-middle text-white align-middle">
							<tr>
								<td>Tahun Ajaran</td>
								<td>
									<select name="tahun_ajaran_id" class="form-control theSelect" required>
										<option value="">-- Pilih --</option>
										<?php foreach ($tahun_ajaran_data as $data) : ?>
											<option value="<?= $data->tahun_ajaran_id ?>" <?= ($tahun_ajar == $data->tahun_ajaran_id) ? 'selected' : '' ?>>
												<?= $data->nama_tahun_ajaran ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Ranking</button>
									<?php if (request()->getGet('tahun_ajaran_id')) : ?>
										<a href="<?= $action_url ?>" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Reset</a>
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>

		<!-- Panel Data Ranking -->
		<div class="col-xl-8">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">RANKING KEDISIPLINAN - <?= $jenis_laporan ?></h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table id="data-table-default" class="table table-bordered table-hover table-striped text-white align-middle text-center">
							<thead class="table-light text-dark">
								<tr>
									<th>No</th>
									<th>Photo</th>
									<th class="text-start">Nama Siswa</th>
									<th>Kelas</th>
									<th>H</th>
									<th>A</th>
									<th>B</th>
									<th>I</th>
									<th>S</th>
									<th class="bg-danger text-white">Total Poin</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($dataRangking)): ?>
									<?php $no = 1; ?>
									<?php foreach ($dataRangking as $siswa): ?>
										<tr>
											<td><?= $no++ ?></td>
											<td>
												<?php $foto = empty($siswa->photo) ? 'default.png' : $siswa->photo; ?>
												<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?= $foto ?>" data-nama_siswa="<?= htmlspecialchars($siswa->nama_siswa, ENT_QUOTES, 'UTF-8') ?>">
													<img src="<?= base_url('assets/img/siswa/' . $foto) ?>" class="rounded h-30px" style="object-fit: cover; width: 30px;" />
												</a>
											</td>
											<td class="text-start"><?= htmlspecialchars($siswa->nama_siswa, ENT_QUOTES, 'UTF-8') ?></td>
											<td><?= htmlspecialchars($siswa->nama_kelas, ENT_QUOTES, 'UTF-8') ?></td>
											<td><?= $siswa->total_Hadir ?></td>
											<td><?= $siswa->total_Alpha ?></td>
											<td><?= $siswa->total_Bolos ?></td>
											<td><?= $siswa->total_Izin ?></td>
											<td><?= $siswa->total_Sakit ?></td>
											<td class="fw-bold fs-5 text-danger"><?= $siswa->total_poin ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="10" class="text-center text-warning py-3">Silakan pilih Tahun Ajaran terlebih dahulu.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(".theSelect").select2();
	});

	$(document).on('click', '#view_gambar', function() {
		var nama_siswa = $(this).data('nama_siswa');
		var photo = $(this).data('photo');
		var baseUrl = "<?= base_url() ?>";

		$('#modal-dialog #cuts').text(" - " + nama_siswa);
		$('#modal-dialog #photo_siswa').attr("src", baseUrl + "assets/img/siswa/" + photo);
		$('#modal-dialog #download').attr("href", baseUrl + "assets/img/siswa/" + photo);
	});
</script>

<?= $this->endSection() ?>