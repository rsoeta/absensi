<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
// Rute Absensi Utama
$routes->get('absensi', 'Absensi::index');
$routes->post('absensi/un_lock', 'Absensi::un_lock');
$routes->post('absensi/get_info_absen', 'Absensi::get_info_absen');
$routes->get('absensi/proses_wa_fonnte', 'Absensi::proses_wa_fonnte');

// Rute Otentikasi (Login & Logout)
$routes->get('auth', 'Auth::index');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/lock', 'Auth::lock');
$routes->post('auth/edit_profil/(:num)', 'Auth::edit_profil/$1');
$routes->post('auth/edit_password/(:num)', 'Auth::edit_password/$1');

// Rute Dashboard
$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard_user', 'Dashboard::user'); // Rute untuk user non-admin

// Rute App Setting
$routes->get('app_setting/update/(:any)', 'App_setting::update/$1');
$routes->post('app_setting/update_action', 'App_setting::update_action');
// Rute Pengaturan Sistem
$routes->get('absen_geolocation/update/(:any)', 'Absen_geolocation::update/$1');
$routes->post('absen_geolocation/update_action', 'Absen_geolocation::update_action');

$routes->get('halaman_absensi/update/(:any)', 'Halaman_absensi::update/$1');
$routes->post('halaman_absensi/update_action', 'Halaman_absensi::update_action');

$routes->get('hari_libur', 'Hari_libur::index');
$routes->get('hari_libur/create', 'Hari_libur::create');
$routes->post('hari_libur/create_action', 'Hari_libur::create_action');
$routes->get('hari_libur/update/(:any)', 'Hari_libur::update/$1');
$routes->post('hari_libur/update_action', 'Hari_libur::update_action');
$routes->get('hari_libur/delete/(:any)', 'Hari_libur::delete/$1');

$routes->get('konfigurasi_tambahan', 'Konfigurasi_tambahan::index');
$routes->post('konfigurasi_tambahan/update_action', 'Konfigurasi_tambahan::update_action');

$routes->get('pengumuman/update/(:any)', 'Pengumuman::update/$1');
$routes->post('pengumuman/update_action', 'Pengumuman::update_action');

$routes->get('waktu_absen', 'Waktu_absen::index');
$routes->get('waktu_absen/create', 'Waktu_absen::create');
$routes->post('waktu_absen/create_action', 'Waktu_absen::create_action');
$routes->get('waktu_absen/delete/(:any)', 'Waktu_absen::delete/$1');
$routes->get('waktu_absen/update/(:any)', 'Waktu_absen::update/$1');
$routes->post('waktu_absen/update_action', 'Waktu_absen::update_action');

// Rute Master Data
$routes->get('mapel', 'Mapel::index');
$routes->get('mapel_peminatan', 'Mapel_peminatan::index');
$routes->get('jenjang', 'Jenjang::index');
$routes->get('kelas', 'Kelas::index');
$routes->get('status_guru', 'Status_guru::index');
$routes->get('status_pegawai', 'Status_pegawai::index');
$routes->get('tahun_ajaran', 'Tahun_ajaran::index');
$routes->get('sett_mapel', 'Sett_mapel::index');
// Rute Master Data (Aksi CRUD Tambahan)
$routes->get('mapel/create', 'Mapel::create');
$routes->post('mapel/create_action', 'Mapel::create_action');
$routes->get('mapel/update/(:segment)', 'Mapel::update/$1');
$routes->post('mapel/update_action', 'Mapel::update_action');
$routes->get('mapel/delete/(:segment)', 'Mapel::delete/$1');

$routes->get('mapel_peminatan/create', 'Mapel_peminatan::create');
$routes->post('mapel_peminatan/create_action', 'Mapel_peminatan::create_action');
$routes->get('mapel_peminatan/update/(:segment)', 'Mapel_peminatan::update/$1');
$routes->post('mapel_peminatan/update_action', 'Mapel_peminatan::update_action');
$routes->get('mapel_peminatan/delete/(:segment)', 'Mapel_peminatan::delete/$1');

$routes->get('jenjang/create', 'Jenjang::create');
$routes->post('jenjang/create_action', 'Jenjang::create_action');
$routes->get('jenjang/update/(:segment)', 'Jenjang::update/$1');
$routes->post('jenjang/update_action', 'Jenjang::update_action');
$routes->get('jenjang/delete/(:segment)', 'Jenjang::delete/$1');

