	<!-- #modal-dialog -->
	<div class="modal fade" id="modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Photo <span id="cuts"></span></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<img src="" id="photo_siswa" width="100%" />
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
					<a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
				</div>
			</div>
		</div>
	</div>

	<?php
	// $tgl_sekarang = date('2022-02-27');
	$tgl_sekarang = date('Y-m-d');
	$findtahunajaranbetweendatenow = $this->db->query("SELECT * FROM tahun_ajaran WHERE tgl_awal <= '$tgl_sekarang' AND tgl_akhir >= '$tgl_sekarang'")->row();

	?>

	<div id="content" class="app-content">
		<h1 class="page-header"> DAFTAR PANGGILAN</h1>
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
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="box-body">
								<div class="col-md-4">
								</div>

								<br>
								<div class="box-body" style="overflow-x: scroll; ">


									<table id="tabel-panggil-siswa" class="table table-bordered table-hover table-td-valign-middle text-white">
										<thead>
											<tr>

												<th style="width: 5%;">No</th>
												<th>Nisn</th>
												<th>Siswa</th>
												<th>Kelas</th>
												<th>Wali Siswa</th>
												<th>No HP Wali Siswa</th>
												<th>Terakhir Dipanggil</th>
												<th>Tidak Masuk/Terlambat</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php

											$deteksitahunajaran = $this->db->query("SELECT * FROM tahun_ajaran")->result();

											if ($deteksitahunajaran) {
												if ($findtahunajaranbetweendatenow) {
													$no = 1;
													foreach ($siswa_data as $siswa) {
														$getuserbyusername = $this->db->query("SELECT * FROM user WHERE username = '" . $siswa->nisn . "'")->row();

														$cektanggalpanggilwalisiswa = $this->db->query("SELECT * FROM surat_panggilan WHERE nisn = '" . $siswa->nisn . "' ORDER BY id_surat_panggilan DESC LIMIT 1")->row();

														$tglresetawal = '';

														if ($cektanggalpanggilwalisiswa) {
															$tglresetawal = $cektanggalpanggilwalisiswa->terakhir_dipanggil;
														} else {
															$tglresetawal = $findtahunajaranbetweendatenow->tgl_awal;
														}

														$total_terlambat = hitung_terlambat($tglresetawal, $tgl_sekarang, $getuserbyusername->user_id);
														$total_alpha = hitung_alpha($tglresetawal, $tgl_sekarang, $getuserbyusername->user_id);

														$t = $total_terlambat + $total_alpha;

														if ($t >= 3) {
											?>
															<tr>
																<td><?= $no++ ?></td>
																<td><?php echo $siswa->nisn ?></td>
																<td>
																	<a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal"
																		<?php if ($siswa->photo == '' || $siswa->photo == null) { ?> data-photo="default.png" <?php } else { ?> data-photo="<?php echo $siswa->photo ?>" <?php } ?>
																		data-nama_siswa="<?php echo $siswa->nama_siswa ?>">
																		<!-- <img src="<?= base_url() ?>assets/img/siswa/<?php echo $siswa->photo ?>" class="rounded h-30px my-n1 mx-n1" /> -->

																		<?php if ($siswa->photo == '' || $siswa->photo == null) { ?>
																			<img src="<?= base_url() ?>assets/img/siswa/default.png" class="rounded h-30px my-n1 mx-n1" />
																		<?php } else { ?>
																			<img src="<?= base_url() ?>assets/img/siswa/<?php echo $siswa->photo ?>" class="rounded h-30px my-n1 mx-n1" />
																		<?php } ?>
																	</a>&nbsp
																	<?php echo $siswa->nama_siswa ?>
																</td>
																<td><?php echo $siswa->nama_kelas ?></td>
																<td><?php echo $siswa->nama_wali_siswa ?></td>
																<td><?php echo $siswa->no_hp_wali_siswa ?></td>
																<td><?php echo $siswa->tgl_panggil_wali == null ? 'N/A' : $siswa->tgl_panggil_wali ?></td>
																<td>
																	<?php
																	$bgnya_terlambat = '';
																	$bgnya_alpha = '';

																	if ($total_terlambat >= 3) {
																		$bgnya_terlambat = 'bg-danger';
																	} else if ($total_terlambat > 1) {
																		$bgnya_terlambat = 'bg-warning';
																	} else {
																		$bgnya_terlambat = 'bg-success';
																	}

																	if ($total_alpha >= 3) {
																		$bgnya_alpha = 'bg-danger';
																	} else if ($total_alpha > 1) {
																		$bgnya_alpha = 'bg-warning';
																	} else {
																		$bgnya_alpha = 'bg-success';
																	}
																	?>
																	<?= '<span class="badge ' . $bgnya_terlambat . '">Terlambat : ' . $total_terlambat . '</span>' ?> <br>
																	<?= '<span class="badge ' . $bgnya_alpha . '">Tidak Masuk : ' . $total_alpha . '</span>' ?></td>
																<td>
																	<!-- create form -->


																	<form class="generate_surat_form" method="post">
																		<input type="hidden" name="id_siswa" value="<?= encrypt_url($siswa->siswa_id) ?>">
																		<input type="hidden" name="nisn" value="<?= encrypt_url($siswa->nisn) ?>">
																		<input type="hidden" name="tanggal_dipanggil" value="<?= $tgl_sekarang ?>">
																		<input type="hidden" name="telat" value="<?= $total_terlambat ?>" />
																		<input type="hidden" name="tidakhadir" value="<?= $total_alpha ?>" />
																		<button type="submit" class="btn btn-primary btn-sm btn-generate-surat">Generate Surat Panggilan</button>
																	</form>
																</td>
															</tr>
											<?php
														}
													}
												} else {
													echo '<tr><td colspan="9" class="text-center"><p>Tidak Dapat Melakukan Listing Siswa untuk Panggilan karena tidak ada tahun ajaran terencana, input tahun ajaran sesuai dengan sekarang dengan pergi ke menu Tahun Ajaran</p>
														<a class="btn btn-primary" href="' . base_url() . 'tahun_ajaran">Kelola Tahun Ajaran</a></td></tr>';
												}
											} else {
												echo "<tr><td colspan='10' align='center' style='padding: 4rem 0;'>
													<p>Tahun Ajaran Perlu ditambahkan terlebih dahulu, klik tombol dibawah untuk menambah tahun ajaran</p>
													<a class='btn btn-primary' href='" . base_url() . "tahun_ajaran'>Kelola Tahun Ajaran</a></td></tr>";
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).on('click', '#view_gambar', function() {
				var nama_siswa = $(this).data('nama_siswa');
				var photo = $(this).data('photo');
				$('#modal-dialog #cuts').text(nama_siswa);
				$('#modal-dialog #photo_siswa').attr("src", "assets/img/siswa/" + photo);
				$('#modal-dialog #download').attr("href", "siswa/download/" + photo);
			})
		</script>
		<script>
			$(document).ready(function() {
				$(".theSelect").select2();
				// check if #tabel-panggil-siswa doesnt have any data
				if ($('#tabel-panggil-siswa').find('tr').length == 1) {
					// insert a row with colspan 10 with text "Tidak Ada Murid yang perlu dipanggil"
					$('#tabel-panggil-siswa').append('<tr><td colspan="10" class="text-center">Tidak Ada Murid yang perlu dipanggil</td></tr>');
				}
			})

			$(document).on('submit', '.generate_surat_form', function(e) {

				e.preventDefault()

				var form = $(this)
				var url = "<?= base_url() . 'surat_panggilan/generate' ?>"

				// create swal.fire with confirm button
				swal.fire({
					title: 'Apakah anda yakin?',
					text: "Anda akan mengenerate surat panggilan untuk siswa ini",
					type: 'warning',
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: 'Batal',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Generate Surat'
				}).then((result) => {
					if (result.value) {
						// show loading swal
						swal.fire({
							title: 'Mohon Tunggu',
							text: 'Generate Surat Sedang Berjalan',
							type: 'info',
							allowOutsideClick: false,
							showConfirmButton: false,
							onOpen: () => {
								swal.showLoading()
							}
						})
						$.ajax({
							url: url,
							type: "POST",
							data: form.serialize(),
							success: function(data) {
								var dt = JSON.parse(data);
								if (dt.status == 'success') {
									Swal.fire({
										title: 'Berhasil Generate Surat',
										text: 'Cetak Surat panggilan?',
										type: 'success',
										icon: 'success',
										confirmButtonColor: '#3085d6',
										confirmButtonClass: 'btn btn-success',
										cancelButtonClass: 'btn btn-danger'
									}).then((result) => {
										if (result.value) {
											window.location.href = "<?= base_url() . 'surat_panggilan' ?>"
										} else {
											window.location.href = "<?= base_url() . 'surat_panggilan' ?>";
										}
									})
								} else {
									Swal.fire({
										title: 'Gagal',
										text: 'Surat Panggilan Gagal di Generate, silahkan coba lagi',
										type: 'error',
										icon: 'error',
										confirmButtonText: 'Ok',
										// footer
										footer: 'Masih mengalami masalah? Silahkan cek <a href>Bantuan</a>'
									})
								}
							}
						})
					}
				})
			})
		</script>