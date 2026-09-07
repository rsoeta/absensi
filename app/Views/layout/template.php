<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $sett_apps->nama_aplikasi ?? 'e-Absensi' ?></title>

    <!-- Sisipkan kode favicon dinamis di sini -->
    <?php if (isset($sett_apps) && !empty($sett_apps->logo_sekolah)) : ?>
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
    <?php else : ?>
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/default.png') ?>">
    <?php endif; ?>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="<?= base_url('assets/css/vendor.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent/app.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="<?= base_url('assets/plugins/jvectormap-next/jquery-jvectormap.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">

    <!-- Gunakan jQuery dari cdn secara normal, hapus atribut type="beba..." -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/transparent.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <!-- Letakkan di dalam <head> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Letakkan di atas penutup </body> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <?php
    $db = \Config\Database::connect();
    $user_login = $db->table('user')->where('user_id', session()->get('userid'))->get()->getRow();
    ?>

    <div class="app-cover"></div>

    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>

    <div id="app" class="app app-header-fixed app-sidebar-fixed">

        <div id="header" class="app-header">
            <div class="navbar-header">
                <a href="#" class="navbar-brand"><span class="navbar-logo"></span> <?= $sett_apps->nama_aplikasi ?? 'e-Absensi' ?></a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="navbar-nav">
                <div class="navbar-item dropdown">
                    <a href="<?= base_url('absensi') ?>" target="_blank" class="navbar-link dropdown-toggle icon">
                        <img src="<?= base_url('assets/image.webp') ?>" alt="Halaman Website" height="27px">
                        Visit Halaman Absensi
                    </a>
                </div>
                <div class="navbar-item dropdown">
                    <?php
                    $count = $db->table('notif_siswa')->where('status_baca', 'Belum Terbaca')->countAllResults();
                    $t = ($count > 50) ? '50+' : $count;
                    $fetch = $db->table('notif_siswa')->where('status_baca', 'Belum Terbaca')->orderBy('tanggal', 'DESC')->limit(5)->get()->getResult();
                    ?>
                    <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
                        <i class="fa fa-bell"></i>
                        <span class="badge"><?= $t ?></span>
                    </a>
                    <div class="dropdown-menu media-list dropdown-menu-end">
                        <div class="dropdown-header">Notifikasi Tambah/Hapus Siswa</div>
                        <?php foreach ($fetch as $key => $value) { ?>
                            <a href="javascript:;" class="dropdown-item media">
                                <div class="media-left">
                                    <?php if ($value->deksripsi == 'ditambahkan') {
                                        echo '<i class="fa fa-plus media-object bg-success-500"></i>';
                                    } else {
                                        echo '<i class="fa fa-trash media-object bg-danger-500"></i>';
                                    } ?>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading"> <?= $value->nama_siswa ?> </h6>
                                    <div class="text-muted fs-10px">berhasil <?= $value->deksripsi ?> Waktu : <?= $value->tanggal ?></div>
                                </div>
                            </a>
                        <?php } ?>
                        <div class="dropdown-footer text-center">
                            <a href="<?= base_url('notif_siswa') ?>" class="text-decoration-none">View more</a>
                        </div>
                    </div>
                </div>
                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="<?= base_url('assets/img/user/admin.png') ?>" alt="" />
                        <span>
                            <span class="d-none d-md-inline"><?= ucfirst($user_login->username ?? 'Admin') ?></span>
                            <b class="caret"></b>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">Log Out</a>
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
                                <img src="<?= base_url('assets/img/user/admin.png') ?>" alt="" />
                            </div>
                            <div class="menu-profile-info">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <?= ucfirst($user_login->username ?? 'Admin') ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="menu-header">Navigation</div>

                    <div class="menu-item <?= url_is('dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>" class="menu-link ">
                            <div class="menu-icon"><i class="fa fa-home"></i></div>
                            <div class="menu-text">Dashboard</div>
                        </a>
                    </div>

                    <div class="menu-item has-sub <?= url_is('mapel*') || url_is('mapel_peminatan*') || url_is('jenjang*') || url_is('kelas*') || url_is('status_guru*') || url_is('status_pegawai*') || url_is('tahun_ajaran*') ? 'active' : '' ?>">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-list"></i></div>
                            <div class="menu-text">Master Data</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('mapel') ? 'active' : '' ?>"><a href="<?= base_url('mapel') ?>" class="menu-link">
                                    <div class="menu-text">Mapel</div>
                                </a></div>
                            <div class="menu-item <?= url_is('mapel_peminatan') ? 'active' : '' ?>"><a href="<?= base_url('mapel_peminatan') ?>" class="menu-link">
                                    <div class="menu-text">Mapel Peminatan</div>
                                </a></div>
                            <div class="menu-item <?= url_is('jenjang') ? 'active' : '' ?>"><a href="<?= base_url('jenjang') ?>" class="menu-link">
                                    <div class="menu-text">Jenjang</div>
                                </a></div>
                            <div class="menu-item <?= url_is('kelas') ? 'active' : '' ?>"><a href="<?= base_url('kelas') ?>" class="menu-link">
                                    <div class="menu-text">Kelas</div>
                                </a></div>
                            <div class="menu-item <?= url_is('status_guru') ? 'active' : '' ?>"><a href="<?= base_url('status_guru') ?>" class="menu-link">
                                    <div class="menu-text">Status Guru</div>
                                </a></div>
                            <div class="menu-item <?= url_is('status_pegawai') ? 'active' : '' ?>"><a href="<?= base_url('status_pegawai') ?>" class="menu-link">
                                    <div class="menu-text">Status Pegawai</div>
                                </a></div>
                            <div class="menu-item <?= url_is('tahun_ajaran') ? 'active' : '' ?>"><a href="<?= base_url('tahun_ajaran') ?>" class="menu-link">
                                    <div class="menu-text">Tahun Ajaran</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item <?= url_is('sett_mapel*') ? 'active' : '' ?>">
                        <a href="<?= base_url('sett_mapel') ?>" class="menu-link ">
                            <div class="menu-icon"><i class="fa fa-circle" aria-hidden="true"></i></div>
                            <div class="menu-text">Set Guru Mapel</div>
                        </a>
                    </div>

                    <div class="menu-item has-sub <?= url_is('guru*') || url_is('pegawai*') || url_is('siswa*') ? 'active' : '' ?>">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-users"></i></div>
                            <div class="menu-text">Pengguna</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('guru*') ? 'active' : '' ?>"><a href="<?= base_url('guru') ?>" class="menu-link">
                                    <div class="menu-text">Guru</div>
                                </a></div>
                            <div class="menu-item <?= url_is('pegawai*') ? 'active' : '' ?>"><a href="<?= base_url('pegawai') ?>" class="menu-link">
                                    <div class="menu-text">Pegawai</div>
                                </a></div>
                            <div class="menu-item <?= url_is('siswa*') ? 'active' : '' ?>"><a href="<?= base_url('siswa') ?>" class="menu-link">
                                    <div class="menu-text">Siswa</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('izin_sakit*') || url_is('absen*') || url_is('rangking_absen*') ? 'active' : '' ?>">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-calendar"></i></div>
                            <div class="menu-text">Data Absen</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item has-sub <?= url_is('absen/*') ? 'active' : '' ?> ">
                                <a href="javascript:;" class="menu-link">
                                    <div class="menu-text">Absen</div>
                                    <div class="menu-caret"></div>
                                </a>
                                <div class="menu-submenu">
                                    <div class="menu-item <?= url_is('absen/guru') ? 'active' : '' ?>"><a href="<?= base_url('absen/guru') ?>" class="menu-link">
                                            <div class="menu-text">Absen Guru</div>
                                        </a></div>
                                    <div class="menu-item <?= url_is('absen/pegawai') ? 'active' : '' ?>"><a href="<?= base_url('absen/pegawai') ?>" class="menu-link">
                                            <div class="menu-text">Absen Pegawai</div>
                                        </a></div>
                                    <div class="menu-item <?= url_is('absen/siswa') ? 'active' : '' ?>"><a href="<?= base_url('absen/siswa') ?>" class="menu-link">
                                            <div class="menu-text">Absen Siswa</div>
                                        </a></div>
                                </div>
                            </div>
                            <div class="menu-item <?= url_is('izin_sakit*') ? 'active' : '' ?>"><a href="<?= base_url('izin_sakit') ?>" class="menu-link">
                                    <div class="menu-text">Izin /Sakit <span class="menu-label">
                                            <?php echo $db->table('izin_sakit')->where('status', 'Waiting')->countAllResults(); ?> Waiting
                                        </span></div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('rangking_absen*') ? 'active' : '' ?>"><a href="<?= base_url('rangking_absen') ?>" class="menu-link">
                                    <div class="menu-text">Raking Absen</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('surat_panggilan*') ? 'active' : '' ?> ">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-book"></i></div>
                            <div class="menu-text">Surat Panggilan</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= (isset($_GET['page']) && $_GET['page'] == 'list') ? 'active' : '' ?>"><a href="<?= base_url('surat_panggilan?page=list') ?>" class="menu-link">
                                    <div class="menu-text">Daftar Panggilan</div>
                                </a></div>
                            <div class="menu-item <?= (isset($_GET['page']) && $_GET['page'] == 'history') ? 'active' : '' ?>"><a href="<?= base_url('surat_panggilan?page=history') ?>" class="menu-link">
                                    <div class="menu-text">History Panggilan</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('absen_mapel') || url_is('absen_mapel/laporan') ? 'active' : '' ?> ">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            <div class="menu-text">Absen Mapel</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('absen_mapel') ? 'active' : '' ?>"><a href="<?= base_url('absen_mapel') ?>" class="menu-link">
                                    <div class="menu-text">Absen</div>
                                </a></div>
                            <div class="menu-item <?= url_is('absen_mapel/laporan') ? 'active' : '' ?>"><a href="<?= base_url('absen_mapel/laporan') ?>" class="menu-link">
                                    <div class="menu-text">Laporan Absen</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('absen_mapel_peminatan*') ? 'active' : '' ?> ">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            <div class="menu-text">Absen Mapel Peminatan</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('absen_mapel_peminatan') ? 'active' : '' ?>"><a href="<?= base_url('absen_mapel_peminatan') ?>" class="menu-link">
                                    <div class="menu-text">Absen</div>
                                </a></div>
                            <div class="menu-item <?= url_is('absen_mapel_peminatan/laporan') ? 'active' : '' ?>"><a href="<?= base_url('absen_mapel_peminatan/laporan') ?>" class="menu-link">
                                    <div class="menu-text">Laporan Absen</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('kedisiplinan*') ? 'active' : '' ?> ">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-book"></i></div>
                            <div class="menu-text">Laporan Kedisiplinan</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('kedisiplinan/mapel_umum') || url_is('kedisiplinan/view_mapel_umum') ? 'active' : '' ?>"><a href="<?= base_url('kedisiplinan/mapel_umum') ?>" class="menu-link">
                                    <div class="menu-text">Mapel Umum</div>
                                </a></div>
                            <div class="menu-item <?= url_is('kedisiplinan/mapel_peminatan') || url_is('kedisiplinan/view_mapel_peminatan') ? 'active' : '' ?>"><a href="<?= base_url('kedisiplinan/mapel_peminatan') ?>" class="menu-link">
                                    <div class="menu-text">Mapel Peminatan</div>
                                </a></div>
                        </div>
                    </div>

                    <div class="menu-item has-sub <?= url_is('laporan*') || url_is('rekap_absen_kelas*') ? 'active' : '' ?> ">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-book"></i></div>
                            <div class="menu-text">Laporan Absen Umum</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('laporan/laporan_guru') || url_is('laporan/view_laporan_guru') ? 'active' : '' ?>"><a href="<?= base_url('laporan/laporan_guru') ?>" class="menu-link">
                                    <div class="menu-text">Absen Guru</div>
                                </a></div>
                            <div class="menu-item <?= url_is('laporan/laporan_pegawai') || url_is('laporan/view_laporan_pegawai') ? 'active' : '' ?>"><a href="<?= base_url('laporan/laporan_pegawai') ?>" class="menu-link">
                                    <div class="menu-text">Absen Pegawai</div>
                                </a></div>
                            <div class="menu-item <?= url_is('laporan/laporan_siswa') || url_is('laporan/view_laporan_siswa') ? 'active' : '' ?>"><a href="<?= base_url('laporan/laporan_siswa') ?>" class="menu-link">
                                    <div class="menu-text">Absen Siswa</div>
                                </a></div>
                            <div class="menu-item <?= url_is('rekap_absen_kelas*') ? 'active' : '' ?>"><a href="<?= base_url('rekap_absen_kelas') ?>" class="menu-link">
                                    <div class="menu-text">Rekapitulasi Absen Kelas/Hari</div>
                                </a></div>
                        </div>
                    </div>

                    <?php
                    // Kelompokkan pengecekan URL agar HTML tetap bersih dan mudah dibaca
                    $isSettingActive = url_is('user*')
                        || url_is('app_setting*')
                        || url_is('halaman_absensi*')
                        || url_is('absen_geolocation*')
                        || url_is('pengumuman*')
                        || url_is('waktu_absen*')
                        || url_is('hari_libur*')
                        || url_is('konfigurasi_tambahan*');
                    ?>
                    <div class="menu-item has-sub <?= $isSettingActive ? 'active' : '' ?>">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-cogs"></i></div>
                            <div class="menu-text">Setting</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item <?= url_is('user*') ? 'active' : '' ?>">
                                <a href="<?= base_url('user') ?>" class="menu-link">
                                    <div class="menu-text">User Admin</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('app_setting*') ? 'active' : '' ?>">
                                <a href="<?= base_url('app_setting/update/' . encrypt_url(1)) ?>" class="menu-link">
                                    <div class="menu-text">Aplikasi</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('halaman_absensi*') ? 'active' : '' ?>">
                                <a href="<?= base_url('halaman_absensi/update/' . encrypt_url(1)) ?>" class="menu-link">
                                    <div class="menu-text">Absensi QR</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('absen_geolocation*') ? 'active' : '' ?>">
                                <a href="<?= base_url('absen_geolocation/update/' . encrypt_url(1)) ?>" class="menu-link">
                                    <div class="menu-text">Absensi Geolocation</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('pengumuman*') ? 'active' : '' ?>">
                                <a href="<?= base_url('pengumuman/update/' . encrypt_url(1)) ?>" class="menu-link">
                                    <div class="menu-text">Pengumuman</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('waktu_absen*') ? 'active' : '' ?>">
                                <a href="<?= base_url('waktu_absen') ?>" class="menu-link">
                                    <div class="menu-text">Jam Masuk & Pulang</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('hari_libur*') ? 'active' : '' ?>">
                                <a href="<?= base_url('hari_libur') ?>" class="menu-link">
                                    <div class="menu-text">Hari Libur</div>
                                </a>
                            </div>
                            <div class="menu-item <?= url_is('konfigurasi_tambahan*') ? 'active' : '' ?>">
                                <a href="<?= base_url('konfigurasi_tambahan') ?>" class="menu-link">
                                    <div class="menu-text">Konfigurasi Tambahan</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item d-flex">
                        <a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-sidebar-bg"></div>
        <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>

        <div class="flash-data" data-flashdata="<?= session()->getFlashdata('message'); ?>"></div>
        <div class="flash-data2" data-flashdata2="<?= session()->getFlashdata('error'); ?>"></div>

        <!-- AREA KONTEN -->
        <?= $this->renderSection('content') ?>

        <!-- SCRIPT INTI -->
        <script>
            var baseURL = '<?= base_url(); ?>';
            var siteURL = '<?= site_url(); ?>';
        </script>

        <script src="<?= base_url('assets/plugins/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script>

        <!-- Fokus gunakan SweetAlert2 saja -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?= base_url('assets/js/dataflash.js'); ?>"></script>

        <script>
            // Inisialisasi DataTable Global
            $(document).ready(function() {
                $('#data-table-default').DataTable({
                    responsive: true
                });
                $('#data-table-default2').DataTable({
                    responsive: true
                });
                $('#data-table-default3').DataTable({
                    responsive: true,
                    info: false,
                    ordering: false,
                    paging: false
                });

                if ($('#wysihtml5').length) {
                    $('#wysihtml5').wysihtml5();
                }

                // Hilangkan loader saat halaman selesai dimuat
                setTimeout(function() {
                    $('#loader').fadeOut('slow');
                }, 500);
            });

            // Konfigurasi khusus ukuran mobile SweetAlert2
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Trigger Flashdata
            const flashData = $('.flash-data').data('flashdata');
            const flashError = $('.flash-data2').data('flashdata2');

            if (flashData) {
                Toast.fire({
                    icon: 'success',
                    title: flashData
                });
            }
            if (flashError) {
                Toast.fire({
                    icon: 'error',
                    title: flashError
                });
            }
        </script>
    </div>
</body>

</html>