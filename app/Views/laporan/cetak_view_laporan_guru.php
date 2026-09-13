<head>
    <title>Laporan Data Guru</title>
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

<body style="min-height: 1100px;padding: none; margin: none;">
    <div class="text-center" style="text-align: center;">

        <img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>"></img>
        <div style="padding-left:150px;padding-right:150px">
            <h3><?= $sett_apps->nama_sekolah  ?></h3>
            <p><?= $sett_apps->alamat_sekolah ?></p>
        </div>
        <hr style="border-top: 2px solid #000">

        <h3>Laporan Absen
            <?php if ($user_id == 'semua_data') {
                echo "Semua Guru";
            } else {
                echo nama_guru($user_id);
            } ?>


            <?= nama_bulan($bulan)  ?> - <?= $tahun ?> </h3>
        <div style="padding-top:30px; padding-bottom:30px;">
            <?php
            $kalender = CAL_GREGORIAN;
            $hari = cal_days_in_month($kalender, $bulan, $tahun);
            $url = 'https://api-harilibur.vercel.app/api?month=' . $bulan . '&year=' . $tahun;
            $content = file_get_contents($url);
            $result_holdaydate  = json_decode($content);
            ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;">Nama guru</th>
                        <th colspan="<?= $hari ?>">Tanggal</th>
                        <th colspan="6">Keterangan</th>
                    </tr>
                    <tr>
                        <?php
                        for ($x = 1; $x <= $hari; $x++) { ?>
                            <td style="width: 2%;"><?= $x ?></td>
                        <?php } ?>
                        <td style="width: 2%;">A</td>
                        <td style="width: 2%;">S</td>
                        <td style="width: 2%;">I</td>
                        <td style="width: 2%;">✓</td>
                        <td style="background-color: grey;width: 2%;">✓</td>
                        <td style="background-color: #5353ec;width: 2%;">B</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($user_id == 'semua_data') {
                        $kelas_id = $this->uri->segment(3);
                        $query = $this->db->query("SELECT user.*,siswa.kelas_id from user join siswa on siswa.nisn=user.username where level_id='4' and kelas_id='$kelas_id'");
                        foreach ($query->result() as $data) { ?>
                            <tr>
                                <td><?php echo nama_guru($data->user_id)  ?></td>
                                <?php
                                for ($x = 1; $x <= $hari; $x++) { ?>
                                    <?= cek_absen($data->user_id, $tahun . '/' . $bulan . '/' . $x, $result_holdaydate) ?>
                                <?php } ?>
                                <td><?= cek_alpha($data->user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
                                <td><?= cek_sakit($data->user_id, $hari, $bulan, $tahun) ?></td>
                                <td><?= cek_izin($data->user_id, $hari, $bulan, $tahun) ?></td>
                                <td><?= cek_hadir_tepat($data->user_id, $hari, $bulan, $tahun) ?></td>
                                <td><?= cek_terlambat($data->user_id, $hari, $bulan, $tahun) ?></td>
                                <td><?= cek_bolos($data->user_id, $hari, $bulan, $tahun) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td><?php echo nama_guru($user_id)  ?></td>
                            <?php
                            for ($x = 1; $x <= $hari; $x++) { ?>
                                <?= cek_absen($user_id, $tahun . '/' . $bulan . '/' . $x, $result_holdaydate) ?>
                            <?php } ?>
                            <td><?= cek_alpha($user_id, $hari, $bulan, $tahun, $result_holdaydate) ?></td>
                            <td><?= cek_sakit($user_id, $hari, $bulan, $tahun) ?></td>
                            <td><?= cek_izin($user_id, $hari, $bulan, $tahun) ?></td>
                            <td><?= cek_hadir_tepat($user_id, $hari, $bulan, $tahun) ?></td>
                            <td><?= cek_terlambat($user_id, $hari, $bulan, $tahun) ?></td>
                            <td><?= cek_bolos($data->user_id, $hari, $bulan, $tahun) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table class="table table-bordered table-sm" style="width: 30%; font-size: 12px; margin-top: 1rem;">
                <tr>
                    <th class="table-warning">Kode</th>
                    <th class="table-warning">Keterangan</th>
                </tr>
                <tr>
                    <td>A</td>
                    <td>Alpha</td>
                </tr>

                <tr>
                    <td>S</td>
                    <td>Sakit</td>
                </tr>
                <tr>
                    <td>I</td>
                    <td>Izin</td>
                </tr>
                <tr>
                    <td>✓</td>
                    <td>Hadir Tepat Waktu</td>
                </tr>
                <tr>
                    <td style="background-color: grey;">✓</td>
                    <td>Hadir Terlambat</td>
                </tr>
                <tr>
                    <td style="background-color: #5353ec;">B</td>
                    <td>Bolos/Masuk Tidak Absen Pulang</td>
                </tr>
                <tr>
                    <td style="background-color: yellow;"></td>
                    <td>Hari Libur</td>
                </tr>
                <tr>
                    <td style="background-color: red;"></td>
                    <td>Hari Minggu</td>
                </tr>
            </table>
        </div>

    </div>
</body>