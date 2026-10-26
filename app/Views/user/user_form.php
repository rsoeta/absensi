<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="panel panel-inverse">
            <div class="panel-heading bg-dark text-white">
                <h4 class="panel-title mb-0"><i class="fas fa-user-cog me-2"></i> <?= esc($button) ?></h4>
            </div>
            <div class="panel-body p-4">
                <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= esc($user_id) ?>" />

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap Admin <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama_lengkap" 
                                   class="form-control" 
                                   placeholder="Contoh: Administrator Sekolah" 
                                   value="<?= esc($nama_lengkap) ?>" 
                                   required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="text" 
                                       name="username" 
                                       class="form-control" 
                                       placeholder="Username login" 
                                       value="<?= esc($username) ?>" 
                                       required />
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" 
                                       name="email" 
                                       class="form-control" 
                                       placeholder="admin@sekolah.sch.id" 
                                       value="<?= esc($email) ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <input type="file" 
                                   name="foto" 
                                   accept="image/*" 
                                   class="form-control" />
                            <small class="text-muted">Biarkan kosong jika tidak mengubah foto.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Password <?= empty($user_id) ? '<span class="text-danger">*</span>' : '' ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" 
                                   name="password" 
                                   id="form-password" 
                                   class="form-control" 
                                   placeholder="<?= empty($user_id) ? 'Ketik password admin' : 'Biarkan kosong jika password tidak diganti' ?>" 
                                   <?= empty($user_id) ? 'required' : '' ?> />
                            <button class="btn btn-outline-secondary toggle-pass-form" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php if (!empty($user_id)): ?>
                            <small class="text-danger">*Biarkan kosong jika password tidak ingin diganti.</small>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url('user') ?>" class="btn btn-secondary px-3">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> <?= esc($button) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.toggle-pass-form').on('click', function() {
            var input = $('#form-password');
            var icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>

<?= $this->endSection() ?>