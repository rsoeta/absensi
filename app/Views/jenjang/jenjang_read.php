<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Jenjang Read</h4>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-bordered table-td-valign-middle">
					<tr>
						<td width="200">Nama Jenjang</td>
						<td><?= $nama_jenjang ?></td>
					</tr>
					<tr>
						<td></td>
						<td><a href="<?= base_url('jenjang') ?>" class="btn btn-default">Kembali</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>