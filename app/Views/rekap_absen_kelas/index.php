<div id="content" class="app-content">
    <div class="row">
        <div class="col-xl-12 ui-sortable">
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title">PILIH TANGGAL</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"
                            data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i
                                class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                                class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i
                                class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">

                    <form action="" method="get" enctype="multipart/form-data">
                        <table class="table  table-bordered table-hover table-td-valign-middle">
                            <tr>
                                <td>Tanggal
                                </td>
                                <td>
                                    <input type="date" <?php if (isset($_GET['tanggal'])) { ?>
                                        value="<?= $_GET['tanggal'] ?>" <?php } ?> class="form-control" id="tanggal" required
                                        name="tanggal">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View
                                        Rekapitulasi</button>
                                    <?php if (isset($_GET['tanggal'])) { ?>
                                        <a href="<?= base_url() ?>rekap_absen_kelas" class="btn btn-warning"><i
                                                class="fas fa-refresh" aria-hidden="true"></i> Reset</a>
                                    <?php } ?>
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
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                                class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i
                                class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                                class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">

                    <center>
                        <h5>Rekapitulasi absensi harian kelas</h5>
                        <h5><?= $sett_apps->nama_sekolah ?></h5>
                        <h5>Tanggal : <?php if (isset($_GET['tanggal'])) { ?>
                                <span style="color: green;"><?= $_GET['tanggal'] ?></span>
                            <?php } else { ?>
                                <span style="color: red;">Belum pilih tanggal</span>
                            <?php } ?>
                        </h5>
                    </center>
                    <br>
                    <!-- Combined Table for Alpha and Not Yet Absent Out Students -->
                    <?php if (isset($_GET['tanggal'])) { ?>
                        <div class="table-responsive">
                            <h4>Daftar Siswa Tidak Absen (Alpha) dan Belum Absen Pulang</h4>
                            <table class="table table-bordered table-hover table-td-valign-middle text-white" id="data-table-default2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Absen Masuk</th>
                                        <th>Absen Pulang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $tanggal = $_GET['tanggal'];

                                    // Query untuk siswa alpha (tidak ada data absen sama sekali di tanggal tersebut)
                                    $alpha_students = $this->db->query("
                SELECT s.*, k.nama_kelas
                FROM siswa s
                JOIN kelas k ON s.kelas_id = k.kelas_id
                LEFT JOIN user u ON u.username = s.nisn
                LEFT JOIN absen a ON a.user_id = u.user_id AND a.tanggal = '$tanggal'
                WHERE a.absen_id IS NULL
                ORDER BY k.nama_kelas, s.nama_siswa
            ")->result();

                                    // Query untuk siswa yang ada data absen tapi jam_pulang null
                                $not_pulang_students = $this->db->query("
                SELECT s.*, k.nama_kelas, a.jam_masuk
                FROM siswa s
                JOIN kelas k ON s.kelas_id = k.kelas_id
                JOIN user u ON u.username = s.nisn
                JOIN absen a ON a.user_id = u.user_id AND a.tanggal = '$tanggal'
                WHERE a.jam_pulang IS NULL
                AND (a.keterangan IS NULL OR a.keterangan NOT IN ('Izin','Sakit'))
                ORDER BY k.nama_kelas, s.nama_siswa
            ")->result();

                                    // Gabungkan hasil
                                    $students = array_merge($alpha_students, $not_pulang_students);

                                    if (count($students) > 0) {
                                        foreach ($students as $student) {
                                            $is_alpha = !isset($student->jam_masuk);
                                            $status = $is_alpha ? 'Alpha' : 'Belum Absen Pulang';
                                            $masuk = $is_alpha ? 'Tidak Absen' : $student->jam_masuk;
                                            $pulang = $is_alpha ? '-' : 'Tidak Absen';

                                            $masuk_class = $is_alpha ? 'bg-danger' : 'bg-success';
                                            $pulang_class = $is_alpha ? 'bg-danger' : 'bg-danger';
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $student->nama_siswa ?></td>
                                                <td><?= $student->nama_kelas ?></td>
                                                <td class="<?= $masuk_class ?>"><?= $masuk ?></td>
                                                <td class="<?= $pulang_class ?>"><?= $pulang ?></td>
                                                <td>
                                                    <?php if ($is_alpha): ?>
                                                        <span class="badge bg-danger">Alpha</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Belum Absen Pulang</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Semua siswa sudah absen lengkap</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <br>
                    <div class="table-responsive">
                        <table id="data-table-default"
                            class="table table-bordered table-hover table-td-valign-middle text-white">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Hadir</th>
                                    <th>Izin</th>
                                    <th>Sakit</th>
                                    <th>Alpha</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Walikelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($_GET['tanggal'])) {
                                    $no = 1;
                                    $tanggal = $_GET['tanggal'];
                                    $kelas = $this->db->query("SELECT kelas.*,guru.nama_guru FROM kelas left join guru on guru.guru_id=kelas.walikelas")->result() ?>
                                    <?php foreach ($kelas as $value) {
                                        // jml siswa
                                        $query = $this->db->query("SELECT * FROM siswa where kelas_id='$value->kelas_id'");
                                        $jml_siswa =  $query->num_rows();
                                        // jml masuk
                                        $masuk = $this->db->query("SELECT absen.*,user.level_id,user.username,siswa.kelas_id FROM absen
                                        join user on user.user_id = absen.user_id
                                        join siswa on siswa.nisn = user.username
                                        where level_id='4' and tanggal='$tanggal' and kelas_id='$value->kelas_id' and keterangan='Masuk'");
                                        $jml_masuk =  $masuk->num_rows();

                                        // jml Izin
                                        $izin = $this->db->query("SELECT absen.*,user.level_id,user.username,siswa.kelas_id FROM absen
                                         join user on user.user_id = absen.user_id
                                         join siswa on siswa.nisn = user.username
                                         where level_id='4' and tanggal='$tanggal' and kelas_id='$value->kelas_id' and keterangan='Izin'");
                                        $jml_izin =  $izin->num_rows();

                                        // jml Sakit
                                        $sakit = $this->db->query("SELECT absen.*,user.level_id,user.username,siswa.kelas_id FROM absen
                                        join user on user.user_id = absen.user_id
                                        join siswa on siswa.nisn = user.username
                                        where level_id='4' and tanggal='$tanggal' and kelas_id='$value->kelas_id' and keterangan='Sakit'");
                                        $jml_sakit =  $sakit->num_rows();
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
                                            <td><?= $jml_siswa ?> Siswa</td>
                                            <td><?= $value->nama_guru ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="alert alert-info" role="alert">
                                                <h5> <i class="fa fa-info"></i> Silahkan pilih tanggal terlebih dahulu untuk
                                                    view rekapitulasi absen kelas !!!</h5>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".theSelect").select2();
    })
</script>