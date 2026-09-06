<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA NOTIF_SISWA</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">List Data notif_siswa </h4>
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
                            <div class="box-body" style="overflow-x: scroll; ">
                                <table id="data-table-default" class="table table-bordered table-hover table-td-valign-middle text-white">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Remark</th>

                                            <th>Nama Siswa</th>
                                            <!-- <th>Kelas</th> -->
                                            <th>Deksripsi</th>
                                            <th>Status Baca</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php $no = 1;
                                            foreach ($notif_siswa_data as $notif_siswa) {
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <?php if ($notif_siswa->deksripsi == 'ditambahkan') {
                                                    echo '<td> <button style="width:100%" type="button" class=" btn-success btn-block"><i class="fa fa-plus"></i> Ditambahkan</button>  </td>';
                                                } else {
                                                    echo '<td> <button style="width:100%" type="button" class=" btn-danger btn-block"><i class="fa fa-trash"></i> Dihapus </button> </td>';
                                                } ?>

                                                <td><?php echo $notif_siswa->nama_siswa ?></td>
                                                <!-- <td><?php echo $notif_siswa->kelas ?></td> -->
                                                <td><?php echo $notif_siswa->nama_siswa ?> Berhasil <?php echo $notif_siswa->deksripsi ?></td>
                                                <td><?php echo $notif_siswa->status_baca ?></td>
                                                <td><?php echo $notif_siswa->tanggal ?></td>
                                                <td>
                                                    <?php
                                                    echo anchor(site_url('notif_siswa/delete/' . encrypt_url($notif_siswa->notif_siswa_id)), '<i class="fas fa-trash-alt" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm delete_data" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                    ?>
                                                </td>
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