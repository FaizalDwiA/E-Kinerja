<style>
    .profile-user-img {
        border: 3px solid #486edb;
        margin: 0 auto;
        padding: 3px;
        width: 100px
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">PROFIL</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Profil</a></li>
            <li class="breadcrumb-item active">Profil Pribadi</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-circle img-profile rounded-circle mb-3" width="100px" height="100px" src="uploads/profil/<?= $model['user']['foto']; ?>" alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center"><?= $model['user']['nama_lengkap']; ?></h3>

                    <p class="text-muted text-center"><?= $model['user']['jabatan']; ?></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Masuk</b> <a class="float-right"><?= $model['data']['masuk']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Izin</b> <a class="float-right"><?= $model['data']['izin']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Sakit</b> <a class="float-right"><?= $model['data']['sakit']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Jobdesk</b> <a class="float-right"><?= $model['data']['jobdesk']; ?></a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary mt-4">
                <div class="card-header">
                    <h3 class="card-title">About Me</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong><i class="fas fa-calendar-alt"></i> Tanggal Lahir</strong>

                    <p class="text-muted">
                        <?= $model['user']['tgl_lahir']; ?>
                    </p>

                    <hr>

                    <strong><i class="fas fa-book mr-1"></i> Email</strong>

                    <p class="text-muted">
                        <?= $model['user']['email']; ?>
                    </p>

                    <hr>

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>

                    <p class="text-muted"><?= $model['user']['alamat']; ?></p>

                    <hr>

                    <!-- <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                    <p class="text-muted">
                        <span class="tag tag-danger">UI Design</span>
                        <span class="tag tag-success">Coding</span>
                        <span class="tag tag-info">Javascript</span>
                        <span class="tag tag-warning">PHP</span>
                        <span class="tag tag-primary">Node.js</span>
                    </p>

                    <hr> -->

                    <strong><i class="fab fa-whatsapp mr-1"></i> Whatsapp</strong>
                    <!-- <i class="fab fa-whatsapp"></i> -->

                    <p class="text-muted"><?= $model['user']['wa']; ?></p>

                    <hr>

                    <strong><i class="far fa-file-alt mr-1"></i> Catatan</strong>

                    <p class="text-muted"><?= $model['user']['catatan']; ?></p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- /.container-fluid -->