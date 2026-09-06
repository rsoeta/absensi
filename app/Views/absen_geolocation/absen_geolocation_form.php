<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div id="content" class="app-content">
    <div class="row">
        <div class="col-xl-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">KELOLA DATA ABSEN_GEOLOCATION</h4>
                </div>
                <div class="panel-body">
                    <form action="<?= $action ?>" method="post">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>Is Aktif</td>
                                <td><select name="is_aktif" class="form-control theSelect">
                                        <option value="Aktif" <?= $is_aktif == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Non Aktif" <?= $is_aktif == 'Non Aktif' ? 'selected' : '' ?>>Non Aktif</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Is Photo</td>
                                <td><select name="is_photo" class="form-control theSelect">
                                        <option value="Aktif" <?= $is_photo == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Non Aktif" <?= $is_photo == 'Non Aktif' ? 'selected' : '' ?>>Non Aktif</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width='200'>Latitude</td>
                                <td><input type="text" class="form-control" name="latitude" id="latitude" value="<?= $latitude ?>" required /></td>
                            </tr>
                            <tr>
                                <td width='200'>Longitude</td>
                                <td><input type="text" class="form-control" name="longitude" id="longitude" value="<?= $longitude ?>" required /></td>
                            </tr>
                            <tr>
                                <td width='200'>Radius</td>
                                <td><input type="text" class="form-control" name="radius" id="radius" value="<?= $radius ?>" required /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="hidden" name="id" value="<?= $id ?>" />
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Peta</h4>
                </div>
                <div class="panel-body">
                    <div id="map" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.11.0/js/standalone/selectize.js"></script>
<script>
    $(document).ready(function() {
        $(".theSelect").select2();

        const getLocationMap = L.map('map');
        const osm = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            minZoom: 8,
            maxZoom: 50
        });
        getLocationMap.scrollWheelZoom.disable()
        getLocationMap.setView(new L.LatLng('-6.175392', '106.827153'), 14)
        getLocationMap.addLayer(osm)
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
                if (layer instanceof L.Circle) getLocationMap.removeLayer(layer);
            });
            if (lat != '' && lng != '') L.circle([lat, lng], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(getLocationMap);
        }

        <?php if ($button == 'Update') echo "getToLoc($latitude, $longitude); addRadius($('#radius').val());"; ?>

        getLocationMap.on('click', function(e) {
            const {
                lat = 0, lng = 0
            } = e.latlng;
            getToLoc(lat, lng);
            addRadius($('#radius').val());
        });

        $(document).on('keyup', '#radius', function() {
            addRadius($(this).val())
        });
    })
</script>
<?= $this->endSection() ?>