<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?= $sett_apps->nama_aplikasi ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="<?= base_url() ?>assets/css/vendor.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/css/transparent/app.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />

	<link href="<?= base_url() ?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
	<script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
</head>

<body>
	<div class="app-cover"></div>
	<div id="app" class="app app-header-fixed app-sidebar-fixed">
		<div id="header" class="app-header">
			<div class="navbar-header">
				<a href="#" class="navbar-brand"><span class="navbar-logo"></span> <?= $sett_apps->nama_aplikasi ?></a>
				<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navbar-nav">
				<div class="navbar-item navbar-user dropdown">
					<a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">

						<span>
							<span class="d-none d-md-inline">
								<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
									Guru <?= nama_guru($this->session->userdata('userid')) ?>
								<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
									Pegawai <?= nama_pegawai($this->session->userdata('userid')) ?>
								<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
									Siswa <?= nama_siswa($this->session->userdata('userid')) ?>
								<?php } ?>

							</span>
							<b class="caret"></b>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-1">
						<a href="<?= base_url() ?>dashboard_user/edit_profile" class="dropdown-item">Edit Profile</a>
						<a href="<?= base_url() ?>Auth/logout" class="dropdown-item">Log Out</a>
					</div>
				</div>
			</div>

		</div>
		<div id="sidebar" class="app-sidebar">
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<div class="menu">
					<div class="menu-profile">
						<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
							<div class="menu-profile-cover with-shadow"></div>
							<div class="menu-profile-image">
								<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
									<img src="<?= base_url() ?>assets/img/guru/<?= photo_guru($this->session->userdata('userid')) ?>" alt="" />
								<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
									<img src="<?= base_url() ?>assets/img/pegawai/<?= photo_pegawai($this->session->userdata('userid')) ?>" alt="" />
								<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
									<img src="<?= base_url() ?>assets/img/siswa/<?= photo_siswa($this->session->userdata('userid')) ?>" alt="" />
								<?php } ?>

							</div>
							<div class="menu-profile-info">
								<div class="d-flex align-items-center">
									<div class="flex-grow-1">
										<span class="d-none d-md-inline">
											<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
												Guru <?= nama_guru($this->session->userdata('userid')) ?>
											<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
												Pegawai <?= nama_pegawai($this->session->userdata('userid')) ?>
											<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
												Siswa <?= nama_siswa($this->session->userdata('userid')) ?>
											<?php } ?>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="menu-header">Navigation</div>
					<div class="menu-item">
						<a href="<?= base_url() ?>dashboard_user" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-home"></i>
							</div>
							<div class="menu-text">Dashboard</div>
						</a>
					</div>

					<div class="menu-item">
						<a href="<?= base_url() ?>dashboard_user/absen" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-calendar"></i>
							</div>
							<div class="menu-text">History Absen</div>
						</a>
					</div>
					<div class="menu-item">
						<a href="<?= base_url() ?>dashboard_user/izin_sakit" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-list" aria-hidden="true"></i>
							</div>
							<div class="menu-text">Izin/Sakit</div>
						</a>
					</div>

					<?php if ($this->fungsi->user_login()->level_id == '4') { ?>
						<div class="menu-item">
							<a href="<?= base_url() ?>absen_mapel/laporan_siswa" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-book" aria-hidden="true"></i>
								</div>
								<div class="menu-text">Laporan Absen Mapel</div>
							</a>
						</div>
					<?php } ?>




					<div class="menu-item">
						<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
							<a target="_blank" href="<?= base_url() ?>dashboard_user/kartu/<?= encrypt_url(guru_id($this->session->userdata('userid'))) ?>" class="menu-link">
							<?php } else if ($this->fungsi->user_login()->level_id == '3') { ?>
								<a target="_blank" href="<?= base_url() ?>dashboard_user/kartu/<?= encrypt_url(pegawai_id($this->session->userdata('userid'))) ?>" class="menu-link">
								<?php } else if ($this->fungsi->user_login()->level_id == '4') { ?>
									<a target="_blank" href="<?= base_url() ?>dashboard_user/kartu/<?= encrypt_url(siswa_id($this->session->userdata('userid'))) ?>" class="menu-link">
									<?php } ?>
									<div class="menu-icon">
										<i class="fa fa-print"></i>
									</div>
									<div class="menu-text">Cetak Kartu</div>
									</a>
					</div>

					<?php if ($this->fungsi->user_login()->level_id == '2') { ?>
						<div class="menu-item has-sub <?= $this->uri->segment(1) == 'mapel_peminatan'  ? 'active' : '' ?> ">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-calendar" aria-hidden="true"></i>
								</div>
								<div class="menu-text">Mapel Peminatan</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item  <?= $this->uri->segment(1) == 'mapel_peminatan'  &&  $this->uri->segment(2) == ''  ? 'active' : '' ?>">
									<a href="<?= base_url() ?>mapel_peminatan" class="menu-link">
										<div class="menu-text">Daftar Mapel</div>
									</a>
								</div>
								<div class="menu-item  <?= $this->uri->segment(1) == 'absen_mapel_peminatan'  &&  $this->uri->segment(2) == ''  ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel_peminatan" class="menu-link">
										<div class="menu-text">Absen Mapel Peminatan</div>
									</a>
								</div>
								<div class="menu-item  <?= $this->uri->segment(1) == 'absen_mapel_peminatan'  &&  $this->uri->segment(2) == ''  ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel_peminatan/laporan" class="menu-link">
										<div class="menu-text">Laporan Absen Mapel</div>
									</a>
								</div>
							</div>
						</div>
						<div class="menu-item has-sub <?= $this->uri->segment(1) == 'absen_mapel'  ? 'active' : '' ?> ">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-calendar" aria-hidden="true"></i>
								</div>
								<div class="menu-text">Absen Mapel</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item  <?= $this->uri->segment(1) == 'absen_mapel'  &&  $this->uri->segment(2) == ''  ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel" class="menu-link">
										<div class="menu-text">Absen Mapel</div>
									</a>
								</div>
								<div class="menu-item  <?= $this->uri->segment(1) == 'absen_mapel' &&  $this->uri->segment(2) == 'laporan'  ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel/laporan" class="menu-link">
										<div class="menu-text">Laporan Absen Mapel</div>
									</a>
								</div>
							</div>
						</div>
						<?php
						$segment1 = $this->uri->segment(1);
						$segment2 = $this->uri->segment(2);
						$isActive = ($segment1 == 'dashboard_user' && in_array($segment2, ['mapel_umum', 'mapel_peminatan']));
						?>

						<div class="menu-item has-sub <?= $isActive ? 'active' : '' ?>">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-book"></i>
								</div>
								<div class="menu-text">Laporan Kedisiplinan</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item <?= in_array($segment2, ['mapel_umum', 'view_mapel_umum']) ? 'active' : '' ?>">
									<a href="<?= base_url() ?>dashboard_user/mapel_umum" class="menu-link">
										<div class="menu-text">Mapel Umum</div>
									</a>
								</div>
								<div class="menu-item <?= in_array($segment2, ['mapel_peminatan', 'view_mapel_peminatan']) ? 'active' : '' ?>">
									<a href="<?= base_url() ?>dashboard_user/mapel_peminatan" class="menu-link">
										<div class="menu-text">Mapel Peminatan</div>
									</a>
								</div>
							</div>
						</div>
						<div class="menu-item">
							<a href="<?= base_url() ?>dashboard_user/laporan_siswa" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-book" aria-hidden="true"></i>
								</div>
								<div class="menu-text">Laporan Absen Siswa</div>
							</a>
						</div>

					<?php } ?>

					<div class="menu-item d-flex">
						<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
					</div>

				</div>

			</div>

		</div>
		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"></div>
		<?php if ($this->session->flashdata('message')) : ?>
		<?php endif; ?>

		<div class="flash-data2" data-flashdata2="<?= $this->session->flashdata('error'); ?>"></div>
		<?php if ($this->session->flashdata('error')) : ?>
		<?php endif; ?>


		<!-- isi -->
		<?php echo $contents ?>
		<script src="<?= base_url() ?>assets/js/vendor.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/js/app.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/js/theme/transparent.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/plugins/d3/d3.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/plugins/nvd3/build/nv.d3.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<!-- <script src="<?= base_url() ?>assets/plugins/gritter/js/jquery.gritter.js" type="beba54df5f87d24c2458d535-text/javascript"></script> -->
		<script src="<?= base_url() ?>assets/js/demo/dashboard-v2.js" type="beba54df5f87d24c2458d535-text/javascript"></script>
		<script src="<?= base_url() ?>assets/js/rocket-loader.min.js" data-cf-settings="beba54df5f87d24c2458d535-|49" defer=""></script>

		<script src="<?= base_url() ?>assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sweetalert.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script> <!-- untuk sweet alret -->
		<script src="<?php echo base_url(); ?>assets/js/dataflash.js"></script>
</body>

</html>



<script>
	//datatable
	$('#data-table-default').DataTable({
		responsive: true
	});
	$('#data-table-default2').DataTable({
		responsive: true
	});
	//ckeditor
	$('#wysihtml5').wysihtml5();
</script>