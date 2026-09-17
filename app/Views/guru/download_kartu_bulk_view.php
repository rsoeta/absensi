<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Massal Kartu Guru</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Load library html2canvas, JSZip, dan SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f6f9;
            margin: 0;
        }

        #kartu-container {
            position: absolute;
            top: 0;
            left: 0;
            z-index: -10;
            padding: 20px;
            background: #fff;
        }

        .kartu-guru {
            width: 30rem;
            position: relative;
            padding: 10px;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            margin-bottom: 20px;
        }

        .kartu-guru table {
            border-spacing: 0px;
        }

        .kartu-guru th,
        .kartu-guru td {
            padding: 0px;
        }
    </style>
</head>

<body>
    <!-- Container untuk menampung seluruh kartu yang di-loop -->
    <div id="kartu-container">
        <?php foreach ($guru_data as $guru) : ?>
            <?php
            // Logika Foto
            $foto_tampil = empty($guru->photo) ? base_url('assets/img/icon/default.png') : base_url('assets/img/guru/' . $guru->photo);

            // Logika QR Code
            $qr_path = FCPATH . 'assets/img/qr/guru/' . $guru->qr_code;
            if (empty($guru->qr_code) || !file_exists($qr_path)) {
                $qr_tampil = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($guru->nip);
            } else {
                $qr_tampil = base_url('assets/img/qr/guru/' . $guru->qr_code);
            }
            ?>

            <!-- Beri class .capture-area dan simpan nama file di data-filename -->
            <div class="card kartu-guru border capture-area" data-filename="Kartu_<?= str_replace(" ", "_", $guru->nama_guru) ?>_<?= $guru->nip ?>.png">
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
        <?php endforeach; ?>
    </div>

    <!-- Script Proses Rendering ke ZIP -->
    <script>
        // Gunakan Async Await agar proses bisa diurutkan
        window.onload = async function() {
            Swal.fire({
                title: 'Mempersiapkan Kartu...',
                text: 'Sedang merender gambar, mohon jangan tutup halaman ini.',
                allowOutsideClick: false,
                width: '350px',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var zip = new JSZip(); // Inisiasi library pembuat ZIP
            var cards = document.querySelectorAll('.capture-area');

            // Jeda 2 detik agar background, foto profil, dan API QR termuat sempurna
            await new Promise(r => setTimeout(r, 2000));

            // Looping untuk memfoto setiap kartu
            for (let i = 0; i < cards.length; i++) {
                let card = cards[i];
                let filename = card.getAttribute('data-filename');

                // Update text di SweetAlert agar user tidak bosan menunggu
                Swal.update({
                    text: `Memfoto kartu ke-${i + 1} dari ${cards.length}...`
                });

                let canvas = await html2canvas(card, {
                    scale: 3, // Resolusi HD
                    useCORS: true,
                    backgroundColor: '#ffffff'
                });

                // Ekstrak data base64 gambar
                let imgData = canvas.toDataURL("image/png");
                // Hapus string data:image/png;base64, agar murni isi file saja
                let base64Data = imgData.replace(/^data:image\/(png|jpg);base64,/, "");

                // Masukkan gambar ke dalam objek ZIP
                zip.file(filename, base64Data, {
                    base64: true
                });
            }

            Swal.update({
                title: 'Mengemas ZIP...',
                text: 'Sedikit lagi selesai!'
            });

            // Generate dan Download ZIP
            zip.generateAsync({
                type: "blob"
            }).then(function(content) {
                var link = document.createElement('a');
                link.href = URL.createObjectURL(content);
                link.download = "Kumpulan_Kartu_Guru.zip";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Konfirmasi berhasil lalu tutup tab
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'File ZIP terunduh otomatis.',
                    timer: 2000,
                    showConfirmButton: false,
                    width: '300px'
                }).then(() => {
                    window.close(); // Tutup tab secara otomatis
                });
            });
        };
    </script>
</body>

</html>