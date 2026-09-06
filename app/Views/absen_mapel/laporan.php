<div id="content" class="app-content">
    <div class="col-xl-6 ui-sortable">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1" style="" data-init="true">

            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">LAPORAN ABSEN MAPEL</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="panel-body">

                <form action="<?= base_url() ?>absen_mapel/view_laporan" method="post" enctype="multipart/form-data">
                    <thead>
                        <table id="data-table-default" class="table  table-bordered table-hover table-td-valign-middle">
                            <tr>
                                <td width='200'>Set Mapel <br>
                                    <span style="color: red;"> <i>Note : Guru - Mapel - Kelas</i> </span>
                                </td>
                                <td>
                                    <select name="set_mapel_id" id="set_mapel_id" class="form-control theSelect" required>
                                    <option value="">-- Pilih --</option>
                                        <?php $no = 1;
                                        foreach ($absen_mapel_data as $absen_mapel) {
                                        ?>
                                            <option value="<?= $absen_mapel->set_mapel_id ?>"><?= $absen_mapel->nama_guru ?> - <?= $absen_mapel->nama_mapel ?> - <?= $absen_mapel->nama_kelas ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-eye"></i> View Absen</button>
                                </td>
                            </tr>
                    </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".theSelect").select2();
    })
</script>