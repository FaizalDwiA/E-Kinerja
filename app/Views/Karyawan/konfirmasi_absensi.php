<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active">Konfirmasi Absensi</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Konfirmasi Absensi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Terlambat</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Terlambat</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr class="
                                <?= ($row['status'] == "masuk") ? 'bg-success text-white' : ''; ?>
                                <?= ($row['status'] == "izin") ? 'bg-primary text-white' : ''; ?>
                                <?= ($row['status'] == "sakit") ? 'bg-warning text-white' : ''; ?>
                                <?= ($row['status'] == "alpha") ? 'bg-danger text-white' : ''; ?>
                            ">
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= $row['tanggal']; ?></td>
                                <td><?= $row['check_in']; ?></td>
                                <td><?= $row['check_out']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td>
                                    <img src="uploads/bukti/<?= $row['bukti_gambar']; ?>" alt="Foto Profil" style="width:50px; height:50px;">
                                </td>
                                <td><?= $row['terlambat']; ?></td>
                                <td><?= $row['status_validasi']; ?></td>
                                <td width="15%">
                                    <a href="setuju_absensi?absensi_id=<?= $row['absensi_id']; ?>" class="btn btn-success btn-circle">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <button class="btn btn-warning btn-circle btn-edit" data-absensi-id="<?= $row['absensi_id']; ?>" data-nama="<?= $row['nama_lengkap']; ?>" data-tanggal="<?= $row['tanggal']; ?>" data-check-in="<?= $row['check_in']; ?>" data-check-out="<?= $row['check_out']; ?>" data-status="<?= $row['status']; ?>" data-bukti-gambar="<?= $row['bukti_gambar']; ?>" data-terlambat="<?= $row['terlambat']; ?>" data-status-validasi="<?= $row['status_validasi']; ?>" data-toggle="modal" data-target="#editAbsensiModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="hapus_absensi?absensi_id=<?= $row['absensi_id']; ?>" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a>
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


<!-- Modal Edit Absensi -->
<div class="modal fade" id="editAbsensiModal" tabindex="-1" aria-labelledby="editAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="edit_absensi" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAbsensiModalLabel">Edit Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_absensi_id" name="absensi_id">

                    <div class="form-group">
                        <label for="edit_nama" class="col-form-label">Nama:</label>
                        <input type="text" name="nama" class="form-control" id="edit_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal" class="col-form-label">Tanggal:</label>
                        <input type="date" name="tanggal" class="form-control" id="edit_tanggal" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_check_in" class="col-form-label">Masuk:</label>
                        <input type="time" name="check_in" class="form-control" id="edit_check_in">
                    </div>
                    <div class="form-group">
                        <label for="edit_check_out" class="col-form-label">Keluar:</label>
                        <input type="time" name="check_out" class="form-control" id="edit_check_out">
                    </div>
                    <div class="form-group">
                        <label for="edit_status" class="col-form-label">Status:</label>
                        <select class="custom-select" id="edit_status" name="status">
                            <option value="masuk">Masuk</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_bukti_gambar" class="col-form-label">Bukti Gambar:</label>
                        <input type="file" name="bukti_gambar" class="form-control-file" id="edit_bukti_gambar">
                        <img id="preview_bukti_gambar" src="" alt="Preview Bukti Gambar" style="width: 100px; height: 100px; margin-top: 10px;">
                    </div>
                    <div class="form-group">
                        <label for="edit_terlambat" class="col-form-label">Terlambat:</label>
                        <select class="custom-select" id="edit_terlambat" name="terlambat">
                            <option value="ya">Ya</option>
                            <option value="tidak">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_status_validasi" class="col-form-label">Status Validasi:</label>
                        <select class="custom-select" id="edit_status_validasi" name="status_validasi">
                            <option value="disetujui">Disetujui</option>
                            <option value="belum">Belum</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
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
                const absensiId = this.getAttribute('data-absensi-id');
                const nama = this.getAttribute('data-nama');
                const tanggal = this.getAttribute('data-tanggal');
                const checkIn = this.getAttribute('data-check-in');
                const checkOut = this.getAttribute('data-check-out');
                const status = this.getAttribute('data-status');
                const buktiGambar = this.getAttribute('data-bukti-gambar');
                const terlambat = this.getAttribute('data-terlambat');
                const statusValidasi = this.getAttribute('data-status-validasi');

                document.getElementById('edit_absensi_id').value = absensiId;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_tanggal').value = tanggal;
                document.getElementById('edit_check_in').value = checkIn;
                document.getElementById('edit_check_out').value = checkOut;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_terlambat').value = terlambat;
                document.getElementById('edit_status_validasi').value = statusValidasi;

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