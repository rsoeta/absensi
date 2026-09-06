<?php $db = \Config\Database::connect(); ?>
<style>
    /* Optimasi SweetAlert2 khusus untuk layar Mobile */
    .swal2-popup {
        font-size: 0.85rem !important;
        width: 85% !important;
        max-width: 320px !important;
        padding: 1.2em !important;
        border-radius: 15px !important;
    }

    .swal2-title {
        font-size: 1.25rem !important;
        margin-bottom: 0.5em !important;
    }

    .swal2-html-container {
        margin: 0.5em 1em 0 !important;
    }

    #canvas-jam {
        margin: auto;
        display: block;
    }

    #video_capture {
        margin: auto;
        display: none;
    }

    #canvas_camera {
        margin: auto;
        display: none;
    }

    .note-capture-photo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        text-align: center;
    }

    .success-indicator {
        color: green;
        font-size: 2rem;
        position: absolute;
        top: 50%;
    }
</style>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="content" class="app-content">
    <div class="row">
        <div class="col-md-6 mb-5">
            <?php
            $last = $db->table('tahun_ajaran')->orderBy('tahun_ajaran_id', 'desc')->limit(1)->get()->getRow();
            $tgl_awal = $last ? $last->tgl_awal : date('Y-m-d');
            $tgl_akhir = $last ? $last->tgl_akhir : date('Y-m-d');
            $user_id = session()->get('userid');
            $user_data = $db->table('user')->where('user_id', $user_id)->get()->getRow();
            $username = $user_data ? $user_data->username : '';

            $sql = "SELECT SUM(point) AS point, SUM(point_pulang) AS point_pulang, absen.user_id, siswa.nama_siswa, kelas.nama_kelas 
                    FROM absen
                    JOIN user ON user.user_id = absen.user_id
                    JOIN siswa ON siswa.nisn = user.username
                    JOIN kelas ON kelas.kelas_id = siswa.kelas_id
                    WHERE user.level_id=4 AND absen.user_id='$user_id' 
                    AND tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'
                    GROUP BY absen.user_id ORDER BY point DESC";
            $dataRangking = $db->query($sql)->getRow();
            $totalPoint = $dataRangking ? ($dataRangking->point + $dataRangking->point_pulang) : 0;
            ?>
            <div class="alert alert-light" role="alert">
                <b>Point Absen Tahun Ajaran <?= $last ? $last->nama_tahun_ajaran : '' ?> : <?= $totalPoint ?> Point</b>
            </div>

            <?php if (isset($is_geo) && $is_geo == 'Aktif') { ?>
                <div class="alertnya">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Perhatian</strong> Pastikan izin lokasi diberikan untuk mengetahui lokasi anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <input type="hidden" id="is_radius" value="">
                            <?php if (isset($is_photo) && $is_photo == 'Aktif') { ?>
                                <div class="camera_wrapper" style="height: 240px; width: 100%; position:relative;">
                                    <video id="video_capture" width="320" height="240" autoplay></video>
                                    <canvas id="canvas_camera" width="320" height="240"></canvas>
                                    <div class="note-capture-photo">
                                        <i class="fa fa-camera" style="font-size: 6rem;margin: auto;"></i>
                                        <p class="text-center">Silahkan Ambil Photo</p>
                                    </div>
                                    <i class="fa fa-check-circle success-indicator" style="display: none;"></i>
                                </div>
                                <?php if (isset($show) && $show == 'Ya') { ?>
                                    <div class="d-flex gap-2 w-100 mt-2">
                                        <button id="start-camera" type="button" class="btn btn-danger w-100">Ambil Foto</button>
                                        <button id="click-photo" type="button" class="btn w-100 btn-success" style="display: none;">Click Photo</button>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if (isset($show) && $show == 'Ya') { ?>
                                <input type="hidden" name="latitude" id="latitude" readonly class="form-control" required>
                                <input type="hidden" name="longitude" id="longitude" readonly class="form-control" required>
                                <input type="hidden" name="codeny" id="codeny" value="<?= $username ?>" readonly class="form-control" required>
                                <input type="hidden" value="<?= (isset($is_photo) && $is_photo == 'Aktif') ? '' : 'a' ?>" name="imageDataURL" id="photo" readonly class="form-control" required>
                                <button id="btn-act-absen" type="submit" class="btn btn-absen btn-primary mt-2 w-100"><?= isset($remark) ? $remark : 'Absen' ?></button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-absen btn-primary mt-2 w-100"><?= isset($remark) ? $remark : 'Absen' ?></button>
                            <?php } ?>
                            <ul>
                                <li>Jam Masuk : <?= isset($masuk) ? $masuk : '' ?> </li>
                                <li>Jam Pulang : <?= isset($pulang) ? $pulang : '' ?> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div id="embed-map" style="height: 325px; width: 100%;"></div>
                        <br>
                        <center><span id="notif-radius"></span></center>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-md-6 mb-5">
            <?php if (isset($status_pengumuman) && $status_pengumuman == 'Aktif') { ?>
                <div class="note note-primary">
                    <div class="note-icon"><img src="<?= base_url('assets/img/umum.png') ?>" width="50" height="50"></div>
                    <div class="note-content">
                        <h4><b> PENGUMUMAN </b></h4>
                        <p><?= isset($text) ? $text : '' ?> </p>
                    </div>
                </div>
                <p>
                    <a href="https://bukabuku.my.id"> <img src="<?= base_url('assets/img/ikan.jpg') ?>" class="img-fluid" /></a>
                </p>
            <?php } ?>
            <div class="note note-primary">
                <div class="note-content">
                    <h3>ABSENSI DIGITAL</h3>
                    <p>Moto <b>"Mewujudkan Peserta Didik Yang Disiplin dan Berprofile Pancasila"</b>.</p>
                    <div class="accordion" id="accordion" role="tablist">
                        <div class="card mb-0">
                            <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0"><a data-toggle="collapse" href="#" aria-expanded="true" aria-controls="Pengumuman" class="">Aturan Absensi Sekolah</a></h5>
                            </div>
                            <div class="collapse show" id="Pengumuman" role="tabpanel" font color="#ff0000" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <ol>
                                        <li>Siswa diwajibkan Untuk melakukan absensi pada mesin absensi saat jam Masuk dan Waktu Pulang Sekolah,</li>
                                        <li>Siswa yang terlambat maupun alpha ( Tanpa Keterangan ) sebanyak 3 Kali dan lebih akan mendapat surat panggilan ,</li>
                                        <li>Bagi siswa yang Izin maupun Sakit Wajib melakukan Izin via Website dan login Menggunakan Akun Masing-masing dengan Wajib mengupload Foto Surat Izin melalui link : absen.sman1tual.sch.id</li>
                                        <li>Bagi Siswa yang Izin maupun sakit yang tidak memiliki handphone untuk izin online, wajib memberikan surat ijin kepada admin atau kepada wali kelas yang akan diteruskan ke admin absensi.</li>
                                        <li>Apabila siswa izin dan tidak menggunakan metode pada poin 3 dan 4 di atas maka automatis system akan menganggap Alpha atau Tidak Hadir Tanpa Keterangan dan akan masuk pada daftar pelanggaran.</li>
                                        <li>Bagi siswa yang kartu absensi nya hilang dapat mencetak mandiri dengan akun masing-masing melalui link : absen.sman1tual.sch.id.</li>
                                        <li>Lupa Absensi dan Lupa Tidak Izin apa bila tidak masuk menjadi Tanggung Jawab Siswa, dan akan dianggap pelanggaran oleh system</li>
                                        <li>Dilarang titip scan kartu atau titip absensi dengan alasan apapun</li>
                                        <li>Batas Terlambat Absensi Masuk Siswa jam 07.30 WIT</li>
                                        <li>Siswa tidak dapat absensi pulang sebelum Bell Pulang Sekolah.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-0">
                            <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="CekHasil" class="collapsed">Link Sekolah </a></h5>
                            </div>
                            <div class="collapse show" id="Pengumuman" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <ol>
                                        <li><a href="https://sman1tual.sch.id">www.sman1tual.sch.id</a></li>
                                        <li><a href="https://youtu.be/ex59s-AlXc0">Tutorial Ijin dan Melihat Riwayat Absensi </a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/camera-capture.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script>
    const getLocationMap = L.map('embed-map');
    const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const osmAttrib = 'Leaflet © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    const osm = new L.TileLayer(osmUrl, {
        minZoom: 8,
        maxZoom: 50,
        attribution: osmAttrib
    });

    let myCircle;
    let insidekah = false;
    let outsidekah = false;

    getLocationMap.scrollWheelZoom.disable()
    getLocationMap.setView(new L.LatLng('-6.175392', '106.827153'), 14)
    getLocationMap.addLayer(osm)
    let getLocationMapMarker = L.marker(['-6.175392', '106.827153']).addTo(getLocationMap);

    function getToLoc(lat, lng) {
        const zoom = 17;
        getLocationMap.setView(new L.LatLng(lat, lng), zoom);

        if (getLocationMapMarker) {
            getLocationMap.removeLayer(getLocationMapMarker)
        }
        getLocationMapMarker = L.marker([lat, lng]).addTo(getLocationMap);
        getLocationMapMarker.setLatLng([lat, lng])

        $('#latitude').val(lat)
        $('#longitude').val(lng)

        var d = getLocationMapMarker.getLatLng().distanceTo(myCircle.getLatLng());
        var isInside = d < myCircle.getRadius();

        if (isInside) {
            outsidekah = false
            if (insidekah == false) {
                insidekah = true
                $('#notif-radius').html("* Anda berada pada radius absen geolocation")
                $(':input[type="submit"]').prop('disabled', false);
                $('#start-camera').prop('disabled', false);
                $('#is_radius').val('Y');
            }
        } else {
            insidekah = false
            if (outsidekah == false) {
                outsidekah = true
                $('#notif-radius').html("* Anda berada diluar radius absen geolocation")
                $(':input[type="submit"]').prop('disabled', true);
                $('#start-camera').prop('disabled', true);
                $('#is_radius').val('N');
            }
        }
    }

    function createRadius(lat, lng, radius) {
        myCircle = L.circle([lat, lng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(getLocationMap);
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                getToLoc(position.coords.latitude, position.coords.longitude)
                $('.alertnya').html('')
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Perhatian',
                text: 'Geolocation tidak didukung browser ini.',
                customClass: {
                    popup: 'swal2-popup'
                }
            });
            $('.alertnya').html(`
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Perhatian</strong> Izin lokasi harus diberikan untuk mengetahui lokasi anda.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			`)
        }
    }

    function addLapanganRadius() {
        $.ajax({
            url: '<?= base_url('dashboard_user/getLapanganRadius') ?>',
            type: 'POST',
            data: {},
            success: function(res) {
                res = JSON.parse(res)
                createRadius(res.lat, res.lng, res.radius_diizinkan)
            }
        })
    }

    $(document).ready(function() {
        $(':input[type="submit"]').prop('disabled', true);
        $('#start-camera').prop('disabled', true);
        addLapanganRadius()
        setInterval(() => {
            getCurrentLocation()
        }, 100);
    })
