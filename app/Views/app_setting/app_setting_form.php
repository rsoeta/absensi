<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div id="content" class="app-content">
    <div class="col-xl-12 ui-sortable">
        <div class="panel panel-inverse">

            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">KELOLA DATA APP_SETTING</h4>
            </div>

            <div class="panel-body">
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered table-hover table-td-valign-middle">
                        <thead>
                            <tr>
                                <td width='200'>Nama Aplikasi</td>
                                <td><input type="text" class="form-control" name="nama_aplikasi" value="<?= $nama_aplikasi; ?>" required /></td>
                            </tr>
                            <tr>
                                <td>Nama Sekolah</td>
                                <td><input type="text" class="form-control" name="nama_sekolah" value="<?= $nama_sekolah; ?>" required /></td>
                            </tr>
                            <tr>
                                <td>Alamat Sekolah</td>
                                <td><textarea class="form-control" rows="3" name="alamat_sekolah" required><?= $alamat_sekolah; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Kepala Sekolah</td>
                                <td><input type="text" class="form-control" name="kepala_sekolah" value="<?= $kepala_sekolah; ?>" required /></td>
                            </tr>
                            <tr>
                                <td>Logo Sekolah</td>
                                <td>
                                    <img src="<?= base_url('assets/img/logo/' . $logo_sekolah); ?>" style="width: 150px;height: 150px;border-radius: 5%; display:block; margin-bottom:10px;">
                                    <input type="hidden" name="logo_sekolah_lama" value="<?= $logo_sekolah ?>">
                                    <p style="color: red; margin-top: 5px;"><small>Note : Pilih Logo Sekolah Jika Ingin Merubahnya</small></p>
                                    <input type="file" class="form-control" name="logo_sekolah" accept=".png,.jpg,.jpeg" />
                                </td>
                            </tr>
                            <tr>
                                <td>Wa Blast (Fonnte)</td>
                                <td>
                                    <select name="wa_blast" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <option value="Aktif" <?= $wa_blast == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Non Aktif" <?= $wa_blast == 'Non Aktif' ? 'selected' : '' ?>>Non Aktif</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Token Fonnte</td>
                                <td>
                                    <input type="text" class="form-control" name="token_fonnte" placeholder="Masukkan Token API Fonnte" value="<?= $token_fonnte; ?>" />
                                    <small class="text-muted">Dapatkan token dari dashboard <a href="https://fonnte.com" target="_blank">fonnte.com</a></small>
                                </td>
                            </tr>
                            <tr>
                                <td>Template Notifikasi WA</td>
                                <td>
                                    <div class="alert alert-info p-2 mb-2">
                                        <strong>Gunakan variabel berikut agar data otomatis berubah sesuai siswa yang absen:</strong><br>
                                        <code>[nama_sekolah]</code>, <code>[tanggal]</code>, <code>[nama_siswa]</code>, <code>[nisn]</code>, <code>[kelas]</code>, <code>[tipe_absen]</code> (Masuk/Pulang), <code>[jam_absen]</code>, <code>[status_absen]</code> (On-Time/Terlambat).
                                    </div>
                                    <textarea class="form-control" rows="12" name="template_notif_wa" placeholder="Masukkan format pesan WhatsApp di sini..."><?= $template_notif_wa; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width='200'>Tema Tampilan Aplikasi</td>
                                <td>
                                    <!-- Kita gunakan operator ?? sebagai fallback jika variabel belum terkirim -->
                                    <?php $tema_aktif = $tema_aplikasi ?? 'default'; ?>
                                    <select name="tema_aplikasi" id="tema_aplikasi" class="form-control">
                                        <option value="default" <?= ($tema_aktif == 'default') ? 'selected' : '' ?>>Dark (Bawaan)</option>
                                        <option value="muhammadiyah" <?= ($tema_aktif == 'muhammadiyah') ? 'selected' : '' ?>>Tema Muhammadiyah (Biru & Kuning)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="hidden" name="id" value="<?= $id; ?>" />
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> <?= $button ?></button>
                                </td>
                            </tr>
                    </table> <!-- Pastikan kode di atas berada SEBELUM tag </table> dan </form> ini -->
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>