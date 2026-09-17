<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Kartu - <?= $guru->nama_guru ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Load library untuk merender HTML ke Gambar dan SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f6f9;
            margin: 0;
        }

        /* Container Pembungkus Kartu */
        #kartu-container {
            /* Kita posisikan di pojok dan kita timpa dengan z-index minus agar tidak terlihat user */
            position: absolute;
            top: 0;
            left: 0;
            z-index: -10;
            padding: 20px;
            background: #fff;
            /* Pastikan background kanvas putih bersih */
        }

        /* Desain asli cetak.php Anda */
        .kartu-siswa {
            width: 30rem;
            position: relative;
            padding: 10px;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
        }

        .kartu-siswa table {
            border-spacing: 0px;
        }

        .kartu-siswa th,
        .kartu-siswa td {
            padding: 0px;
        }
    </style>
</head>

<body>

    <?php
    // Logika Fallback Foto Profil
    $foto_tampil = empty($guru->photo) ? base_url('assets/img/icon/default.png') : base_url('assets/img/guru/' . $guru->photo);

    // Logika Fallback QR Code
    $qr_path = FCPATH . 'assets/img/qr/guru/' . $guru->qr_code;
    if (empty($guru->qr_code) || !file_exists($qr_path)) {
        // Jika file fisik tidak ada, generate langsung via API menggunakan NIP
        $qr_tampil = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($guru->nip);
    } else {
        $qr_tampil = base_url('assets/img/qr/guru/' . $guru->qr_code);
    }
    ?>

    <!-- Elemen Tersembunyi untuk difoto oleh html2canvas -->
    <div id="kartu-container">
        <div id="capture-area" class="card kartu-siswa border">
            <p style="color: white; margin-top: 13px; right:50px; position: absolute;font-family: Cambria;font-size: 15px;"><strong>MAJELIS PENDIDIKAN DASAR DAN MENENGAH</strong></p>
            <p style="color: white; margin-top: 30px; right:100px; position: absolute;font-family: Cambria;font-size: 16px;"><strong>MUHAMMADIYAH PAKENJENG</strong></p>
            <p style="color: white; margin-top: 50px; right:80px; position: absolute;font-family: Cambria;font-size: 14px;text-transform: uppercase;"><strong><?= $sett_apps->nama_sekolah ?></strong></p>
            <p style="margin-top: 90px; right:130px; position: absolute;font-family: Cambria;font-size: 20px;"><strong>KARTU GURU</strong></p>

            <table style="margin-top: 130px; position: absolute; right:130px; text-align: right; font-family: Cambria;font-size: 12px;">
                <tr>
                    <td>NIP</td>
                </tr>
                <tr>
                    <td><?= $guru->nip ?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                </tr>
                <tr>
                    <td><strong style="font-size: 10px;"><?= $guru->nama_guru ?></strong></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal lahir</td>
                </tr>
                <tr>
                    <td><?= $guru->tempat_lahir ?>, <?= $guru->tanggal_lahir ?></td>
                </tr>
                <tr>
                    <td>Alamat, <?= $guru->alamat ?></td>
                </tr>
            </table>

            <p style="font-family:Verdana; right:50px; margin-top: 256px; text-align:right; padding-left: 10px;font-size: 8px; position: absolute;">Alamat Sekolah : <?= $sett_apps->alamat_sekolah ?> </p>

            <img class="card-img-top" src="<?= base_url('assets/img/kartu/birunom.png') ?>" style="width: 100%; border-radius: 5px;">

            <img style="border: 1px solid #ffffff;position: absolute;right: 30px;margin-top: 130px; object-fit:cover;" src="<?= $foto_tampil ?>" width="85px" height="100px">
            <img style="position: absolute;margin-left: 35px;margin-top: 130px;" src="<?= $qr_tampil ?>" width="120px" height="120px">
            <img style="position: absolute;margin-left: 30px;margin-top: 10px;" src="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>" width="65px" height="65px">
        </div>
    </div>

    <!-- Script Proses Otomatis -->
    <script>
        window.onload = function() {
            // Tampilkan loading screen agar pengguna tahu sedang ada proses
            Swal.fire({
                title: 'Sedang Mengunduh...',
                text: 'Mempersiapkan Kartu Guru Resolusi Tinggi',
                allowOutsideClick: false,
                width: '350px',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Beri jeda 800ms agar semua gambar (logo, background, foto) termuat sempurna
            setTimeout(function() {
                var element = document.getElementById('capture-area');

                // Eksekusi html2canvas dengan skala 3 agar resolusinya tajam (HD)
                html2canvas(element, {
                    scale: 3,
                    useCORS: true, // Wajib true agar gambar API/URL luar bisa dirender
                    backgroundColor: '#ffffff'
                }).then(function(canvas) {

                    // Ekstrak canvas jadi file format PNG
                    var imgData = canvas.toDataURL("image/png");

                    // Simulasikan klik link untuk memicu auto-download
                    var link = document.createElement('a');
                    link.download = 'Kartu_Guru_<?= str_replace(" ", "_", $guru->nama_guru) ?>_<?= $guru->nip ?>.png';
                    link.href = imgData;

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Konfirmasi berhasil lalu tutup tab
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kartu siap digunakan.',
                        timer: 1500,
                        showConfirmButton: false,
                        width: '300px'
                    }).then(() => {
                        window.close(); // Tutup tab secara otomatis
                    });
                });
            }, 800);
        };
    </script>
</body>

</html>