<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    /* ATURAN MOBILE-FIRST & TOUCH TARGETS (MIN 48x48PX) */
    @media (max-width: 767.98px) {

        .form-control,
        .form-select,
        .btn {
            min-height: 48px;
            font-size: 16px !important;
            /* Mencegah auto-zoom pada iOS/Android */
        }

        .panel-body {
            padding: 1rem !important;
        }

        #map {
            height: 350px !important;
            /* Tinggi peta disesuaikan untuk layar HP */
        }
    }
</style>

<div id="content" class="app-content">
    <h1 class="page-header mb-3">PENGATURAN GEOLOCATION</h1>

    <div class="row">
        <!-- Form Pengaturan -->
        <div class="col-xl-6 mb-3">
            <div class="panel panel-inverse shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <div class="panel-heading" style="background: #123e87; color: white;">
                    <h4 class="panel-title"><i class="fa fa-map-marked-alt me-2"></i> Konfigurasi Titik Pusat & Radius</h4>
                </div>
                <div class="panel-body">
                    <form action="<?= $action ?>" method="post">
                        <input type="hidden" name="id" value="<?= $id ?>" />

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Status Geolocation (Aktif / Tidak)</label>
                            <select name="is_aktif" class="form-control theSelect" required>
                                <option value="Ya" <?= $is_aktif == 'Ya' ? 'selected' : '' ?>>Ya (Aktif)</option>
                                <option value="Tidak" <?= $is_aktif == 'Tidak' ? 'selected' : '' ?>>Tidak (Non Aktif)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Validasi Foto Selfie</label>
                            <select name="is_photo" class="form-control theSelect" required>
                                <option value="Ya" <?= $is_photo == 'Ya' ? 'selected' : '' ?>>Ya (Wajib)</option>
                                <option value="Tidak" <?= $is_photo == 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Latitude (Lintang)</label>
                            <input type="text" class="form-control font-monospace" name="latitude" id="latitude" value="<?= $latitude ?>" required readonly placeholder="Klik titik pada peta di samping" />
                            <small class="text-muted">Klik langsung pada peta untuk memperbarui titik koordinat pusat.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Longitude (Bujur)</label>
                            <input type="text" class="form-control font-monospace" name="longitude" id="longitude" value="<?= $longitude ?>" required readonly placeholder="Klik titik pada peta di samping" />
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Radius Absen (Meter)</label>
                            <input type="number" class="form-control" name="radius" id="radius" value="<?= $radius ?>" required min="1" placeholder="Contoh: 50" />
                            <small class="text-muted">Batas maksimal jarak siswa/guru dari titik pusat instansi.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-3 shadow" style="border-radius: 8px;">
                                <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Peta Interaktif -->
        <div class="col-xl-6 mb-3">
            <div class="panel panel-inverse shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <div class="panel-heading" style="background: #123e87; color: white;">
                    <h4 class="panel-title"><i class="fa fa-globe-asia me-2"></i> Peta Area Instansi (Klik untuk Set Titik)</h4>
                </div>
                <div class="panel-body p-0">
                    <div id="map" style="width: 100%; height: 505px; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function() {
        if (typeof $.fn.select2 === 'function') {
            $(".theSelect").select2({
                width: '100%'
            });
        }

        // Inisialisasi Peta Leaflet
        const getLocationMap = L.map('map');

        // --- UBAH BAGIAN INI MENJADI GOOGLE SATELLITE HYBRID ---
        const googleSatellite = new L.TileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            minZoom: 8,
            maxZoom: 21, // Max zoom diperbesar agar bisa melihat atap gedung lebih jelas
            attribution: '&copy; Google Maps Satellite'
        });

        getLocationMap.scrollWheelZoom.disable();

        // Default awal sebelum data dimuat
        getLocationMap.setView(new L.LatLng(-7.378772, 107.728867), 16);

        // Masukkan layer satelit ke dalam map
        getLocationMap.addLayer(googleSatellite);
        // --------------------------------------------------------

        const getLocationMapMarker = L.marker([0, 0]).addTo(getLocationMap);

        function getToLoc(lat, lng) {
            getLocationMap.setView(new L.LatLng(lat, lng), 17);
            getLocationMapMarker.setLatLng([lat, lng]);
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        }

        function addRadius(radius) {
            var lat = $('#latitude').val();
            var lng = $('#longitude').val();

            getLocationMap.eachLayer(function(layer) {
                if (layer instanceof L.Circle) {
                    getLocationMap.removeLayer(layer);
                }
            });

            if (lat != '' && lng != '' && !isNaN(radius)) {
                L.circle([lat, lng], {
                    color: '#123e87',
                    fillColor: '#ffd500',
                    fillOpacity: 0.35,
                    radius: parseFloat(radius)
                }).addTo(getLocationMap);
            }
        }

        // Jika data sudah ada, arahkan peta ke koordinat database
        <?php if ($button == 'Update' && !empty($latitude) && !empty($longitude)): ?>
            getToLoc(<?= $latitude ?>, <?= $longitude ?>);
            addRadius($('#radius').val());
        <?php endif; ?>

        // Event klik pada peta untuk mengubah titik pusat instansi
        getLocationMap.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            getToLoc(lat.toFixed(6), lng.toFixed(6));
            addRadius($('#radius').val());
        });

        // Update lingkaran radius secara real-time saat angka radius diketik
        $(document).on('input', '#radius', function() {
            addRadius($(this).val());
        });
    });
</script>
<?= $this->endSection() ?>