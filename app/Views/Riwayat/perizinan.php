<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">RIWAYAT</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Riwayat</a></li>
            <li class="breadcrumb-item active">Riwayat Perizinan</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Perizinan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jenis Izin</th>
                            <th>Alasan</th>
                            <th>Bukti</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jenis Izin</th>
                            <th>Alasan</th>
                            <th>Bukti</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= $row['tgl_mulai']; ?></td>
                                <td><?= $row['tgl_selesai']; ?></td>
                                <td><?= $row['jenis_izin']; ?></td>
                                <td><?= $row['alasan']; ?></td>
                                <td>
                                    <img src="uploads/bukti/<?= $row['bukti_gambar']; ?>" alt="Bukti Gambar" style="width:50px; height:50px;">
                                </td>
                                <td><?= $row['status_validasi']; ?></td>
                                <td width="15%">
                                    <?php if ($row['status_validasi'] == "belum") : ?>
                                        <button class="btn btn-warning btn-circle btn-edit" data-perizinan-id="<?= $row['perizinan_id']; ?>" data-nama="<?= $row['nama_lengkap']; ?>" data-tgl-mulai="<?= $row['tgl_mulai']; ?>" data-tgl-selesai="<?= $row['tgl_selesai']; ?>" data-jenis-izin="<?= $row['jenis_izin']; ?>" data-alasan="<?= $row['alasan']; ?>" data-bukti-gambar="<?= $row['bukti_gambar']; ?>" data-status-validasi="<?= $row['status_validasi']; ?>" data-toggle="modal" data-target="#editPerizinanModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="hapus_perizinan_karyawan?perizinan_id=<?= $row['perizinan_id']; ?>" class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else : ?>
                                        <a class="btn btn-success btn-circle">
                                            <i class="fas fa-check"></i>
                                        </a>
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
<!-- /.container-fluid -->


<!-- Modal Edit Perizinan -->
<div class="modal fade" id="editPerizinanModal" tabindex="-1" aria-labelledby="editPerizinanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="ubah_perizinan_karyawan" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPerizinanModalLabel">Edit Perizinan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_perizinan_id" name="perizinan_id">

                    <div class="form-group">
                        <label for="edit_nama" class="col-form-label">Nama:</label>
                        <input type="text" name="nama" class="form-control" id="edit_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_tgl_mulai" class="col-form-label">Tanggal Mulai:</label>
                        <input type="date" name="tgl_mulai" class="form-control" id="edit_tgl_mulai" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_tgl_selesai" class="col-form-label">Tanggal Selesai:</label>
                        <input type="date" name="tgl_selesai" class="form-control" id="edit_tgl_selesai">
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_izin" class="col-form-label">Jenis Izin:</label>
                        <select class="custom-select" id="edit_jenis_izin" name="jenis_izin">
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                            <option value="izin">Izin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_alasan" class="col-form-label">Alasan:</label>
                        <input type="text" name="alasan" class="form-control" id="edit_alasan">
                    </div>
                    <div class="form-group">
                        <label for="edit_bukti_gambar" class="col-form-label">Bukti Gambar:</label>
                        <input type="file" name="bukti_gambar" class="form-control-file" id="edit_bukti_gambar">
                        <img id="preview_bukti_gambar" src="" alt="Preview Bukti Gambar" style="width: 100px; height: 100px; margin-top: 10px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const perizinanId = this.getAttribute('data-perizinan-id');
                const nama = this.getAttribute('data-nama');
                const tglMulai = this.getAttribute('data-tgl-mulai');
                const tglSelesai = this.getAttribute('data-tgl-selesai');
                const jenisIzin = this.getAttribute('data-jenis-izin');
                const alasan = this.getAttribute('data-alasan');
                const buktiGambar = this.getAttribute('data-bukti-gambar');

                document.getElementById('edit_perizinan_id').value = perizinanId;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_tgl_mulai').value = tglMulai;
                document.getElementById('edit_tgl_selesai').value = tglSelesai;
                document.getElementById('edit_jenis_izin').value = jenisIzin;
                document.getElementById('edit_alasan').value = alasan;

                const previewBuktiGambar = document.getElementById('preview_bukti_gambar');
                if (buktiGambar) {
                    previewBuktiGambar.src = 'uploads/bukti/' + buktiGambar;
                } else {
                    previewBuktiGambar.src = '';
                }
            });
        });

        // Handle the file input preview
        const buktiGambarInput = document.getElementById('edit_bukti_gambar');
        buktiGambarInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('preview_bukti_gambar');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            } else {
                preview.src = '';
            }
        });
    });
</script>