$routes->get('kelas/create', 'Kelas::create');
$routes->post('kelas/create_action', 'Kelas::create_action');
$routes->get('kelas/update/(:segment)', 'Kelas::update/$1');
$routes->post('kelas/update_action', 'Kelas::update_action');
$routes->get('kelas/delete/(:segment)', 'Kelas::delete/$1');

$routes->get('status_guru/create', 'Status_guru::create');
$routes->post('status_guru/create_action', 'Status_guru::create_action');
$routes->get('status_guru/update/(:segment)', 'Status_guru::update/$1');
$routes->post('status_guru/update_action', 'Status_guru::update_action');
$routes->get('status_guru/delete/(:segment)', 'Status_guru::delete/$1');

$routes->get('status_pegawai/create', 'Status_pegawai::create');
$routes->post('status_pegawai/create_action', 'Status_pegawai::create_action');
$routes->get('status_pegawai/update/(:segment)', 'Status_pegawai::update/$1');
$routes->post('status_pegawai/update_action', 'Status_pegawai::update_action');
$routes->get('status_pegawai/delete/(:segment)', 'Status_pegawai::delete/$1');

$routes->get('tahun_ajaran/create', 'Tahun_ajaran::create');
$routes->post('tahun_ajaran/create_action', 'Tahun_ajaran::create_action');
$routes->get('tahun_ajaran/update/(:segment)', 'Tahun_ajaran::update/$1');
$routes->post('tahun_ajaran/update_action', 'Tahun_ajaran::update_action');
$routes->get('tahun_ajaran/delete/(:segment)', 'Tahun_ajaran::delete/$1');

$routes->post('sett_mapel/create_action', 'Sett_mapel::create_action');
$routes->get('sett_mapel/delete/(:segment)', 'Sett_mapel::delete/$1');

// Rute Pengguna (Guru)
$routes->get('guru', 'Guru::index');
$routes->get('guru/create', 'Guru::create');
$routes->post('guru/create_action', 'Guru::create_action');
$routes->get('guru/update/(:segment)', 'Guru::update/$1');
$routes->post('guru/update_action', 'Guru::update_action');
$routes->get('guru/delete/(:segment)', 'Guru::delete/$1');
$routes->get('guru/cetak/(:segment)', 'Guru::cetak/$1');
$routes->post('guru/update_guru', 'Guru::update_guru');
$routes->get('guru/excel', 'Guru::excel');
$routes->post('guru/preview_excel', 'Guru::preview_excel');
$routes->post('guru/insert_all_from_excel', 'Guru::insert_all_from_excel');
$routes->get('guru/download/(:any)', 'Guru::download/$1');

// Rute Pengguna (Pegawai)
$routes->get('pegawai', 'Pegawai::index');
$routes->get('pegawai/create', 'Pegawai::create');
$routes->post('pegawai/create_action', 'Pegawai::create_action');
$routes->get('pegawai/update/(:segment)', 'Pegawai::update/$1');
$routes->post('pegawai/update_action', 'Pegawai::update_action');
$routes->get('pegawai/delete/(:segment)', 'Pegawai::delete/$1');
$routes->get('pegawai/cetak/(:segment)', 'Pegawai::cetak/$1');
$routes->post('pegawai/update_pegawai', 'Pegawai::update_pegawai'); // Pastikan fungsi ini ada/dibuat di Pegawai.php jika diperlukan
$routes->get('pegawai/excel', 'Pegawai::excel');
$routes->post('pegawai/preview_excel', 'Pegawai::preview_excel');
$routes->post('pegawai/insert_all_from_excel', 'Pegawai::insert_all_from_excel');
$routes->get('pegawai/download/(:any)', 'Pegawai::download/$1');

// Rute Pengguna (Siswa)
$routes->get('siswa', 'Siswa::index');
$routes->get('siswa/daftar_siswa', 'Siswa::daftar_siswa');
$routes->get('siswa/create', 'Siswa::create');
$routes->post('siswa/create_action', 'Siswa::create_action');
$routes->get('siswa/update/(:segment)', 'Siswa::update/$1');
$routes->post('siswa/update_action', 'Siswa::update_action');
$routes->get('siswa/delete/(:segment)', 'Siswa::delete/$1');
$routes->get('siswa/cetak/(:segment)', 'Siswa::cetak/$1');
$routes->post('siswa/update_kelas/(:segment)', 'Siswa::update_kelas/$1');
$routes->get('siswa/export_excel', 'Siswa::export_excel');
$routes->post('siswa/preview_excel', 'Siswa::preview_excel');
$routes->post('siswa/insert_all_from_excel', 'Siswa::insert_all_from_excel');
$routes->get('siswa/download/(:any)', 'Siswa::download/$1');
$routes->post('siswa/update_kelas/(:segment)', 'Siswa::update_kelas/$1');
$routes->get('siswa/cetak/(:segment)', 'Siswa::cetak/$1');
$routes->post('siswa/cetak_semua', 'Siswa::cetak_semua');
$routes->get('siswa/cetak_bulk', 'Siswa::cetak_bulk');

