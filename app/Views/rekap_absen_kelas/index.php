<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php $db = \Config\Database::connect(); ?>
<div id="content" class="app-content">
    <div class="row">
        <div class="col-xl-12 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title">PILIH TANGGAL</h4>
                </div>
                <div class="panel-body">
                    <form action="<?= base_url('rekap_absen_kelas') ?>" method="get">
                        <table class="table table-bordered table-td-valign-middle text-white align-middle">
                            <tr>
                                <td width="150">Tanggal</td>
                                <td>
                                    <input type="date" class="form-control" name="tanggal" required value="<?= $tanggal ?>">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Rekapitulasi</button>
                                    <?php if ($tanggal) : ?>
                                        <a href="<?= base_url('rekap_absen_kelas') ?>" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Reset</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-12 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="table-basic-7">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title">REKAPITULASI ABSEN MAPEL</h4>
                </div>
                <div class="panel-body">

                    <div class="text-center mb-4">
                        <h4 class="mb-1">Rekapitulasi Absensi Harian Kelas</h4>
                        <h5 class="mb-2"><?= $sett_apps->nama_sekolah ?></h5>
                        <h5>Tanggal :
                            <?php if ($tanggal) : ?>
                                <span class="text-success fw-bold"><?= date('d F Y', strtotime($tanggal)) ?></span>
                            <?php else : ?>
                                <span class="text-danger fw-bold">Belum pilih tanggal</span>
                            <?php endif; ?>
                        </h5>
                    </div>

                    <?php if ($tanggal) { ?>
                        <!-- TABEL 1: DAFTAR SISWA ALPHA & BELUM ABSEN PULANG -->
                        <div class="table-responsive mb-5">
                            <h5 class="mb-3 text-warning"><i class="fas fa-exclamation-triangle"></i> Daftar Siswa Tidak Absen (Alpha) dan Belum Absen Pulang</h5>
                            <table class="table table-bordered table-hover table-td-valign-middle text-white align-middle text-center" id="data-table-default2">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>No</th>
                                        <th class="text-start">Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Absen Masuk</th>
                                        <th>Absen Pulang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk siswa alpha (tidak ada data absen sama sekali di tanggal tersebut)
                                    $alpha_students = $db->query("
                                        SELECT s.*, k.nama_kelas
                                        FROM siswa s
                                        JOIN kelas k ON s.kelas_id = k.kelas_id
                                        LEFT JOIN user u ON u.username = s.nisn
                                        LEFT JOIN absen a ON a.user_id = u.user_id AND DATE(a.tanggal) = ?
                                        WHERE a.absen_id IS NULL
                                        ORDER BY k.nama_kelas, s.nama_siswa
                                    ", [$tanggal])->getResult();

                                    // Query untuk siswa yang ada data absen tapi jam_pulang null (Bolos/Belum Pulang)
                                    $not_pulang_students = $db->query("
                                        SELECT s.*, k.nama_kelas, a.jam_masuk
                                        FROM siswa s
                                        JOIN kelas k ON s.kelas_id = k.kelas_id
                                        JOIN user u ON u.username = s.nisn
                                        JOIN absen a ON a.user_id = u.user_id AND DATE(a.tanggal) = ?
                                        WHERE a.jam_pulang IS NULL
                                        AND (a.keterangan IS NULL OR a.keterangan NOT IN ('Izin','Sakit'))
                                        ORDER BY k.nama_kelas, s.nama_siswa
                                    ", [$tanggal])->getResult();

                                    // Gabungkan hasil
                                    $students = array_merge($alpha_students, $not_pulang_students);
                                    $no = 1;

                                    if (count($students) > 0) {
                                        foreach ($students as $student) {
                                            $is_alpha = !isset($student->jam_masuk);

                                            $masuk = $is_alpha ? 'Tidak Absen' : $student->jam_masuk;
                                            $pulang = $is_alpha ? '-' : 'Tidak Absen';

                                            $masuk_class = $is_alpha ? 'bg-danger' : 'bg-success';
                                            $pulang_class = 'bg-danger';
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td class="text-start"><?= $student->nama_siswa ?></td>
                                                <td><?= $student->nama_kelas ?></td>
                                                <td class="<?= $masuk_class ?> fw-bold"><?= $masuk ?></td>
                                                <td class="<?= $pulang_class ?> fw-bold"><?= $pulang ?></td>
                                                <td>
                                                    <?php if ($is_alpha): ?>
                                                        <span class="badge bg-danger">Alpha</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Belum Absen Pulang</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-success py-3"><i class="fas fa-check-circle"></i> Semua siswa sudah absen masuk dan pulang dengan lengkap!</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- TABEL 2: REKAPITULASI KELAS (AGREGAT) -->
                        <div class="table-responsive">
                            <h5 class="mb-3 text-info"><i class="fas fa-chart-bar"></i> Rekapitulasi Hadir/Izin/Sakit/Alpha per Kelas</h5>
                            <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white align-middle text-center">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                        <th>Jumlah Siswa</th>
                                        <th class="text-start">Walikelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $kelas = $db->query("SELECT kelas.*, guru.nama_guru FROM kelas LEFT JOIN guru ON guru.guru_id = kelas.walikelas")->getResult();

                                    foreach ($kelas as $value) {
                                        // jml siswa per kelas
                                        $jml_siswa = $db->query("SELECT * FROM siswa WHERE kelas_id = ?", [$value->kelas_id])->getNumRows();

                                        // jml masuk
                                        $jml_masuk = $db->query("SELECT absen.* FROM absen JOIN user ON user.user_id = absen.user_id JOIN siswa ON siswa.nisn = user.username 
                                            WHERE user.level_id = '4' AND DATE(absen.tanggal) = ? AND siswa.kelas_id = ? AND absen.keterangan = 'Masuk'", [$tanggal, $value->kelas_id])->getNumRows();

                                        // jml Izin
                                        $jml_izin = $db->query("SELECT absen.* FROM absen JOIN user ON user.user_id = absen.user_id JOIN siswa ON siswa.nisn = user.username 
                                            WHERE user.level_id = '4' AND DATE(absen.tanggal) = ? AND siswa.kelas_id = ? AND absen.keterangan = 'Izin'", [$tanggal, $value->kelas_id])->getNumRows();

                                        // jml Sakit
                                        $jml_sakit = $db->query("SELECT absen.* FROM absen JOIN user ON user.user_id = absen.user_id JOIN siswa ON siswa.nisn = user.username 
                                            WHERE user.level_id = '4' AND DATE(absen.tanggal) = ? AND siswa.kelas_id = ? AND absen.keterangan = 'Sakit'", [$tanggal, $value->kelas_id])->getNumRows();

                                        $total = $jml_masuk + $jml_izin + $jml_sakit;
                                        $jml_alpha = $jml_siswa - $total;
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $value->nama_kelas ?></td>
                                            <td><?= $jml_masuk ?></td>
                                            <td><?= $jml_izin ?></td>
                                            <td><?= $jml_sakit ?></td>
                                            <td><?= $jml_alpha ?></td>
                                            <td><span class="badge bg-secondary"><?= $jml_siswa ?> Siswa</span></td>
                                            <td class="text-start"><?= $value->nama_guru ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info py-4">
                            <h5 class="mb-0 text-center"><i class="fa fa-info-circle"></i> Silahkan pilih tanggal terlebih dahulu untuk melihat rekapitulasi absen harian kelas.</h5>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>