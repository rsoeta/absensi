<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Photo <span id="cuts"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="photo_siswa" style="max-width:100%; border-radius:10px;" />
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                <a class="btn btn-primary" id="download" href=""><i class="ace-icon fa fa-download"></i> Download</a>
            </div>
        </div>
    </div>
</div>

<div id="content" class="app-content">
    <h1 class="page-header">KELOLA DATA SISWA</h1>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Seluruh Data Siswa</h4>
        </div>
        <div class="panel-body">
            <!-- Parameter diatur ke 'all' karena tidak ada id kelas spesifik -->
            <form action="<?= base_url('siswa/update_kelas/all') ?>" method="POST">
                <div class="control-panel mb-3">
                    <!-- Baris 1: Tombol Aksi Dasar & Pencarian -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="btn-group">
                            <a href="<?= base_url('siswa/create') ?>" class="btn btn-danger btn-sm"><i class="fas fa-plus-square"></i> Tambah Data</a>
                            <a href="<?= base_url('siswa/export_excel?id=all') ?>" class="btn btn-success btn-sm"><i class="far fa-file-excel"></i> Export Excel</a>
                            <a href="<?= base_url('siswa') ?>" class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Kembali</a>
                        </div>

                        <div class="input-group w-auto" style="min-width: 250px;">
                            <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="pencarian_siswa" class="form-control" placeholder="Cari NISN, Nama, atau Kelas...">
                        </div>
                    </div>

                    <!-- Baris 2: Aksi Massal (Bulk Actions) dengan Background Soft -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center p-2 bg-light border rounded gap-2">

                        <!-- Kiri: Form Pindah Kelas -->
                        <div class="input-group w-auto">
                            <select name="kelas_id" class="form-control theSelect" style="min-width: 180px;">
                                <option value="">-- Pindah Kelas --</option>
                                <?php foreach ($kelas as $k) : ?>
                                    <option value="<?= $k->kelas_id ?>"><?= $k->nama_kelas ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="pindah" value="Y" class="btn btn-primary"><i class="fa fa-save"></i> Terapkan</button>
                        </div>

                        <!-- Kanan: Form Hapus, Cetak & Download Massal -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="form-check form-switch mt-1 me-2">
                                <input class="form-check-input" type="checkbox" name="notify" id="notify" checked>
                                <label class="form-check-label text-dark fw-bold" style="font-size: 13px;" for="notify">Notif Hapus?</label>
                            </div>
                            <button type="submit" name="hapus" value="Y" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data siswa terpilih?');"><i class="fa fa-trash"></i> Hapus</button>
                            <button type="submit" name="cetak" value="Y" class="btn btn-white btn-sm" formtarget="_blank"><i class="fa fa-print"></i> Cetak</button>
                            <button type="submit" name="download" value="Y" class="btn btn-success btn-sm" formtarget="_blank"><i class="fa fa-download"></i> Download</button>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table id="data-table-default" class="table table-bordered table-hover text-dark align-middle" style="white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th width="1%">No</th>
                                <th width="1%"><input type='checkbox' id='checkAll'></th>
                                <th>Action</th>
                                <th>Photo</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>L/P</th>
                                <th>Kelas</th>
                                <th>Alamat</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Wali Siswa</th>
                                <th>No HP Wali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($siswa_data as $siswa) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><input type="checkbox" name="update[]" value="<?= $siswa->siswa_id ?>"></td>
                                    <td class="text-center">
                                        <!-- Tombol Cetak -->
                                        <a href="<?= base_url('siswa/cetak/' . encrypt_url($siswa->siswa_id)) ?>" target="_blank" class="btn btn-white btn-sm mb-1" title="Cetak Kartu">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <!-- Tombol Download (Baru) -->
                                        <a href="<?= base_url('siswa/download_kartu/' . encrypt_url($siswa->siswa_id)) ?>" target="_blank" class="btn btn-success btn-sm mb-1" title="Download Kartu">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('siswa/update/' . encrypt_url($siswa->siswa_id)) ?>" class="btn btn-primary btn-sm mb-1" title="Edit Siswa">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $file_foto = empty($siswa->photo) ? 'default.png' : $siswa->photo;
                                        $url_foto = empty($siswa->photo)
                                            ? base_url('assets/img/icon/default.png')
                                            : base_url('assets/img/siswa/' . $siswa->photo);
                                        ?>
                                        <a id="view_gambar" href="#modal-dialog" data-bs-toggle="modal" data-photo="<?= $file_foto ?>" data-imgurl="<?= $url_foto ?>" data-nama_siswa="<?= htmlspecialchars($siswa->nama_siswa, ENT_QUOTES, 'UTF-8') ?>">
                                            <img src="<?= $url_foto ?>" class="rounded h-30px my-n1 mx-n1" style="object-fit:cover; width:30px;" />
                                        </a>
                                    </td>
                                    <td><?= $siswa->nisn ?></td>
                                    <td class="fw-bold"><?= $siswa->nama_siswa ?></td>
                                    <td><?= $siswa->jk_kelamin ?></td>
                                    <td class="text-primary fw-bold"><?= $siswa->nama_kelas ?? 'N/A' ?></td>
                                    <td><?= $siswa->alamat ?></td>
                                    <td><?= $siswa->tempat_lahir ?></td>
                                    <td><?= $siswa->tanggal_lahir ?></td>
                                    <td><?= $siswa->nama_wali_siswa ?></td>
                                    <td><?= $siswa->no_hp_wali_siswa ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#checkAll').change(function() {
            $('input[name="update[]"]').prop('checked', $(this).is(':checked'));
        });

        $('input[name="update[]"]').click(function() {
            var total_checkboxes = $('input[name="update[]"]').length;
            var total_checkboxes_checked = $('input[name="update[]"]:checked').length;
            $('#checkAll').prop('checked', total_checkboxes_checked === total_checkboxes);
        });

        if (typeof $.fn.select2 === 'function') {
            $(".theSelect").select2();
        }

        // Inisiasi DataTables agar bisa di-search dan ada pagination
        // if (typeof $.fn.DataTable === 'function' && !$.fn.DataTable.isDataTable('#data-table-default')) {
        //     $('#data-table-default').DataTable({
        //         responsive: true,
        //         language: {
        //             url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        //         }
        //     });
        // }

        $(document).on('click', '#view_gambar', function() {
            var nama_siswa = $(this).data('nama_siswa');
            var imgurl = $(this).data('imgurl');
            var photo = $(this).data('photo');

            $('#modal-dialog #cuts').text(" - " + nama_siswa);
            $('#modal-dialog #photo_siswa').attr("src", imgurl);
            $('#modal-dialog #download').attr("href", "<?= base_url('siswa/download/') ?>" + photo);
        });

        // Deteksi ketikan pada kotak input
        $("#pencarian_siswa").on("keyup", function() {
            // Ambil teks yang diketik dan ubah ke huruf kecil
            var keyword = $(this).val().toLowerCase();

            // Filter setiap baris <tr> di dalam <tbody>
            $("table tbody tr").filter(function() {
                // Tampilkan baris jika ada teks yang cocok, sembunyikan jika tidak
                $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
            });
        });
    });
</script>
<?= $this->endSection() ?>