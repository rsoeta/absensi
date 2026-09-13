<!DOCTYPE html>
<html>

<head>
    <title>Laporan Absen Mapel PDF</title>
    <style type="text/css">
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

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
            padding: 5px;
            background-color: #f2f2f2;
        }

        td {
            text-align: center;
            padding: 4px;
        }

        .text-left {
            text-align: left;
            padding-left: 5px;
        }
    </style>
</head>

<body>
    <?php $db = \Config\Database::connect(); ?>
    <div style="text-align: center;">
        <!-- Path logo diperbaiki agar sesuai dengan direktori CI4 standar -->
        <img style="width: 90px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
        <div style="padding: 0 100px;">
            <h3 style="margin-bottom: 5px;"><?= $sett_apps->nama_sekolah ?></h3>
            <p style="margin-top: 0; font-size: 12px;"><?= $sett_apps->alamat_sekolah ?></p>
        </div>
        <hr style="border-top: 2px solid #000; margin-bottom: 20px;">

        <?php
        $namaMapel = $db->query("SELECT * FROM sett_mapel
            JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
            JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
            JOIN guru ON guru.guru_id = sett_mapel.guru_id
            WHERE sett_mapel_id = ?", [$set_mapel_id])->getRow();
        ?>

        <h3 style="margin-bottom: 20px;">Laporan Absen Mapel <?= $namaMapel->nama_mapel ?> , Kelas <?= $namaMapel->nama_kelas ?>, Pengajar <?= $namaMapel->nama_guru ?></h3>

        <table>
            <thead>
                <?php
                $tanggalData = $db->query("SELECT * FROM absen_mapel WHERE set_mapel_id = ? GROUP BY tanggal ORDER BY tanggal ASC", [$set_mapel_id])->getResult();
                $jml = count($tanggalData);
                ?>
                <tr>
                    <th rowspan="2" style="width:3%">NO</th>
                    <th rowspan="2" style="width:18%">Nama Siswa</th>
                    <?php if ($jml > 0): ?>
                        <th colspan="<?= $jml ?>">Tanggal Pertemuan</th>
                    <?php endif; ?>
                    <th colspan="5">Total Keterangan</th>
                </tr>
                <tr>
                    <?php $tgl = []; ?>
                    <?php foreach ($tanggalData as $value) : ?>
                        <?php $tgl[] = $value->tanggal; ?>
                        <td style="width: 3%;"><b><?= date('d/m', strtotime($value->tanggal)) ?></b></td>
                    <?php endforeach; ?>
                    <td style="width: 3%;"><b>H</b></td>
                    <td style="width: 3%;"><b>A</b></td>
                    <td style="width: 3%;"><b>I</b></td>
                    <td style="width: 3%;"><b>S</b></td>
                    <td style="width: 3%;"><b>B</b></td>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($siswa as $value) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-left"><?= $value->nama_siswa ?></td>
                        <?php for ($x = 0; $x < $jml; $x++) : ?>
                            <!-- Helper mereturn tag <td>..</td> jadi langsung di-echo -->
                            <?= cek_absen_mapel($set_mapel_id, $value->siswa_id, $tgl[$x]) ?>
                        <?php endfor; ?>
                        <td><?= cek_hadir_mapel($set_mapel_id, $value->siswa_id) ?></td>
                        <td><?= cek_alpha_mapel($set_mapel_id, $value->siswa_id) ?></td>
                        <td><?= cek_ijin_mapel($set_mapel_id, $value->siswa_id) ?></td>
                        <td><?= cek_sakit_mapel($set_mapel_id, $value->siswa_id) ?></td>
                        <td><?= cek_bolos_mapel($set_mapel_id, $value->siswa_id) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br><br>
        <table style="width: 30%; margin: 0;">
            <tr>
                <th style="background-color: #f8f9fa;">Kode</th>
                <th style="background-color: #f8f9fa;">Keterangan</th>
            </tr>
            <tr>
                <td>H</td>
                <td class="text-left">Hadir</td>
            </tr>
            <tr>
                <td>A</td>
                <td class="text-left">Alpha</td>
            </tr>
            <tr>
                <td>I</td>
                <td class="text-left">Izin</td>
            </tr>
            <tr>
                <td>S</td>
                <td class="text-left">Sakit</td>
            </tr>
            <tr>
                <td>B</td>
                <td class="text-left">Bolos</td>
            </tr>
        </table>
    </div>
</body>

</html>