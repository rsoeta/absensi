<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA USER</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data user </h4>
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
										<?php echo anchor(site_url('user/create'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm tambah_data"'); ?>
									</div>
								</div>
							</div>
							<div class="box-body" style="overflow-x: scroll; ">
								<table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
									<thead>
										<tr>
											<th style="width: 5%;">No</th>
											<th>Username</th>
											<th>Level</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody><?php $no = 1;
											foreach ($user_data as $user) {
											?>
											<tr>
												<td><?= $no++ ?></td>
												<td><?php echo $user->username ?></td>
												<td>Admin Aplikasi</td>
												<td style="text-align:center">
													<a href="<?= base_url('user/update/' . encrypt_url($user->user_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
													<a href="<?= base_url('user/delete/' . encrypt_url($user->user_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure ?')"><i class="fas fa-trash-alt"></i> Delete</a>
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

<?= $this->endSection() ?>