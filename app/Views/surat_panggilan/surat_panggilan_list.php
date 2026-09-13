<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php
$db = \Config\Database::connect();
$tgl_sekarang = date('Y-m-d');
$findtahunajaranbetweendatenow = $db->table('tahun_ajaran')
	->where('tgl_awal <=', $tgl_sekarang)
	->where('tgl_akhir >=', $tgl_sekarang)
	->get()->getRow();
?>

<div id="content" class="app-content">
	<h1 class="page-header"> DAFTAR PANGGILAN</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Data Siswa Melampaui Batas Absensi</h4>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="tabel-panggil-siswa" class="table table-bordered table-hover text-white align-middle">
					<thead>
						<tr>
							<th>No</th>
							<th>NISN</th>
							<th>Siswa</th>
							<th>Kelas</th>
							<th>Wali Siswa</th>
							<th>Terakhir Dipanggil</th>
							<th>Alpha/Terlambat</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$deteksitahunajaran = $db->table('tahun_ajaran')->get()->getResult();

						if ($deteksitahunajaran && $findtahunajaranbetweendatenow) {
							$no = 1;
							foreach ($siswa_data as $siswa) {
								$getuserbyusername = $db->table('user')->where('username', $siswa->nisn)->get()->getRow();
								$cektanggalpanggilwalisiswa = $db->table('surat_panggilan')->where('nisn', $siswa->nisn)->orderBy('id_surat_panggilan', 'DESC')->limit(1)->get()->getRow();

								$tglresetawal = $cektanggalpanggilwalisiswa ? $cektanggalpanggilwalisiswa->terakhir_dipanggil : $findtahunajaranbetweendatenow->tgl_awal;

								if ($getuserbyusername) {
									$total_terlambat = hitung_terlambat($tglresetawal, $tgl_sekarang, $getuserbyusername->user_id);
									$total_alpha = hitung_alpha($tglresetawal, $tgl_sekarang, $getuserbyusername->user_id);

									if (($total_terlambat + $total_alpha) >= 3) {
										$foto = empty($siswa->photo) ? 'default.png' : $siswa->photo;
										$bg_t = $total_terlambat >= 3 ? 'bg-danger' : ($total_terlambat > 1 ? 'bg-warning' : 'bg-success');
										$bg_a = $total_alpha >= 3 ? 'bg-danger' : ($total_alpha > 1 ? 'bg-warning' : 'bg-success');
						?>
										<tr>
											<td><?= $no++ ?></td>
											<td><?= $siswa->nisn ?></td>
											<td>
												<img src="<?= base_url('assets/img/siswa/' . $foto) ?>" class="rounded h-30px my-n1 mx-n1" />&nbsp;
												<?= $siswa->nama_siswa ?>
											</td>
											<td><?= $siswa->nama_kelas ?></td>
											<td><?= $siswa->nama_wali_siswa ?></td>
											<td><?= $siswa->tgl_panggil_wali ?? 'N/A' ?></td>
											<td>
												<span class="badge <?= $bg_t ?>">Terlambat : <?= $total_terlambat ?></span><br>
												<span class="badge <?= $bg_a ?>">Tidak Masuk : <?= $total_alpha ?></span>
											</td>
											<td>
												<form class="generate_surat_form" method="post">
													<input type="hidden" name="id_siswa" value="<?= encrypt_url($siswa->siswa_id) ?>">
													<input type="hidden" name="nisn" value="<?= encrypt_url($siswa->nisn) ?>">
													<input type="hidden" name="tanggal_dipanggil" value="<?= $tgl_sekarang ?>">
													<input type="hidden" name="telat" value="<?= $total_terlambat ?>">
													<input type="hidden" name="tidakhadir" value="<?= $total_alpha ?>">
													<button type="submit" class="btn btn-primary btn-sm">Generate Surat Panggilan</button>
												</form>
											</td>
										</tr>
						<?php
									}
								}
							}
						} else {
							echo '<tr><td colspan="8" class="text-center text-warning">Tahun Ajaran aktif tidak ditemukan.</td></tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('submit', '.generate_surat_form', function(e) {
		e.preventDefault();
		var form = $(this);
		var url = "<?= base_url('surat_panggilan/generate') ?>";

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Anda akan mengenerate surat panggilan untuk siswa ini",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Generate Surat'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Mohon Tunggu',
					text: 'Generate Surat Sedang Berjalan',
					allowOutsideClick: false,
					showConfirmButton: false,
					didOpen: () => {
						Swal.showLoading()
					}
				});

				$.ajax({
					url: url,
					type: "POST",
					data: form.serialize(),
					dataType: "JSON",
					success: function(data) {
						if (data.status == 'success') {
							Swal.fire({
								title: 'Berhasil',
								text: 'Surat panggilan telah digenerate. Lanjut ke halaman cetak?',
								icon: 'success',
								showCancelButton: true,
								confirmButtonText: 'Ya, Cetak',
								cancelButtonText: 'Tutup'
							}).then((result) => {
								window.location.href = "<?= base_url('surat_panggilan?page=history') ?>";
							});
						} else {
							Swal.fire('Gagal', data.message, 'error');
						}
					}
				});
			}
		});
	});
</script>

<?= $this->endSection() ?>