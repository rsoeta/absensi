<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="page-header mb-1"><i class="fas fa-user-shield me-2 text-primary"></i> PENGATURAN IDENTITAS & AKUN ADMIN</h1>
            <p class="text-muted mb-0">Kelola identitas pribadi admin, pembaruan password, dan manajemen akun administrator sistem.</p>
        </div>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs nav-tabs-inverse mb-3" role="tablist">
        <li class="nav-item">
            <a href="#tab-profile" class="nav-link active" data-bs-toggle="tab">
                <i class="fas fa-id-card me-1"></i> Identitas & Password Saya
            </a>
        </li>
        <li class="nav-item">
            <a href="#tab-all-admin" class="nav-link" data-bs-toggle="tab">
                <i class="fas fa-users-cog me-1"></i> Daftar Semua Admin (<?= count($user_data) ?>)
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-0">
        <!-- TAB 1: IDENTITAS & PASSWORD ADMIN SAYA -->
        <div class="tab-pane fade show active" id="tab-profile">
            <div class="row">
                <!-- Kolom Kiri: Kartu Identitas Ringkas -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-4">
                            <?php 
                                $foto_path = (!empty($my_user->foto) && file_exists(FCPATH . 'assets/img/user/' . $my_user->foto)) 
                                    ? base_url('assets/img/user/' . $my_user->foto) 
                                    : base_url('assets/img/user/admin.png');
                            ?>
                            <div class="position-relative d-inline-block mb-3">
                                <img id="preview-avatar" 
                                     src="<?= $foto_path ?>" 
                                     alt="Avatar Admin" 
                                     class="rounded-circle border border-3 border-primary shadow-sm" 
                                     style="width: 140px; height: 140px; object-fit: cover;">
                            </div>
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($my_user->nama_lengkap ?? 'Administrator') ?></h4>
                            <p class="text-muted mb-2 font-monospace">@<?= esc($my_user->username) ?></p>
                            <span class="badge bg-primary text-white px-3 py-2" style="font-size: 12px;">
                                <i class="fas fa-shield-alt me-1"></i> Administrator Utama
                            </span>

                            <hr class="my-3">

                            <div class="text-start px-2 small">
                                <div class="mb-2">
                                    <span class="text-muted"><i class="fas fa-envelope me-2 text-primary"></i> Email:</span>
                                    <span class="fw-bold float-end"><?= esc($my_user->email ?: '-') ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted"><i class="fas fa-check-circle me-2 text-success"></i> Status Akun:</span>
                                    <span class="badge bg-success float-end"><?= esc($my_user->is_aktif ?? 'Ya') ?></span>
                                </div>
                                <div>
                                    <span class="text-muted"><i class="fas fa-clock me-2 text-warning"></i> Sesi Aktif:</span>
                                    <span class="fw-bold float-end"><?= date('d M Y H:i') ?> WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Ubah Identitas & Password -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title mb-0 text-dark fw-bold">
                                <i class="fas fa-edit me-2 text-primary"></i> Formulir Perubahan Identitas & Password
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('user/update_profile') ?>" method="post" enctype="multipart/form-data">
                                <h6 class="text-uppercase text-primary fw-bold mb-3" style="font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="fas fa-user-circle me-1"></i> Identitas Admin
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="nama_lengkap" 
                                               class="form-control" 
                                               value="<?= esc($my_user->nama_lengkap ?? '') ?>" 
                                               placeholder="Contoh: Administrator Sekolah" 
                                               required>
                                        <small class="text-muted">Nama ini akan tampil pada salam dan navbar atas.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                            <input type="text" 
                                                   name="username" 
                                                   class="form-control" 
                                                   value="<?= esc($my_user->username ?? '') ?>" 
                                                   placeholder="Username untuk login" 
                                                   required>
                                        </div>
                                        <small class="text-muted">Digunakan saat proses login ke aplikasi.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Alamat Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control" 
                                                   value="<?= esc($my_user->email ?? '') ?>" 
                                                   placeholder="admin@sekolah.sch.id">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Ubah Foto Profil</label>
                                        <input type="file" 
                                               name="foto" 
                                               id="input-foto" 
                                               accept="image/*" 
                                               class="form-control">
                                        <small class="text-muted">Format: JPG, PNG, WEBP (Maksimal 2 MB).</small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="text-uppercase text-danger fw-bold mb-3" style="font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="fas fa-key me-1"></i> Pengaturan Password Baru
                                </h6>
                                <div class="alert alert-light border small text-muted mb-3">
                                    <i class="fas fa-info-circle text-primary me-1"></i> Biarkan kedua kolom password di bawah ini <b>kosong</b> jika Anda tidak ingin mengubah password saat ini.
                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   name="password_baru" 
                                                   id="password_baru" 
                                                   class="form-control" 
                                                   placeholder="Ketik password baru (min. 4 karakter)">
                                            <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="password_baru">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   name="konfirmasi_password" 
                                                   id="konfirmasi_password" 
                                                   class="form-control" 
                                                   placeholder="Ketik ulang password baru">
                                            <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="konfirmasi_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan Profil & Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: DAFTAR SEMUA USER ADMIN -->
        <div class="tab-pane fade" id="tab-all-admin">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-dark fw-bold">
                        <i class="fas fa-users-cog me-2 text-primary"></i> Daftar Akun Administrator
                    </h5>
                    <a href="<?= base_url('user/create') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Tambah Admin Baru
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th style="width: 70px;" class="text-center">Foto</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th class="text-center">Status</th>
                                    <th style="width: 140px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($user_data as $user): 
                                    $is_me = ($user->user_id == session()->get('userid'));
                                    $img_src = (!empty($user->foto) && file_exists(FCPATH . 'assets/img/user/' . $user->foto)) 
                                        ? base_url('assets/img/user/' . $user->foto) 
                                        : base_url('assets/img/user/admin.png');
                                ?>
                                    <tr class="<?= $is_me ? 'table-primary bg-opacity-10' : '' ?>">
                                        <td class="text-center fw-bold"><?= $no++ ?></td>
                                        <td class="text-center">
                                            <img src="<?= $img_src ?>" 
                                                 alt="Foto" 
                                                 class="rounded-circle border" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                <?= esc($user->nama_lengkap ?? $user->username) ?>
                                                <?php if ($is_me): ?>
                                                    <span class="badge bg-primary text-white ms-1" style="font-size: 10px;">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted">Administrator</small>
                                        </td>
                                        <td class="font-monospace text-primary fw-bold">@<?= esc($user->username) ?></td>
                                        <td><?= esc($user->email ?: '-') ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success text-white">Aktif</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('user/update/' . encrypt_url($user->user_id)) ?>" 
                                               class="btn btn-sm btn-outline-primary py-1 px-2" 
                                               title="Edit Admin">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (!$is_me): ?>
                                                <a href="<?= base_url('user/delete/' . encrypt_url($user->user_id)) ?>" 
                                                   class="btn btn-sm btn-outline-danger py-1 px-2" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?')" 
                                                   title="Hapus Admin">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary py-1 px-2 disabled" title="Tidak dapat menghapus akun sendiri" disabled>
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Toggle Show / Hide Password
        $('.toggle-pass').on('click', function() {
            var targetId = $(this).data('target');
            var input = $('#' + targetId);
            var icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Live Preview Upload Foto
        $('#input-foto').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    $('#preview-avatar').attr('src', evt.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<?= $this->endSection() ?>