<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA SISWA MAPEL PEMINATAN <?= $mapel_peminatan_nama ?></h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data siswa </h4>
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
							<div class='row'>
								<div class="col-md-5">
									<form action="<?= site_url('siswa_mapel_peminatan/create_action/'.$mapel_peminatan_id) ?>" method="post">
										<div class="input-group">
											<select name="siswa_id" class="form-control theSelect">
												<option value="">-- Pilih -- </option>
												<?php foreach ($list_siswa as $key => $data) { ?>
													<option value="<?php echo $data->siswa_id ?>"><?php echo $data->nama_siswa ?></option>
												<?php } ?>
											</select>
											<button type="submit" class="btn btn-primary">Tambah</button>
										</div>
									</form>
								</div>
							</div>
						<?php
// Menampilkan peringatan dengan teks warna merah, ukuran huruf 12, dan miring
echo '<div style="color: red; font-size: 12px; font-style: italic;">Jika saat dicari Siswa Tidak ditemukan Hub : Ibu Aftah , Agar Ditambahkan kemungkinan siswa belum terdaftar siswa pindahan</div>'
;
?>
							<div class="row mt-5">
								<table class="table">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Siswa</th>
											<th>Kelas</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach($siswa_mapel_peminatan_list as $key => $p) {
												?>
													<tr>
														<td><?php echo $key + 1 ?></td>
														<td><?php echo $p->nama_siswa ?></td>
														<td><?php echo data_kelas($p->kelas_id)->nama_kelas ?></td>
														<td>
															<a href="<?php echo site_url('siswa_mapel_peminatan/delete/'.$p->id) ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>
														</td>
													</tr>
												<?php
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
</div>

<script>
	$(document).ready(function() {
		$(".theSelect").select2();
	})
</script>