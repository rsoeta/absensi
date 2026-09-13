<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
	<div class="col-xl-6">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">LAPORAN ABSEN SISWA</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post">
					<table class="table table-bordered table-td-valign-middle text-white align-middle">
						<tr>
							<td width="200">Kelas</td>
							<td>
								<select name="kelas_id" class="form-control target theSelect" required>
									<option value="">-- Pilih Kelas --</option>
									<?php foreach ($kelas as $data) : ?>
										<option value="<?= $data->kelas_id ?>"><?= $data->nama_kelas ?></option>
									<?php endforeach; ?>
								</select>
								<div class="mt-2" id="result"></div>
								<div id="result_tunggu"></div>
							</td>
						</tr>
						<tr>
							<td>Bulan</td>
							<td>
								<select name="bulan" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php
									$bulans = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
									foreach ($bulans as $num => $nama) : ?>
										<option value="<?= $num ?>"><?= $nama ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Tahun</td>
							<td>
								<select name="tahun" class="form-control theSelect" required>
									<option value="">-- Pilih --</option>
									<?php foreach ($tahun_data as $data) : ?>
										<option value="<?= $data->tahun ?>"><?= $data->tahun ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><button type="submit" class="btn btn-danger"><i class="fas fa-eye"></i> <?= $button ?></button></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".theSelect").select2();
	});
	$(".target").change(function() {
		var selectedValue = $(this).val();
		$.ajax({
			url: '<?= base_url('laporan/get_data_siswa') ?>',
			type: 'POST',
			data: {
				selectedValue: selectedValue
			},
			beforeSend: function() {
				$("#result").html("");
				$("#result_tunggu").html('<span class="text-success"><i class="fas fa-spinner fa-spin"></i> Menarik data siswa...</span>');
			},
			success: function(html) {
				$("#result").html(html);
				$("#result_tunggu").html('');
				$(".theSelect").select2(); // Re-init select2 untuk ajax response
			}
		});
	});
</script>

<?= $this->endSection() ?>