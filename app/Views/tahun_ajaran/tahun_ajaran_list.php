<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA TAHUN_AJARAN</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title"></h4>
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
								<div class='col-md-9'>
									<div style="padding-bottom: 10px;">
										<?php echo anchor(site_url('tahun_ajaran/create'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data"'); ?>
									</div>
								</div>
							</div>
							<div class="box-body" style="overflow-x: scroll; ">
								<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Tahun Ajaran</th>
											<th>Tgl Awal</th>
											<th>Tgl Akhir</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1;
										foreach ($tahun_ajaran_data as $tahun_ajaran) {
										?>
											<tr>
												<td><?= $no++ ?></td>
												<td><?php echo $tahun_ajaran->nama_tahun_ajaran ?></td>
												<td><?php echo $tahun_ajaran->tgl_awal ?></td>
												<td><?php echo $tahun_ajaran->tgl_akhir ?></td>
												<td>
													<?php
													$tanggalsekarang = date('Y-m-d');
													// check if this $tanggalsekarang is between $tahun_ajaran->tgl_awal and $tahun_ajaran->tgl_akhir
													if ($tanggalsekarang >= $tahun_ajaran->tgl_awal && $tanggalsekarang <= $tahun_ajaran->tgl_akhir) {
														echo '<span class="badge bg-success">Sedang Berjalan</span>';
													} else {
														if ($tanggalsekarang < $tahun_ajaran->tgl_awal && $tanggalsekarang < $tahun_ajaran->tgl_akhir) {
															echo '<span class="badge bg-danger">Belum Mulai</span>';
														}
														if ($tanggalsekarang > $tahun_ajaran->tgl_awal && $tanggalsekarang > $tahun_ajaran->tgl_akhir) {
															echo '<span class="badge bg-warning">Berlalu</span>';
														}
													}
													?>
												</td>
												<td style="text-align:center" width="200px">
													<a href="<?= site_url('tahun_ajaran/read/' . encrypt_url($tahun_ajaran->tahun_ajaran_id)) ?>" class="btn btn-success btn-sm read_data"><i class="fas fa-eye" aria-hidden="true"></i></a>
													<a href="<?= site_url('tahun_ajaran/update/' . encrypt_url($tahun_ajaran->tahun_ajaran_id)) ?>" class="btn btn-primary btn-sm update_data"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
													<a href="<?= site_url('tahun_ajaran/delete/' . encrypt_url($tahun_ajaran->tahun_ajaran_id)) ?>" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>
												</td>
											</tr>
										<?php } ?>
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
		$('.btn-hapus').on('click', function(e) {
			e.preventDefault();
			const href = $(this).attr('href');

			Swal.fire({
				title: 'Hapus Data?',
				text: "Data tahun ajaran akan dihapus permanen!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
				width: '300px', // Memperkecil ukuran popup untuk kenyamanan user mobile
				customClass: {
					title: 'fs-5',
					content: 'fs-6'
				}
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = href;
				}
			});
		});
	});
</script>
<?= $this->endSection() ?>