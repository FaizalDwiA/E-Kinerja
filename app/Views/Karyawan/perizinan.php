<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active">Pengajuan Perizin</li>
        </ol>
    </div>

    <!-- End of Main Content -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengajuan Perizinan</h6>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="perizinan" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
            <div class="card-body">
                <input type="hidden" class="form-control" id="user_id" placeholder="User ID" value="<?= $model["user"]["user_id"]; ?>" name="user_id" required>

                <div class="form-group">
                    <label for="jenis_izin">Jenis Izin</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="jenis_izin">Jenis</label>
                        </div>
                        <select class="custom-select" id="jenis_izin" name="jenis_izin" required>
                            <option selected value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                            <option value="izin">Izin</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                </div>
                <div class="form-group">
                    <label for="tgl_selesai">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" required>
                </div>
                <div class="form-group">
                    <label for="alasan">Alasan</label>
                    <input type="text" class="form-control" id="alasan" name="alasan" required>
                </div>
                <div class="form-group">
                    <label for="bukti_gambar">Bukti Gambar</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fotoProfil">Bukti Gambar</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="buktiGambar" name="bukti_gambar" required>
                            <label id="labelBuktiGambar" class="custom-file-label" for="buktiGambar">Choose file</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </div>
        </form>
        <!-- /.card-body -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- /.container-fluid -->