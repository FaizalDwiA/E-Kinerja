<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active">Konfirmasi Jobdesk</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Konfirmasi Jobdesk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nama Jobdesk</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th>Point</th>
                            <th>Status Validasi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Nama Jobdesk</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th>Point</th>
                            <th>Status Validasi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= $row['nama_jobdesk']; ?></td>
                                <td><?= $row['kategori']; ?></td>
                                <td><?= $row['tanggal']; ?></td>
                                <td><?= $row['mulai']; ?></td>
                                <td><?= $row['selesai']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td><?= $row['lampiran_url']; ?></td>
                                <td><?= $row['point']; ?></td>
                                <td><?= $row['status_validasi']; ?></td>
                                <td><?= $row['keterangan']; ?></td>
                                <td width="15%">
                                    <a href="setuju_jobdesk?jobdesk_id=<?= $row['jobdesk_id']; ?>" class="btn btn-success btn-circle">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <button class="btn btn-warning btn-circle btn-edit" data-jobdesk-id="<?= $row['jobdesk_id']; ?>" data-nama="<?= $row['nama_lengkap']; ?>" data-nama-jobdesk="<?= $row['nama_jobdesk']; ?>" data-kategori="<?= $row['kategori']; ?>" data-tanggal="<?= $row['tanggal']; ?>" data-mulai="<?= $row['mulai']; ?>" data-selesai="<?= $row['selesai']; ?>" data-status="<?= $row['status']; ?>" data-lampiran-url="<?= $row['lampiran_url']; ?>" data-point="<?= $row['point']; ?>" data-status-validasi="<?= $row['status_validasi']; ?>" data-keterangan="<?= $row['keterangan']; ?>" data-toggle="modal" data-target="#editJobdeskModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="hapus_jobdesk?jobdesk_id=<?= $row['jobdesk_id']; ?>" class="btn btn-danger btn-circle">
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

<!-- Modal Edit Jobdesk -->
<div class="modal fade" id="editJobdeskModal" tabindex="-1" aria-labelledby="editJobdeskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="ubah_jobdesk">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJobdeskModalLabel">Edit Jobdesk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_jobdesk_id" name="jobdesk_id">

                    <div class="form-group">
                        <label for="edit_nama" class="col-form-label">Nama:</label>
                        <input type="text" name="nama" class="form-control" id="edit_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_jobdesk" class="col-form-label">Nama Jobdesk:</label>
                        <input type="text" name="nama_jobdesk" class="form-control" id="edit_nama_jobdesk">
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori" class="col-form-label">Kategori:</label>
                        <input type="text" name="kategori" class="form-control" id="edit_kategori">
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal" class="col-form-label">Tanggal:</label>
                        <input type="date" name="tanggal" class="form-control" id="edit_tanggal">
                    </div>
                    <div class="form-group">
                        <label for="edit_mulai" class="col-form-label">Mulai:</label>
                        <input type="time" name="mulai" class="form-control" id="edit_mulai">
                    </div>
                    <div class="form-group">
                        <label for="edit_selesai" class="col-form-label">Selesai:</label>
                        <input type="time" name="selesai" class="form-control" id="edit_selesai">
                    </div>
                    <div class="form-group">
                        <label for="edit_status" class="col-form-label">Status:</label>
                        <select class="custom-select" id="edit_status" name="status">
                            <option value="selesai">Selesai</option>
                            <option value="belum selesai">Belum Selesai</option>
                            <option value="ditunda">Ditunda</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_lampiran_url" class="col-form-label">Lampiran URL:</label>
                        <input type="text" name="lampiran_url" class="form-control" id="edit_lampiran_url">
                    </div>
                    <div class="form-group">
                        <label for="edit_poin" class="col-form-label">Point:</label>
                        <input type="number" name="point" class="form-control" id="edit_poin">
                    </div>
                    <div class="form-group">
                        <label for="edit_status_validasi" class="col-form-label">Status Validasi:</label>
                        <select class="custom-select" id="edit_status_validasi" name="status_validasi">
                            <option value="disetujui">Disetujui</option>
                            <option value="belum">Belum</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan" class="col-form-label">Keterangan:</label>
                        <input type="text" name="keterangan" class="form-control" id="edit_keterangan">
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
                const jobdeskId = this.getAttribute('data-jobdesk-id');
                const nama = this.getAttribute('data-nama');
                const namaJobdesk = this.getAttribute('data-nama-jobdesk');
                const kategori = this.getAttribute('data-kategori');
                const tanggal = this.getAttribute('data-tanggal');
                const mulai = this.getAttribute('data-mulai');
                const selesai = this.getAttribute('data-selesai');
                const lampiranUrl = this.getAttribute('data-lampiran-url');
                const point = this.getAttribute('data-point');
                const keterangan = this.getAttribute('data-keterangan');
                const status = this.getAttribute('data-status');

                document.getElementById('edit_jobdesk_id').value = jobdeskId;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_nama_jobdesk').value = namaJobdesk;
                document.getElementById('edit_kategori').value = kategori;
                document.getElementById('edit_tanggal').value = tanggal;
                document.getElementById('edit_mulai').value = mulai;
                document.getElementById('edit_selesai').value = selesai;
                document.getElementById('edit_poin').value = point;
                document.getElementById('edit_lampiran_url').value = lampiranUrl;
                document.getElementById('edit_keterangan').value = keterangan;
                document.getElementById('edit_status').value = status;
            });
        });
    });
</script>