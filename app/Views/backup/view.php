<div id="content" class="app-content">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Backup Database</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                      <div class="alert alert-success fade show" role="alert" style="display: none;" id="alert-box">
                      <i class="fa fa-check"></i> <strong>Success!</strong>
                      Berhasil mencadangkan database                      
                    </div>


              <center>
                <a href="<?= site_url() ;?>backup/file" class="btn btn-primary btn-raised btn-lg" onclick="alert()"><i class="fa fa-download"></i> Back Up DataBase</a>
              </center>
                </div>

            </div>

        </div>

<script type="text/javascript">
  function alert() {
    $("#alert-box").css({"display":"block"});
  }
</script>