<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Siswa - <?= $siswa->nama_siswa ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .kop-surat img {
            height: 80px;
            position: absolute;
            left: 30px;
            top: 15px;
        }

        .kop-surat h2,
        .kop-surat h3,
        .kop-surat p {
            margin: 0;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .tabel-profil {
            width: 70%;
            float: left;
            border-collapse: collapse;
        }

        .tabel-profil td {
            padding: 8px;
            vertical-align: top;
        }

        .tabel-profil td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .tabel-profil td:nth-child(2) {
            width: 5%;
        }

        .foto-box {
            width: 25%;
            float: right;
            text-align: center;
        }

        .foto-box img {
            width: 100%;
            max-width: 150px;
            border: 2px solid #555;
            padding: 5px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        @media print {
            body {
                font-size: 12pt;
            }

            .kop-surat {
                padding-top: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">
        <div class="kop-surat">
            <?php if (!empty($sett_apps->logo_sekolah)) : ?>
                <img src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>" alt="Logo">
            <?php endif; ?>
            <h2><?= $sett_apps->nama_sekolah ?? 'NAMA SEKOLAH' ?></h2>
            <h3>Aplikasi E-Absensi Digital</h3>
            <p><?= $sett_apps->alamat_sekolah ?? 'Alamat Sekolah' ?></p>
        </div>

        <h3 class="judul">PROFIL DATA SISWA</h3>

        <div class="clearfix">
            <table class="tabel-profil">
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?= $siswa->nisn ?></td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><?= $siswa->nama_siswa ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= $siswa->jk_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td><?= $siswa->nama_kelas ?? 'Belum Diatur' ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td><?= $siswa->tempat_lahir ?>, <?= date('d-m-Y', strtotime($siswa->tanggal_lahir)) ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= $siswa->alamat ?></td>
                </tr>
                <tr>
                    <td>Nama Wali Siswa</td>
                    <td>:</td>
                    <td><?= $siswa->nama_wali_siswa ?></td>
                </tr>
                <tr>
                    <td>No HP Wali Siswa</td>
                    <td>:</td>
                    <td><?= $siswa->no_hp_wali_siswa ?></td>
                </tr>
            </table>

            <div class="foto-box">
                <?php
                $foto = empty($siswa->photo) ? base_url('assets/img/icon/default.png') : base_url('assets/img/siswa/' . $siswa->photo);
                ?>
                <img src="<?= $foto ?>" alt="Foto Siswa">
            </div>
        </div>
    </div>

</body>

</html>