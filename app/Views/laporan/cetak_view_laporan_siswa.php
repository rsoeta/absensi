<head>
    <title>Laporan Data Siswa</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }
    </style>
</head>

<body style="min-height: 1100px; padding: none; margin: none;">
    <?php $db = \Config\Database::connect(); ?>
    <div class="text-center" style="text-align: center;">

        <img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
        <div style="padding-left:150px; padding-right:150px">
            <h3><?= $sett_apps->nama_sekolah ?></h3>
            <p><?= $sett_apps->alamat_sekolah ?></p>
        </div>
        <hr style="border-top: 2px solid #000">

        <!-- Menggunakan Judul & Periode Pintar yang dikirim dari Controller -->
        <h3>
            Laporan Absen <?= $teks_judul ?> <br>
            <small style="font-size: 14px; font-weight: normal;"><?= $teks_periode ?></small>
        </h3>

        <div style="padding-top:30px; padding-bottom:30px;">
            <?php
            // Generate Kolom Tanggal Dinamis (Mendukung Bulan & Rentang Tanggal)
            $periode_dates = [];
            if (isset($tipe_filter) && $tipe_filter == 'rentang' && !empty($tanggal_mulai) && !empty($tanggal_akhir)) {
                $begin = new DateTime($tanggal_mulai);
                $end = new DateTime($tanggal_akhir);
                $end = $end->modify('+1 day');
                $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
                foreach ($daterange as $date) {
                    $periode_dates[] = $date->format("Y-m-d");
                }
            } else {
                $kalender = CAL_GREGORIAN;
                $hari = cal_days_in_month($kalender, $bulan, $tahun);
                for ($x = 1; $x <= $hari; $x++) {
                    $periode_dates[] = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($x, 2, '0', STR_PAD_LEFT);
                }
            }
            $total_hari = count($periode_dates);
            ?>

            <table class="table table-bordered table-sm" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle; text-align: left; padding-left: 5px;">Nama Siswa</th>
                        <th colspan="<?= $total_hari ?>">Tanggal</th>
                        <th colspan="6">Keterangan Total</th>
                    </tr>
                    <tr>
                        <?php foreach ($periode_dates as $pd) : ?>
                            <th style="width: 20px; font-size: 11px;"><?= date('d', strtotime($pd)) ?></th>
                        <?php endforeach; ?>
                        <th style="width: 25px;">A</th>
                        <th style="width: 25px;">S</th>
                        <th style="width: 25px;">I</th>
                        <th style="width: 25px;">✓</th>
                        <th style="background-color: grey; color: white; width: 25px;">✓</th>
                        <th style="background-color: #5353ec; color: white; width: 25px;">B</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($user_id == 'semua_data') {
                        if (!empty($kelas_id)) {
                            $query = $db->query("SELECT u.*, s.kelas_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' AND s.kelas_id='$kelas_id' ORDER BY s.nama_siswa ASC")->getResult();
                        } else {
                            $query = $db->query("SELECT u.*, s.kelas_id FROM user u JOIN siswa s ON s.nisn = u.username WHERE u.level_id='4' ORDER BY s.nama_siswa ASC")->getResult();
                        }

                        foreach ($query as $data) { ?>
                            <tr>
                                <td style="text-align: left; padding-left: 5px;"><?= nama_siswa($data->user_id) ?></td>
                                <?php foreach ($periode_dates as $pd) :
                                    $format_tgl = date('Y/m/j', strtotime($pd));
                                ?>
                                    <?= cek_absen($data->user_id, $format_tgl, $result_holdaydate ?? []) ?>
                                <?php endforeach; ?>

                                <?php
                                $tgl_awal_p = $periode_dates[0];
                                $tgl_akhir_p = end($periode_dates);
                                ?>
                                <td><?= cek_alpha($data->user_id, $tgl_awal_p, $tgl_akhir_p, $result_holdaydate ?? []) ?></td>
                                <td><?= cek_sakit($data->user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                                <td><?= cek_izin($data->user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                                <td><?= cek_hadir_tepat($data->user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                                <td><?= cek_terlambat($data->user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                                <td><?= cek_bolos($data->user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td style="text-align: left; padding-left: 5px;"><?= nama_siswa($user_id) ?></td>
                            <?php foreach ($periode_dates as $pd) :
                                $format_tgl = date('Y/m/j', strtotime($pd));
                            ?>
                                <?= cek_absen($user_id, $format_tgl, $result_holdaydate ?? []) ?>
                            <?php endforeach; ?>

                            <?php
                            $tgl_awal_p = $periode_dates[0];
                            $tgl_akhir_p = end($periode_dates);
                            ?>
                            <td><?= cek_alpha($user_id, $tgl_awal_p, $tgl_akhir_p, $result_holdaydate ?? []) ?></td>
                            <td><?= cek_sakit($user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                            <td><?= cek_izin($user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                            <td><?= cek_hadir_tepat($user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                            <td><?= cek_terlambat($user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                            <td><?= cek_bolos($user_id, $tgl_awal_p, $tgl_akhir_p) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Tabel Keterangan Kode -->
            <table class="table table-bordered table-sm" style="width: 35%; font-size: 12px; margin-top: 1.5rem; text-align: left;">
                <tr>
                    <th class="table-warning" style="text-align: center; width: 15%;">Kode</th>
                    <th class="table-warning" style="padding-left: 8px;">Keterangan</th>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">A</td>
                    <td style="padding-left: 8px;">Alpha</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">S</td>
                    <td style="padding-left: 8px;">Sakit</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">I</td>
                    <td style="padding-left: 8px;">Izin</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">✓</td>
                    <td style="padding-left: 8px;">Hadir Tepat Waktu</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold; background-color: grey; color: white;">✓</td>
                    <td style="padding-left: 8px;">Hadir Terlambat</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold; background-color: #5353ec; color: white;">B</td>
                    <td style="padding-left: 8px;">Bolos/Masuk Tidak Absen Pulang</td>
                </tr>
                <tr>
                    <td style="background-color: yellow;"></td>
                    <td style="padding-left: 8px;">Hari Libur</td>
                </tr>
                <tr>
                    <td style="background-color: red;"></td>
                    <td style="padding-left: 8px;">Hari Minggu</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Memunculkan dialog print browser secara otomatis
            setTimeout(function() {
                window.print();
            }, 500); // Beri jeda setengah detik agar tabel dan gambar sempat ter-render sempurna
        }
    </script>
</body>