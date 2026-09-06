<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<h1 class="page-header">KELOLA DATA JENJANG</h1>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">List Data Jenjang</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="mb-3">
						<a href="<?= base_url('jenjang/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
					</div>
					<div class="table-responsive">
						<table id="data-table-default" class="table table-bordered table-hover text-white align-middle">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Nama Jenjang</th>
									<th width="15%" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1;
								foreach ($jenjang_data as $jenjang) : ?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $jenjang->nama_jenjang ?></td>
										<td class="text-center">
											<a href="<?= base_url('jenjang/update/' . encrypt_url($jenjang->jenjang_id)) ?>" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
											<a href="<?= base_url('jenjang/delete/' . encrypt_url($jenjang->jenjang_id)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')"><i class="fas fa-trash-alt"></i></a>
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