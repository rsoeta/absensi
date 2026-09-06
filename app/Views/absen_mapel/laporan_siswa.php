<div id="content" class="app-content">
    <h1 class="page-header">DATA ABSEN MAPEL</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data absen mapel </h4>
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
                                </div>
                            </div>
                            <div class="box-body" style="overflow-x: scroll; ">
                                <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Guru</th>
                                            <th>Kelas - Mapel</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($absen_mapel_data as $value) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?php echo $value->nama_guru ?></td>
                                                <td><?php echo $value->nama_kelas ?> - <?php echo $value->nama_mapel ?> </td>
                                                <td><?php echo $value->tanggal ?></td>
                                                <?php if ($value->keterangan == 'H') { ?>
                                                    <td>Hadir</td>
                                                <?php } else if ($value->keterangan == 'I') { ?>
                                                    <td>Ijin</td>
                                                <?php } else if ($value->keterangan == 'S') { ?>
                                                    <td>Sakit</td>
                                                <?php } else if ($value->keterangan == 'B') { ?>
                                                    <td>Bolos</td>
                                                <?php } else if ($value->keterangan == 'A') { ?>
                                                    <td>Alpha</td>
                                                <?php } ?>
                                            </tr>

                                        <?php  } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>