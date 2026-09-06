<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" data-init="true">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">Mapel Read</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<table id="data-table-default" class="table table-hover table-bordered table-td-valign-middle">
					<tr>
						<td>Kode Mapel</td>
						<td><?php echo $kode_mapel; ?></td>
					</tr>
					<tr>
						<td>Nama Mapel</td>
						<td><?php echo $nama_mapel; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><a href="<?php echo site_url('mapel') ?>" class="btn btn-default">Cancel</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>