<!DOCTYPE html>
<html>

<head>
    <!-- KUNCI PERBAIKAN: Memaksa Excel membaca karakter UTF-8 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Export Excel Siswa</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <!-- Jumlah colspan dinamis: Kolom nama (1) + jumlah hari + total rekap (6) -->
                <th colspan="<?= count($periode_dates) + 7 ?>" style="font-size: 16px; font-weight: bold; text-align: left;">
                    LAPORAN ABSEN <?= strtoupper($teks_judul) ?>
                </th>
            </tr>
            <tr>
                <th colspan="<?= count($periode_dates) + 7 ?>" style="font-size: 14px; text-align: left;">
                    <?= strtoupper($teks_periode) ?>
                </th>
            </tr>
            <tr>
                <th colspan="<?= count($periode_dates) + 7 ?>" style="border: none;"></th>
            </tr>
            <tr>
                <th rowspan="2" style="text-align: left; padding: 5px; background-color: #f2f2f2;">Nama Siswa</th>
                <th colspan="<?= count($periode_dates) ?>" style="background-color: #f2f2f2;">Tanggal</th>
                <th colspan="6" style="background-color: #f2f2f2;">Keterangan Total</th>
            </tr>
            <tr>
                <?php foreach ($periode_dates as $pd) : ?>
                    <th style="background-color: #f2f2f2;"><?= date('d', strtotime($pd)) ?></th>
                <?php endforeach; ?>
                <th style="background-color: #f2f2f2;">A</th>
                <th style="background-color: #f2f2f2;">S</th>
                <th style="background-color: #f2f2f2;">I</th>
                <th style="background-color: #f2f2f2;">✓</th>
                <th style="background-color: #808080; color: white;">✓</th>
                <th style="background-color: #5353ec; color: white;">B</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($query_siswa as $data) :
                if (empty($data->user_id)) continue;
            ?>
                <tr>
                    <td style="text-align: left; padding: 5px;"><?= nama_siswa($data->user_id) ?></td>

                    <?php foreach ($periode_dates as $pd) :
                        $format_tgl = date('Y/m/j', strtotime($pd));
                    ?>
                        <?= cek_absen($data->user_id, $format_tgl, $result_holdaydate) ?>
                    <?php endforeach; ?>

                    <?php
                    $tgl_awal = $periode_dates[0];
                    $tgl_akhir = end($periode_dates);
                    ?>
                    <td><?= cek_alpha($data->user_id, $tgl_awal, $tgl_akhir, $result_holdaydate) ?></td>
                    <td><?= cek_sakit($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
                    <td><?= cek_izin($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
                    <td><?= cek_hadir_tepat($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
                    <td><?= cek_terlambat($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
                    <td><?= cek_bolos($data->user_id, $tgl_awal, $tgl_akhir) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>