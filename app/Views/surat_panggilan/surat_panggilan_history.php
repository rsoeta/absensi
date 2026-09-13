<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Form Isian Waktu Panggilan <span id="cuts"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<!-- Action form dikosongkan karena akan diisi oleh jQuery -->
			<form id="download" action="" method="POST" target="_blank">
				<div class="modal-body">
					<div class="form-group mb-3">
						<label class="form-label" for="tgl">Hari/Tanggal</label>
						<input type="date" class="form-control" name="tgl" required>
					</div>
					<div class="form-group mb-3">
						<label class="form-label" for="waktu">Waktu</label>
						<input type="time" class="form-control" name="waktu" required>
					</div>
					<div class="form-group mb-3">
						<label class="form-label" for="tempat">Tempat</label>
						<input type="text" class="form-control" name="tempat" required>
					</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
$db = \Config\Database::connect();
$tgl_sekarang = date('Y-m-d');
$findtahunajaranbetweendatenow = $db->table('tahun_ajaran')
	->where('tgl_awal <=', $tgl_sekarang)
	->where('tgl_akhir >=', $tgl_sekarang)
	->get()->getRow();
?>

<div id="content" class="app-content">
	<h1 class="page-header"> HISTORY PANGGILAN</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">
				<?php
				if ($findtahunajaranbetweendatenow) {
					echo $findtahunajaranbetweendatenow->nama_tahun_ajaran . ' (' . $findtahunajaranbetweendatenow->tgl_awal . ' - ' . $findtahunajaranbetweendatenow->tgl_akhir . ')';
				} else {
					echo "Tidak ada tahun ajaran berjalan pada tanggal " . $tgl_sekarang;
				}
				?>
			</h4>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
					<thead>
						<tr>
							<th>No</th>
							<th>NISN</th>
							<th>Siswa</th>
							<th>Kelas</th>
							<th>Wali Siswa</th>
							<th>Terakhir Dipanggil</th>
							<th>No HP</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($surat_panggilan_data as $value) : ?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= $value->nisn ?></td>
								<td>
									<?php $foto = empty($value->photo) ? 'default.png' : $value->photo; ?>
									<img src="<?= base_url('assets/img/siswa/' . $foto) ?>" class="rounded h-30px my-n1 mx-n1" />&nbsp;
									<?= $value->nama_siswa ?>
								</td>
								<td><?= $value->nama_kelas ?></td>
								<td><?= $value->nama_wali_siswa ?></td>
								<td><?= $value->terakhir_dipanggil ?? '-' ?></td>
								<td><?= $value->no_hp_wali_siswa ?></td>
								<td>
									<?php if ($value->status_sp == 1) : ?>
										<span class="badge bg-success">Sudah Dipanggil</span> (<?= $value->last_updated ?>)
									<?php else : ?>
										<span class="badge bg-danger">Belum Dipanggil</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($value->status_sp == 0) : ?>
										<a href="<?= base_url('surat_panggilan/update_status_sp/' . encrypt_url($value->id_surat_panggilan)) ?>" class="btn btn-success btn-sm">
											<i class="fa fa-check"></i> Selesai
										</a>
									<?php endif; ?>
									<a id="btn_cetak_surat" href="#modal-dialog" data-bs-toggle="modal" data-id_surat_panggilan="<?= encrypt_url($value->id_surat_panggilan) ?>" class="btn btn-primary btn-sm">
										<i class="fas fa-print"></i> Cetak
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '#btn_cetak_surat', function() {
		var id_surat_panggilan = $(this).data('id_surat_panggilan');
		// Arahkan action form ke fungsi cetak di Controller
		$('#modal-dialog #download').attr("action", "<?= base_url('surat_panggilan/cetak/') ?>" + id_surat_panggilan);
	});
</script>

<?= $this->endSection() ?>