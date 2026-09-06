<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">KELOLA DATA MAPEL</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action ?>" method="post">
					<table class="table table-bordered table-hover table-td-valign-middle">
						<tr>
							<td width='200'>Kode Mapel</td>
							<td><input type="text" class="form-control" name="kode_mapel" id="kode_mapel" placeholder="Kode Mapel" value="<?= $kode_mapel ?>" required /></td>
						</tr>
						<tr>
							<td width='200'>Nama Mapel</td>
							<td><input type="text" class="form-control" name="nama_mapel" id="nama_mapel" placeholder="Nama Mapel" value="<?= $nama_mapel ?>" required /></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" name="mapel_id" value="<?= $mapel_id ?>" />
								<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
								<a href="<?= base_url('mapel') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>