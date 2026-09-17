<?php
// =========================================================================
// PENANGKAL ERROR UNDEFINED VARIABLE & MIGRASI FUNGSI GLOBAL CI4
// =========================================================================
$db = \Config\Database::connect();
// Jika $sett_apps tidak dikirim oleh controller, template akan mengambilnya sendiri
$sett_apps = $sett_apps ?? $db->table('app_setting')->where('id', 1)->get()->getRow();

// Deklarasi Sesi Global
$userid = session()->get('userid');
$level_id = session()->get('level_id');

// Deklarasi URI Segment ala CI4
$request = \Config\Services::request();
$segment1 = $request->getUri()->getTotalSegments() >= 1 ? $request->getUri()->getSegment(1) : '';
$segment2 = $request->getUri()->getTotalSegments() >= 2 ? $request->getUri()->getSegment(2) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?= $sett_apps->nama_aplikasi ?? 'E-Absensi' ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

	<!-- Sisipkan kode favicon dinamis di sini -->
	<?php if (isset($sett_apps) && !empty($sett_apps->logo_sekolah)) : ?>
		<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
	<?php else : ?>
		<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/default.png') ?>">
	<?php endif; ?>

	<!-- CUSTOM THEME OVERRIDES (EKSTERNAL) -->
	<?php if (isset($sett_apps) && $sett_apps->tema_aplikasi == 'muhammadiyah') : ?>
		<link rel="stylesheet" href="<?= base_url('assets/css/tema_muhammadiyah.css') ?>">
	<?php endif; ?>

	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="<?= base_url() ?>assets/css/vendor.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/css/transparent/app.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
	<link href="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />

	<link href="<?= base_url() ?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">

	<!-- MOBILE TAB BAR CSS (TEMA MUHAMMADIYAH) -->
	<style>
		.mobile-tab-bar {
			display: none;
		}

		@media (max-width: 767.98px) {
			.mobile-tab-bar {
				display: flex !important;
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
				background: rgba(18, 62, 135, 0.95);
				/* Biru Gelap Muhammadiyah */
				backdrop-filter: blur(10px);
				z-index: 1040;
				box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.3);
				padding: 10px 15px 15px 15px;
				justify-content: space-between;
				align-items: center;
				border-top: 1px solid rgba(255, 255, 255, 0.1);
			}

			.mobile-tab-item {
				display: flex;
				flex-direction: column;
				align-items: center;
				color: rgba(255, 255, 255, 0.6);
				/* Putih pudar agar kontras dengan biru */
				text-decoration: none;
				font-size: 11px;
				font-weight: 500;
				flex: 1;
				transition: all 0.3s ease;
			}

			.mobile-tab-item i {
				font-size: 20px;
				margin-bottom: 4px;
				transition: transform 0.2s ease;
			}

			.mobile-tab-item.active {
				color: #ffd500;
				/* Kuning cerah untuk menu aktif */
			}

			.mobile-tab-item.active i {
				transform: translateY(-3px) scale(1.1);
			}

			.mobile-tab-item.action-btn i {
				background: #ffd500;
				/* Tombol tengah berwarna kuning */
				color: #123e87;
				/* Ikon di dalam tombol tengah berwarna biru gelap */
				border-radius: 50%;
				padding: 12px;
				font-size: 22px;
				margin-top: -25px;
				box-shadow: 0 4px 10px rgba(255, 213, 0, 0.4);
				/* Efek glow kuning */
				border: 3px solid rgba(18, 62, 135, 1);
				/* Border luar mengikuti warna background tab */
			}

			.app-content {
				padding-bottom: 90px !important;
			}

			.app-header .navbar-mobile-toggler {
				display: none;
			}

			/* Sembunyikan toggler bawaan agar pakai Tab Bar */
		}
	</style>

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
				<a href="#" class="navbar-brand"><span class="navbar-logo"></span> <?= $sett_apps->nama_aplikasi ?? 'E-Absensi' ?></a>
				<!-- Toggler ditahan via CSS di atas untuk memprioritaskan Tab Bar -->
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
								<?php if ($level_id == '2') : ?>
									Guru <?= ucwords(strtolower(nama_guru($userid))) ?>
								<?php elseif ($level_id == '3') : ?>
									Pegawai <?= ucwords(strtolower(nama_pegawai($userid))) ?>
								<?php elseif ($level_id == '4') : ?>
									Siswa <?= ucwords(strtolower(nama_siswa($userid))) ?>
								<?php endif; ?>
							</span>
							<b class="caret"></b>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-1">
						<a href="<?= base_url() ?>dashboard_user/edit_profile" class="dropdown-item">Edit Profile</a>
						<a href="<?= base_url() ?>Auth/logout" class="dropdown-item text-danger">Log Out</a>
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
								<?php if ($level_id == '2') : ?>
									<img src="<?= base_url() ?>assets/img/guru/<?= photo_guru($userid) ?>" alt="" />
								<?php elseif ($level_id == '3') : ?>
									<img src="<?= base_url() ?>assets/img/pegawai/<?= photo_pegawai($userid) ?>" alt="" />
								<?php elseif ($level_id == '4') : ?>
									<img src="<?= base_url() ?>assets/img/siswa/<?= photo_siswa($userid) ?>" alt="" />
								<?php endif; ?>
							</div>
							<div class="menu-profile-info">
								<div class="d-flex align-items-center">
									<div class="flex-grow-1">
										<span class="d-none d-md-inline">
											<?php if ($level_id == '2') : ?>
												Guru <?= ucwords(strtolower(nama_guru($userid))) ?>
											<?php elseif ($level_id == '3') : ?>
												Pegawai <?= ucwords(strtolower(nama_pegawai($userid))) ?>
											<?php elseif ($level_id == '4') : ?>
												Siswa <?= ucwords(strtolower(nama_siswa($userid))) ?>
											<?php endif; ?>
										</span>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="menu-header">Navigation</div>

					<div class="menu-item <?= ($segment1 == 'dashboard_user' && $segment2 == '') ? 'active' : '' ?>">
						<a href="<?= base_url() ?>dashboard_user" class="menu-link">
							<div class="menu-icon"><i class="fa fa-home"></i></div>
							<div class="menu-text">Dashboard</div>
						</a>
					</div>

					<!-- MENU BARU: ABSENSI KEHADIRAN (MANDIRI) -->
					<div class="menu-item <?= ($segment2 == 'absen_mandiri') ? 'active' : '' ?>">
						<a href="<?= base_url() ?>dashboard_user/absen_mandiri" class="menu-link">
							<div class="menu-icon text-info"><i class="fa fa-camera"></i></div>
							<div class="menu-text">Absensi Kehadiran</div>
						</a>
					</div>

					<div class="menu-item <?= ($segment2 == 'absen') ? 'active' : '' ?>">
						<a href="<?= base_url() ?>dashboard_user/absen" class="menu-link">
							<div class="menu-icon"><i class="fa fa-calendar"></i></div>
							<div class="menu-text">History Absen</div>
						</a>
					</div>

					<div class="menu-item <?= ($segment2 == 'izin_sakit') ? 'active' : '' ?>">
						<a href="<?= base_url() ?>dashboard_user/izin_sakit" class="menu-link">
							<div class="menu-icon"><i class="fa fa-list"></i></div>
							<div class="menu-text">Izin/Sakit</div>
						</a>
					</div>

					<?php if ($level_id == '4') : ?>
						<div class="menu-item">
							<a href="<?= base_url() ?>absen_mapel/laporan_siswa" class="menu-link">
								<div class="menu-icon"><i class="fa fa-book"></i></div>
								<div class="menu-text">Laporan Absen Mapel</div>
							</a>
						</div>
					<?php endif; ?>

					<div class="menu-item has-sub <?= ($segment2 == 'kartu' || $segment2 == 'download_kartu_img') ? 'active' : '' ?>">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon"><i class="fa fa-id-badge"></i></div>
							<div class="menu-text">Kartu Identitas</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<?php if ($level_id == '2') : ?>
									<a target="_blank" href="<?= base_url('dashboard_user/kartu/' . encrypt_url(guru_id($userid))) ?>" class="menu-link">
									<?php elseif ($level_id == '3') : ?>
										<a target="_blank" href="<?= base_url('dashboard_user/kartu/' . encrypt_url(pegawai_id($userid))) ?>" class="menu-link">
										<?php elseif ($level_id == '4') : ?>
											<a target="_blank" href="<?= base_url('dashboard_user/kartu/' . encrypt_url(siswa_id($userid))) ?>" class="menu-link">
											<?php endif; ?>
											<div class="menu-text">Cetak PDF</div>
											</a>
							</div>
							<div class="menu-item">
								<?php if ($level_id == '2') : ?>
									<a href="<?= base_url('dashboard_user/download_kartu_img/' . encrypt_url(guru_id($userid))) ?>" class="menu-link">
									<?php elseif ($level_id == '3') : ?>
										<a href="<?= base_url('dashboard_user/download_kartu_img/' . encrypt_url(pegawai_id($userid))) ?>" class="menu-link">
										<?php elseif ($level_id == '4') : ?>
											<a href="<?= base_url('dashboard_user/download_kartu_img/' . encrypt_url(siswa_id($userid))) ?>" class="menu-link">
											<?php endif; ?>
											<div class="menu-text">Download Gambar</div>
											</a>
							</div>
						</div>
					</div>

					<?php if ($level_id == '2') : ?>
						<div class="menu-item has-sub <?= $segment1 == 'mapel_peminatan' ? 'active' : '' ?>">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-calendar-alt"></i></div>
								<div class="menu-text">Mapel Peminatan</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item <?= $segment1 == 'mapel_peminatan' && $segment2 == '' ? 'active' : '' ?>">
									<a href="<?= base_url() ?>mapel_peminatan" class="menu-link">
										<div class="menu-text">Daftar Mapel</div>
									</a>
								</div>
								<div class="menu-item <?= $segment1 == 'absen_mapel_peminatan' && $segment2 == '' ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel_peminatan" class="menu-link">
										<div class="menu-text">Absen Mapel Peminatan</div>
									</a>
								</div>
								<div class="menu-item <?= $segment1 == 'absen_mapel_peminatan' && $segment2 == 'laporan' ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel_peminatan/laporan" class="menu-link">
										<div class="menu-text">Laporan Absen Mapel</div>
									</a>
								</div>
							</div>
						</div>

						<div class="menu-item has-sub <?= $segment1 == 'absen_mapel' ? 'active' : '' ?>">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-book-reader"></i></div>
								<div class="menu-text">Absen Mapel</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item <?= $segment1 == 'absen_mapel' && $segment2 == '' ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel" class="menu-link">
										<div class="menu-text">Absen Mapel</div>
									</a>
								</div>
								<div class="menu-item <?= $segment1 == 'absen_mapel' && $segment2 == 'laporan' ? 'active' : '' ?>">
									<a href="<?= base_url() ?>absen_mapel/laporan" class="menu-link">
										<div class="menu-text">Laporan Absen Mapel</div>
									</a>
								</div>
							</div>
						</div>

						<?php $isActive = ($segment1 == 'dashboard_user' && in_array($segment2, ['mapel_umum', 'mapel_peminatan', 'view_mapel_umum', 'view_mapel_peminatan'])); ?>
						<div class="menu-item has-sub <?= $isActive ? 'active' : '' ?>">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-chart-bar"></i></div>
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
								<div class="menu-icon"><i class="fa fa-users"></i></div>
								<div class="menu-text">Laporan Absen Siswa</div>
							</a>
						</div>
					<?php endif; ?>

					<div class="menu-item d-flex">
						<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
					</div>
				</div>
			</div>
		</div>

		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>

		<!-- NOTIFIKASI FLASH DATA -->
		<div class="flash-data" data-flashdata="<?= session()->getFlashdata('message'); ?>"></div>
		<div class="flash-data2" data-flashdata2="<?= session()->getFlashdata('error'); ?>"></div>

		<!-- Render Konten CI4 -->
		<?= $this->renderSection('content') ?>

		<!-- NAVIGASI BAWAH MOBILE (TAB BAR) -->
		<div class="mobile-tab-bar">
			<a href="<?= base_url('dashboard_user') ?>" class="mobile-tab-item <?= ($segment1 == 'dashboard_user' && $segment2 == '') ? 'active' : '' ?>">
				<i class="fa fa-home"></i>
				<span>Home</span>
			</a>
			<a href="<?= base_url('dashboard_user/absen') ?>" class="mobile-tab-item <?= ($segment2 == 'absen') ? 'active' : '' ?>">
				<i class="fa fa-calendar-check"></i>
				<span>History</span>
			</a>
			<!-- Tombol Tengah / Utama (Absen Mandiri) -->
			<a href="<?= base_url('dashboard_user/absen_mandiri') ?>" class="mobile-tab-item action-btn <?= ($segment2 == 'absen_mandiri') ? 'active' : '' ?>">
				<i class="fa fa-fingerprint"></i>
				<span>Absen</span>
			</a>
			<a href="<?= base_url('dashboard_user/izin_sakit') ?>" class="mobile-tab-item <?= ($segment2 == 'izin_sakit') ? 'active' : '' ?>">
				<i class="fa fa-envelope-open-text"></i>
				<span>Izin</span>
			</a>
			<a href="#" class="mobile-tab-item" data-toggle="app-sidebar-mobile">
				<i class="fa fa-bars"></i>
				<span>Menu</span>
			</a>
		</div>

		<script src="<?= base_url() ?>assets/js/vendor.min.js"></script>
		<script src="<?= base_url() ?>assets/js/app.min.js"></script>
		<script src="<?= base_url() ?>assets/js/theme/transparent.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
		<script src="<?= base_url() ?>assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
		<script src="<?= base_url() ?>assets/js/demo/dashboard-v2.js"></script>

		<script src="<?= base_url() ?>assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
		<script src="<?= base_url() ?>assets/js/sweetalert.min.js"></script>
		<script src="<?= base_url() ?>assets/js/sweetalert.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="<?= base_url() ?>assets/js/dataflash.js"></script>

		<script>
			// Datatable Initializer
			if (typeof $.fn.DataTable === 'function') {
				$('#data-table-default').DataTable({
					responsive: true
				});
				$('#data-table-default2').DataTable({
					responsive: true
				});
			}
			// CKEditor
			if (typeof $.fn.wysihtml5 === 'function') {
				$('#wysihtml5').wysihtml5();
			}
		</script>
	</div>
</body>

</html>