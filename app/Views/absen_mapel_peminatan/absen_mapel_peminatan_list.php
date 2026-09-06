<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA ABSEN MAPEL PEMINATAN</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data absen_mapel_peminatan </h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="box-body">
                            <div class='row'>
                                <div class='col-md-9'>
                                    <div style="padding-bottom: 10px;">
                                        <?php echo anchor(site_url('absen_mapel_peminatan/create'), '<i class="fas fa-plus-square" aria-hidden="true"></i> Absen Siswa', 'class="btn btn-danger btn-sm tambah_data"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body" style="overflow-x: scroll; ">
                                <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Guru</th>
                                            <th>Mapel</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <?php
                                            if ($this->session->userdata('level_id') != 1) { ?>
                                                <th>Action</th>
                                            <?php } ?>


                                        </tr>
                                    </thead>
                                    <tbody><?php $no = 1;
                                            foreach ($absen_mapel_peminatan_data as $absen_mapel_peminatan) {
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?php echo $absen_mapel_peminatan->nama_guru ?> </td>
                                                <td><?php echo $absen_mapel_peminatan->nama_mapel_peminatan ?> </td>
                                                <td><?php echo $absen_mapel_peminatan->tanggal ?></td>
                                                <td>
                                                    <?= ketAbsenMapelPeminatan($absen_mapel_peminatan->mapel_peminatan_id, $absen_mapel_peminatan->tanggal) ?>
                                                </td>
                                                <?php
                                                if ($this->session->userdata('level_id') != 1) { ?>
                                                    <td>
                                                        <?php
                                                        echo anchor(site_url('absen_mapel_peminatan/delete/' . encrypt_url($absen_mapel_peminatan->mapel_peminatan_id) . '/' . encrypt_url(date('Y-m-d', strtotime($absen_mapel_peminatan->tanggal)))), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                        ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>