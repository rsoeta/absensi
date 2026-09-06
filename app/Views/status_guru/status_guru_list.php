<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA STATUS GURU</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data Status Guru</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="mb-3">
						<a href="<?= base_url('status_guru/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
					</div>
					<div class="table-responsive">
						<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Nama Status Guru</th>
									<th width="15%" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1;
								foreach ($status_guru_data as $status_guru) : ?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $status_guru->nama_status_guru ?></td>
										<td class="text-center">
											<a href="<?= base_url('status_guru/update/' . encrypt_url($status_guru->status_guru_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
											<a href="<?= base_url('status_guru/delete/' . encrypt_url($status_guru->status_guru_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>