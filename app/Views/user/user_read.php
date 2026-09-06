<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<div class="col-xl-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">User Read</h4>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-bordered align-middle text-white">
					<tr>
						<td width="200">Username</td>
						<td><?= $username; ?></td>
					</tr>
					<tr>
						<td>Level</td>
						<td><?= $level_id; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><a href="<?= base_url('user') ?>" class="btn btn-default">Cancel</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>