// Rute Pengguna (User/Admin)
$routes->get('user', 'User::index');

// Rute Master Data (Kelas)
$routes->get('kelas', 'Kelas::index');
$routes->get('kelas/create', 'Kelas::create');
$routes->post('kelas/create_action', 'Kelas::create_action');
$routes->get('kelas/update/(:segment)', 'Kelas::update/$1');
$routes->post('kelas/update_action', 'Kelas::update_action');
$routes->get('kelas/delete/(:segment)', 'Kelas::delete/$1');

// Rute Master Data (Jenjang)
$routes->get('jenjang', 'Jenjang::index');
$routes->get('jenjang/read/(:segment)', 'Jenjang::read/$1');
$routes->get('jenjang/create', 'Jenjang::create');
$routes->post('jenjang/create_action', 'Jenjang::create_action');
$routes->get('jenjang/update/(:segment)', 'Jenjang::update/$1');
$routes->post('jenjang/update_action', 'Jenjang::update_action');
$routes->get('jenjang/delete/(:segment)', 'Jenjang::delete/$1');

// Rute Master Data (Status Guru)
$routes->get('status_guru', 'Status_guru::index');
$routes->get('status_guru/create', 'Status_guru::create');
$routes->post('status_guru/create_action', 'Status_guru::create_action');
$routes->get('status_guru/update/(:segment)', 'Status_guru::update/$1');
$routes->post('status_guru/update_action', 'Status_guru::update_action');
$routes->get('status_guru/delete/(:segment)', 'Status_guru::delete/$1');

// Rute Data Absen Khusus & Laporan
$routes->get('izin_sakit', 'Izin_sakit::index');
$routes->post('izin_sakit/update_status', 'Izin_sakit::update_status');
$routes->get('izin_sakit/approved_all_data', 'Izin_sakit::approved_all_data');
$routes->get('izin_sakit/delete/(:segment)', 'Izin_sakit::delete/$1');
$routes->get('izin_sakit/download/(:segment)', 'Izin_sakit::download/$1');
$routes->post('izin_sakit/detail_siswa', 'Izin_sakit::detail_siswa');
$routes->get('izin_sakit/create', 'Izin_sakit::create');
$routes->post('izin_sakit/create_action', 'Izin_sakit::create_action');
$routes->get('izin_sakit/update/(:segment)', 'Izin_sakit::update/$1');
$routes->post('izin_sakit/update_action', 'Izin_sakit::update_action');

$routes->get('rangking_absen', 'Rangking_absen::index');
$routes->get('surat_panggilan', 'Surat_panggilan::index');
$routes->get('absen_mapel', 'Absen_mapel::index');
$routes->get('absen_mapel_peminatan', 'Absen_mapel_peminatan::index');
$routes->get('kedisiplinan/(:any)', 'Kedisiplinan::index/$1');
$routes->get('laporan/(:any)', 'Laporan::index/$1');
$routes->get('rekap_absen_kelas', 'Rekap_absen_kelas::index');

// Rute CRUD Absen Entitas (Contoh dasar, bisa disesuaikan nanti dengan parameter)
$routes->get('absen/guru', 'Absen::guru');
$routes->get('absen/pegawai', 'Absen::pegawai');
$routes->get('absen/siswa', 'Absen::siswa');
$routes->get('absen/delete/(:segment)/(:segment)', 'Absen::delete/$1/$2');

// Rute Pengguna (User/Admin)
$routes->get('user', 'User::index');
$routes->get('user/create', 'User::create');
$routes->post('user/create_action', 'User::create_action');
$routes->get('user/update/(:segment)', 'User::update/$1');
$routes->post('user/update_action', 'User::update_action');
$routes->get('user/delete/(:segment)', 'User::delete/$1');
$routes->get('user/read/(:segment)', 'User::read/$1');

// Rute untuk pengujian manual via Browser (Laragon)
$routes->get('cron_wa/process', 'Cron_wa::process');

// Rute untuk eksekusi via Terminal / Cronjob (cPanel produksi)
$routes->cli('cron_wa/process', 'Cron_wa::process');
