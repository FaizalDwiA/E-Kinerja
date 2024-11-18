<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Cek Gagal -->
    <?php if (isset($model['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?= $modal['error']; ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>
    <!-- END of Cek Gagal -->

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PROFIL</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Profil</a></li>
            <li class="breadcrumb-item active">Ubah Profil</li>
        </ol>
    </div>


    <!-- End of Main Content -->
    <div class="card card-primary">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Profil</h6>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <?php if (isset($model['error'])) { ?>
                <div class="form-group">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $model['error'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            <?php } ?>

            <form action="ubah_profil" method="post" enctype="multipart/form-data">
                <div class="form-group text-center">
                    <img id="previewFotoProfil" class="profile-user-img img-circle img-profile rounded-circle mb-3" width="100px" height="100px" src="uploads/profil/<?= $model['user']['foto']; ?>" alt="User profile picture">
                </div>
                <div class="form-group">
                    <label for="foto_profil">Ubah Foto Profil</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Ubah Foto Profil</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto_profil" name="foto_profil">
                            <label id="label_foto_profil" class="custom-file-label" for="foto_profil">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama_lengkap">Nama</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $model['user']['nama_lengkap']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= $model['user']['jabatan']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $model['user']['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $model['user']['alamat']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="wa">WhatsApp</label>
                    <input type="text" class="form-control" id="wa" name="wa" value="<?= $model['user']['wa']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= $model['user']['tgl_lahir']; ?>" required>
                </div>
                <!-- <div class="form-group">
                    <label for="skills">Skills</label>
                    <input type="text" class="form-control" id="skills" name="skills" value="UI Design, Coding, Javascript, PHP, Node.js">
                    You might want to handle skills differently, like using a tag input or a multi-select dropdown
                </div> -->
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea class="form-control" id="catatan" name="catatan"><?= $model['user']['catatan']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </div>
        </form>
        <!-- /.card-body -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- /.container-fluid -->

<script>
    // Cursor ke nama lengkap langsung
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("nama_lengkap").focus();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen input file
        var inputFotoProfil = document.getElementById("foto_profil");

        // Tambahkan event listener ketika nilai input berubah
        inputFotoProfil.addEventListener("change", function(event) {
            // Ambil file yang dipilih oleh pengguna
            var file = event.target.files[0];

            // Buat objek URL sementara untuk membaca gambar
            var reader = new FileReader();

            // Set fungsi callback ketika proses pembacaan selesai
            reader.onload = function() {
                // Ambil elemen gambar pratinjau
                var previewFotoProfil = document.getElementById("previewFotoProfil");

                // Terapkan URL gambar sebagai sumber pratinjau
                previewFotoProfil.src = reader.result;
            }

            // Baca file sebagai URL data
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    });
</script>