<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PROFIL</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Profil</a></li>
            <li class="breadcrumb-item active">Ubah Password</li>
        </ol>
    </div>

    <!-- End of Main Content -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <!-- onsubmit="return validateForm()" -->
        <form action="ubah_password" method="post" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $model['user']['username']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" class="form-control" placeholder="Password Lama" id="password_lama" name="password_lama" required>
                </div>
                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" class="form-control" placeholder="Password Baru" id="password_baru" name="password_baru" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </div>
        </form>
        <!-- /.card-body -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- /.container-fluid -->

<script>
    // Cursor ke username langsung
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("username").focus();
    });
</script>