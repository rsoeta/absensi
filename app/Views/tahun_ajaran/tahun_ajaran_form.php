<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- required files -->
<link href="<?= base_url() ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/plugins/moment/min/moment.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<div id="content" class="app-content">
	<div class="col-xl-12 ui-sortable">
		<div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">KELOLA DATA TAHUN_AJARAN</h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">

				<form action="<?php echo $action; ?>" method="post">
					<thead>
						<table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
							<tr>
								<td width='200'>Nama Tahun Ajaran </td>
								<td><input type="text" class="form-control" name="nama_tahun_ajaran" id="nama_tahun_ajaran" placeholder="Nama Tahun Ajaran" value="<?php echo $nama_tahun_ajaran; ?>" /></td>
							</tr>
							<tr>
								<td>Tanggal Awal dan Akhir</td>
								<td>
									<div class="input-group" id="tahunajaran_daterange">
										<input type="text" name="tahunajaran_daterange" class="form-control" value="<?= date('d F Y', strtotime($tgl_awal)) . ' - ' . date('d F Y', strtotime($tgl_akhir)) ?>" placeholder="pilih jarak tanggal tahun ajaran" />
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>

									<input type="hidden" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="Tgl Awal" value="<?php echo $tgl_awal; ?>" />
									<input type="hidden" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="Tgl Akhir" value="<?php echo $tgl_akhir; ?>" />
								</td>
							<tr>
								<td></td>
								<td><input type="hidden" id="tahun_ajaran_id" name="tahun_ajaran_id" value="<?php echo $tahun_ajaran_id; ?>" />
									<button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?php echo $button ?></button>
									<a href="<?php echo site_url('tahun_ajaran') ?>" class="btn btn-info"><i class="fas fa-undo"></i> Kembali</a>
								</td>
							</tr>
					</thead>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$("#tahunajaran_daterange").daterangepicker({
		opens: "right",
		format: "MM/DD/YYYY",
		separator: " to ",
		startDate: moment().subtract("days", 29),
		endDate: moment(),
	}, function(start, end) {

		var tgl_awal = start.format("YYYY-MM-DD");
		var tgl_akhir = end.format("YYYY-MM-DD");

		$("#tahunajaran_daterange input").val(start.format("D MMMM YYYY") + " - " + end.format("D MMMM YYYY"));

		$('#tgl_awal').val(tgl_awal);
		$('#tgl_akhir').val(tgl_akhir);
		$.ajax({
			url: '<?php echo site_url('tahun_ajaran/deteksi_tahun_ajaran/') ?>' + tgl_awal + '/' + tgl_akhir + '/1/' + $('#tahun_ajaran_id').val(),
			type: 'GET',
			success: function(data) {
				alert(data);
			}
		});
	});
</script>

<?= $this->endSection() ?>