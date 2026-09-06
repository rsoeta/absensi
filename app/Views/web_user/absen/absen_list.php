<div id="content" class="app-content">
	<h1 class="page-header">DATA ABSEN
	</h1>
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
								</div>
							</div>
							<div class="box-body" style="overflow-x: scroll; ">
								<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama</th>
											<th>Tanggal</th>
											<th>Keterangan</th>
											<th>status_masuk Absen</th>
											<th>Jam Masuk</th>
											<th>Jam Pulang</th>
										</tr>
									</thead>
									<tbody><?php $no = 1;
											foreach ($absen_data as $absen) {
											?>
											<tr>
												<td><?= $no++ ?></td>
												<?php if ($absen->level_id == '1') { ?>
													<td>Admin Aplikasi</td>
												<?php } else if ($absen->level_id == '2') { ?>
													<td><?= nama_guru($absen->user_id) ?>
													</td>
												<?php } else if ($absen->level_id == '3') { ?>
													<td><?= nama_pegawai($absen->user_id) ?></td>
												<?php } else if ($absen->level_id == '4') { ?>
													<td><?= nama_siswa($absen->user_id) ?></td>
												<?php }  ?>
												<td><?php echo $absen->tanggal ?></td>
												<td><?php echo $absen->keterangan ?></td>
												<?php if ($absen->status_masuk == 'Terlambat') { ?>
													<td><span class="badge bg-default rounded-pill">Terlambat</span></td>
												<?php } else if ($absen->status_masuk == 'Tepat Waktu') { ?>
													<td><span class="badge bg-success rounded-pill">Tepat Waktu</span></td>
												<?php }?>
												
												<td><?php echo $absen->jam_masuk ?></td>
												<td><?php echo $absen->jam_pulang ?></td>
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