</script>

<script>
    $('#btn-act-absen').click(function() {
        var latitude = $('#latitude').val()
        var longitude = $('#longitude').val()
        var photo = $('#photo').val()
        var kdnya = $('#codeny').val()
        var is_radius = $('#is_radius').val()

        if (latitude == null || latitude == '') {
            Swal.fire({
                icon: 'info',
                title: 'Tunggu sebentar',
                text: 'Latitude kosong',
                customClass: {
                    popup: 'swal2-popup'
                }
            })
        } else if (longitude == null || longitude == '') {
            Swal.fire({
                icon: 'info',
                title: 'Tunggu sebentar',
                text: 'Longitude kosong',
                customClass: {
                    popup: 'swal2-popup'
                }
            })
        } else if (is_radius == '' || is_radius == 'N') {
            Swal.fire({
                icon: 'info',
                title: 'Luar Radius',
                text: 'Silahkan mendekat ke zona absen',
                customClass: {
                    popup: 'swal2-popup'
                }
            })
        }

        if (typeof photo === "undefined") {
            do_absen(kdnya, photo)
        } else {
            if (photo == null || photo == '') {
                Swal.fire({
                    icon: 'info',
                    title: 'Photo Kosong',
                    text: 'Silahkan ambil photo dahulu',
                    customClass: {
                        popup: 'swal2-popup'
                    }
                })
            } else {
                do_absen(kdnya, photo)
            }
        }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    var baseURL = '<?= base_url() ?>/';

    function do_absen(codeny, photo) {
        Swal.fire({
            title: 'Loading...',
            html: '<i class="fas fa-spinner fa-spin"></i>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-popup'
            }
        });

        $.ajax({
            url: baseURL + 'Dashboard_user/get_info_absen/' + codeny,
            type: 'POST',
            data: {
                codeny: codeny,
                photo: photo
            },
            success: function(data) {
                var dt = typeof data === 'string' ? JSON.parse(data) : data;
                var telatkah = dt.telatkah;
                if (dt.response == 'ok') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Absensi Berhasil'
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: dt.message,
                        footer: 'Mengalami masalah? hubungi <a href="#">admin</a>',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    })
                }
                let audiony = new Audio();
                if (telatkah == 'ya') {
                    audiony = new Audio(baseURL + 'assets/audio/audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav');
                } else {
                    audiony = new Audio(baseURL + 'assets/audio/audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav');
                }
                audiony.play();

                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: e.statusText || 'Terjadi kesalahan koneksi',
                    customClass: {
                        popup: 'swal2-popup'
                    }
                });
            }
        });
    }
</script>