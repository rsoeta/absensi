<head>
    <title>Laporan Absen Mapel</title>
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

<body style="min-height: 1100px; margin: none; padding: none; width: 100%;">
    <div class="text-center" style="text-align: center;">

        <img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>"></img>
        <div style="padding-left:150px;padding-right:150px">
            <h3><?= $sett_apps->nama_sekolah  ?></h3>
            <p><?= $sett_apps->alamat_sekolah ?></p>
        </div>
        <hr style="border-top: 2px solid #000">
        <?php $mapelPeminatan = $this->db->query("SELECT * from 
						mapel_peminatan
						join guru on guru.guru_id=mapel_peminatan.guru_id
						where id=$mapel_peminatan_id")->row();
        ?>

        <h3>Laporan Absen Mapel <?= $mapelPeminatan->nama_mapel_peminatan ?> , Pengajar <?= $mapelPeminatan->nama_guru ?></h3>

        <div style="padding-left: 50px; padding-top:30px; padding-bottom:30px;">
            <table class="table table-bordered table-sm">
                <thead>
                    <?php $tanggal =  $this->db->query("SELECT * from absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id GROUP BY tanggal");
                    $tanggalData = $tanggal->result();
                    $jml = $tanggal->num_rows();

                    ?>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;width:2%">NO</th>
                        <th rowspan="2" style="vertical-align: middle;width:12%">Nama siswa</th>
                        <th colspan="<?= $jml ?>" style="vertical-align: middle;">Tanggal Pertemuan</th>
                        <th colspan="5" style="vertical-align: middle;">Keterangan</th>
                    </tr>
                    <tr>

                        <?php $tgl = array(); ?>
                        <?php foreach ($tanggalData as $value) { ?>
                            <?php array_push($tgl, $value->tanggal); ?>
                            <td style="width: 2%;"><?= date('Y-m-d', strtotime($value->tanggal)) ?></td>
                        <?php }  ?>
                        <td style="width: 2%;">H</td>
                        <td style="width: 2%;">A</td>
                        <td style="width: 2%;">I</td>
                        <td style="width: 2%;">S</td>
                        <td style="width: 2%;">B</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($siswa as $value) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value->nama_siswa ?></td>
                            <?php
                            for ($x = 0; $x < $jml; $x++) { ?>
                                <?= cek_absen_mapel_peminatan($mapel_peminatan_id, $value->siswa_id, $tgl[$x]) ?>
                            <?php } ?>
                            <td><?= cek_hadir_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
                            <td><?= cek_alpha_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
                            <td><?= cek_ijin_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
                            <td><?= cek_sakit_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>
                            <td><?= cek_bolos_mapel_peminatan($mapel_peminatan_id, $value->siswa_id) ?></td>

                        </tr>
                    <?php } ?>

                </tbody>
            </table>
            <br>
            <table class="table table-bordered table-sm" style="width: 30%;">
                <tr>
                    <th class="table-warning">Kode</th>
                    <th class="table-warning">Keterangan</th>
                </tr>
                <tr>
                    <td>H</td>
                    <td>Hadir</td>
                </tr>

                <tr>
                    <td>A</td>
                    <td>Alpha</td>
                </tr>
                <tr>
                    <td>I</td>
                    <td>Izin</td>
                </tr>
                <tr>
                    <td>S</td>
                    <td>Sakit</td>
                </tr>
                <tr>
                    <td>B</td>
                    <td>Bolos</td>
                </tr>

            </table>
        </div>

    </div>
</body>