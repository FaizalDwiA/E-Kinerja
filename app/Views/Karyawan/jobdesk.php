<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active">Jobdesk</li>
        </ol>
    </div>

    <!-- End of Main Content -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Jobdesk</h6>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="jobdesk" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
            <div class="card-body">
                <input type="hidden" class="form-control" id="user_id" placeholder="User ID" value="<?= $model["user"]["user_id"]; ?>" name="user_id" required>

                <div class="form-group">
                    <label for="nama_jobdesk">Nama Jobdesk</label>
                    <input type="text" class="form-control" id="nama_jobdesk" name="nama_jobdesk" required>
                </div>
                <div class="form-group">
                    <label for="label_kategori">Kategori Jobdesk</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="label_kategori">Kategori Jobdesk</label>
                        </div>
                        <select class="custom-select" id="kategori" name="kategori" required>
                            <option selected value="desain-web">Desain Web</option>
                            <option value="website">Website</option>
                            <option value="post-artikel">Post Artikel SEO</option>
                            <option value="share-link">Share Link</option>
                            <option value="list-seo">List SEO</option>
                            <option value="mantenance-website">Mantenance Website</option>
                            <option value="lain">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label for="mulai">Waktu Mulai</label>
                    <input type="time" class="form-control" id="mulai" name="mulai" required>
                </div>
                <div class="form-group">
                    <label for="selesai">Waktu Selesai</label>
                    <input type="time" class="form-control" id="selesai" name="selesai" required>
                </div>
                <div class="form-group">
                    <label for="status">Status Jobdesk</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="status">Status</label>
                        </div>
                        <select class="custom-select" id="status" name="status" required>
                            <option selected value="selesai">Selesai</option>
                            <option value="belum-selesai">Belum Selesai</option>
                            <option value="ditunda">Ditunda</option>
                            <option value="dibatalkan">Dibatalkan</option>
                            <option value="lain">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lampiranUrl">Lampiran URL</label>
                    <input type="text" class="form-control" id="lampiran_url" name="lampiran_url" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="5"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </div>
        </form>
        <!-- /.card-body -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- /.container-fluid -->