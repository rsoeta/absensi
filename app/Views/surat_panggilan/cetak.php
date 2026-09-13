<head>
    <title>Surat Panggilan Wali Murid</title>
    <style type="text/css">
        table.tabel-header {
            border-collapse: collapse;
            width: 100%;
        }

        table.tabel-header th {
            text-align: center;
        }

        table.tabel-header td {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body onload="window.print()" style="min-height: 1100px; margin: none; padding: none; width: 100%;">
    <div>
        <table class="tabel-header" style="border: none; margin-bottom: 15px;">
            <tr>
                <td rowspan="4"><img style="width: 100px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>"></img></td>
                <td>
                    <h1 style="font-size: 18px;">MAJELIS PENDIDIKAN DASAR DAN MENENGAH</h1>
                </td>
            </tr>
            <tr>
                <td>
                    <h1 style="font-size: 23px;">PONPES DAARU ASY-SYIFA</h1>
                </td>
            </tr>
            <tr>
                <td>
                    <h1><?= $sett_apps->nama_sekolah  ?></h1>
                </td>
            </tr>
            <tr>
                <td>
                    <p><?= $sett_apps->alamat_sekolah ?></p>
                </td>
            </tr>
        </table>
        <hr style="border-top: 1px solid #000; margin: 0;">
        <hr style="border-top: 3px solid #000; margin: 1px;">

        <div style="padding-top: 30px;padding-right: 10px; padding-left: 10px">
            <p class="text-right">Pakenjeng, <?= date('j') ?> <?= nama_bulan(date('m'))  ?> <?= date('Y') ?></p>
            <table style="padding-bottom: 30px;">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td><?= date('d-m') . '/S-PL/' . $id_surat_panggilan . '/' . date('Y') ?></td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td>Surat Panggilan</td>
                </tr>
            </table>

            <table style="margin-bottom: 25px;">
                <tr>
                    <td>Kepada</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Yth.</td>
                    <td><b>Bapak/Ibu Orang Tua/Wali Murid <?= $asal_kelas ?></b></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?= $sett_apps->nama_sekolah ?> Tahun Pelajaran <?= $tahun_ajaran ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Di Tempat</td>
                    <td></td>
                </tr>
            </table>

            <p>Dengan Hormat,</p>

            <p style="text-indent: 4em; text-align: justify;">Sehubungan dengan hasil pemantauan kami, diberitahukan bahwa siswa/siswi</p>

            <table style="margin-left: 30px;">
                <tr>
                    <td style="width: 100px;">Nama</td>
                    <td style="width: 5px;">:</td>
                    <td><?= $nama_siswa ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?= $nisn ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td><?= $kelas ?></td>
                </tr>
            </table>

            <p style="text-indent: 4em; text-align: justify;"><?= $keterangan ?>, terkait dengan hal tersebut kami memohon kehadiran Bapak/Ibu selaku Orang Tua/Wali murid untuk datang ke sekolah pada :</p>
            <table style="margin-left: 30px;">
                <tr>
                    <td style="width: 100px;">Hari/Tanggal</td>
                    <td style="width: 5px;">:</td>
                    <td><?= $hari ?>, <?= date('d-m-Y', strtotime($tgl)) ?></td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td><?= $waktu ?> WIB</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td><?= $tempat ?></td>
                </tr>
            </table>
            <p>Demikian surat panggilan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

            <div style="margin-bottom: 3rem;"></div>

            <table style="width: 100%;">
                <tr>
                    <td>Mengetahui,</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Kepala Sekolah</td>
                    <td></td>
                    <td>Wali Kelas</td>
                </tr>
                <tr>
                    <td style="height: 90px;"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?= $sett_apps->kepala_sekolah  ?></td>
                    <td></td>
                    <td><?= $walikelas ?></td>
                </tr>

            </table>

        </div>
    </div>
</body>