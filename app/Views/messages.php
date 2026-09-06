<?php if($this->session->has_userdata('gagal')){ ?>
  <div class="alert alert-warning alert-dismissible fade show mb-2">
    <?= $this->session->flashdata('gagal'); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>


<?php }?>
          