<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA JENJANG</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data jenjang </h4>
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
										<?php echo anchor(site_url('jenjang/create'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data"'); ?>
										<!-- <?php echo anchor(site_url('jenjang/excel'), '<i class="far fa-file-excel" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm export_data"'); ?> -->
									</div>
								</div>
							</div>
							<div class="box-body" style="overflow-x: scroll; ">
								<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Jenjang</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody><?php $no = 1;
											foreach ($jenjang_data as $jenjang) {
											?>
											<tr>
												<td><?= $no++ ?></td>
												<td><?php echo $jenjang->nama_jenjang ?></td>
												<td style="text-align:center" width="200px">
													<?php
													echo anchor(site_url('jenjang/update/' . encrypt_url($jenjang->jenjang_id)), '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', 'class="btn btn-primary btn-sm update_data"');
													echo '  ';
													echo anchor(site_url('jenjang/delete/' . encrypt_url($jenjang->jenjang_id)), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
													?>
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
	<?php
