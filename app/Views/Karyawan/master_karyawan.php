<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active">Master Karyawan</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary d-inline">Master Karyawan</h6>
            <a href="#">
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#masterKaryawan" width="50%">Tambah</button>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Jabatan</th>
                            <th>Foto Profil</th>
                            <th>WA</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Catatan</th>
                            <th>Tanggal Lahir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Jabatan</th>
                            <th>Foto Profil</th>
                            <th>WA</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Catatan</th>
                            <th>Tanggal Lahir</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row->username; ?></td>
                                <td><?= $row->email; ?></td>
                                <td><?= $row->nama_lengkap; ?></td>
                                <td><?= $row->alamat; ?></td>
                                <td><?= $row->jabatan; ?></td>
                                <td>
                                    <img src="uploads/profil/<?= $row->foto_profil; ?>" alt="Foto Profil" style="width:50px; height:50px;">
                                </td>
                                <td><?= $row->wa; ?></td>
                                <td><?= $row->status; ?></td>
                                <td><?= $row->role; ?></td>
                                <td><?= $row->catatan; ?></td>
                                <td><?= $row->tgl_lahir; ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-circle btn-edit" data-toggle="modal" data-target="#editKaryawanModal" data-id="<?= $row->user_id; ?>" data-username="<?= $row->username; ?>" data-email="<?= $row->email; ?>" data-nama="<?= $row->nama_lengkap; ?>" data-alamat="<?= $row->alamat; ?>" data-jabatan="<?= $row->jabatan; ?>" data-wa="<?= $row->wa; ?>" data-status="<?= $row->status; ?>" data-catatan="<?= $row->catatan; ?>" data-foto-profil="<?= $row->foto_profil; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="hapus_karyawan?user_id=<?= $row->user_id; ?>" class="btn btn-danger btn-circle">
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

<!-- Modal Tambah -->
<div class="modal fade" id="masterKaryawan" tabindex="-1" aria-labelledby="masterKaryawanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="master_karyawan">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="masterKaryawanLabel">Karyawan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username" class="col-form-label">Username:</label>
                        <input type="text" name="username" class="form-control" id="username">
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password:</label>
                        <input type="text" name="password" class="form-control" id="password">
                    </div>
                    <div class="form-group">
                        <label for="password2" class="col-form-label">Ulangi Password:</label>
                        <input type="text" class="form-control" id="password2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editKaryawanModal" tabindex="-1" aria-labelledby="editKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="edit_karyawan" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKaryawanModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">

                    <div class="form-group">
                        <label for="edit_username" class="col-form-label">Username:</label>
                        <input type="text" name="username" class="form-control" id="edit_username">
                    </div>
                    <div class="form-group">
                        <label for="edit_email" class="col-form-label">Email:</label>
                        <input type="text" name="email" class="form-control" id="edit_email">
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_lengkap" class="col-form-label">Nama Lengkap:</label>
                        <input type="text" name="nama_lengkap" class="form-control" id="edit_nama_lengkap">
                    </div>
                    <div class="form-group">
                        <label for="edit_alamat" class="col-form-label">Alamat:</label>
                        <input type="text" name="alamat" class="form-control" id="edit_alamat">
                    </div>
                    <div class="form-group">
                        <label for="edit_jabatan" class="col-form-label">Jabatan:</label>
                        <input type="text" name="jabatan" class="form-control" id="edit_jabatan">
                    </div>
                    <div class="form-group">
                        <label for="edit_wa" class="col-form-label">WA:</label>
                        <input type="text" name="wa" class="form-control" id="edit_wa">
                    </div>
                    <!-- <div class="form-group">
                        <label for="edit_status" class="col-form-label">Status:</label>
                        <input type="text" name="status" class="form-control" id="edit_status">
                    </div> -->
                    <div class="form-group">
                        <label for="edit_tgl_lahir" class="col-form-label">Tanggal Lahir:</label>
                        <input type="date" name="tgl_lahir" class="form-control" id="edit_tgl_lahir">
                    </div>
                    <div class="form-group">
                        <label for="edit_catatan" class="col-form-label">Catatan:</label>
                        <input type="text" name="catatan" class="form-control" id="edit_catatan">
                    </div>
                    <div class="form-group">
                        <label for="edit_foto_profil" class="col-form-label">Foto Profil:</label>
                        <input type="file" name="foto_profil" class="form-control-file" id="edit_foto_profil">
                        <img id="preview_foto_profil" src="" alt="Preview Foto Profil" style="width: 100px; height: 100px; margin-top: 10px;">
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
                const userId = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                const email = this.getAttribute('data-email');
                const nama = this.getAttribute('data-nama');
                const alamat = this.getAttribute('data-alamat');
                const jabatan = this.getAttribute('data-jabatan');
                const wa = this.getAttribute('data-wa');
                // const status = this.getAttribute('data-status');
                const catatan = this.getAttribute('data-catatan');
                const fotoProfil = this.getAttribute('data-foto-profil'); // Assuming you add this attribute for existing photo
                const tanggalLahir = this.getAttribute('data-tanggal-lahir');

                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_username').value = username;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_nama_lengkap').value = nama;
                document.getElementById('edit_alamat').value = alamat;
                document.getElementById('edit_jabatan').value = jabatan;
                document.getElementById('edit_wa').value = wa;
                // document.getElementById('edit_status').value = status;
                document.getElementById('edit_catatan').value = catatan;
                document.getElementById('edit_tgl_lahir').value = tanggalLahir;

                const previewFoto = document.getElementById('preview_foto_profil');
                if (fotoProfil) {
                    previewFoto.src = 'uploads/profil/' + fotoProfil;
                } else {
                    previewFoto.src = '';
                }
                console.log(fotoProfil);
            });
        });

        // Handle the file input preview
        const fotoProfilInput = document.getElementById('edit_foto_profil');
        fotoProfilInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('preview_foto_profil');

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