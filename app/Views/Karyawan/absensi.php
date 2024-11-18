<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KARYAWAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
            <li class="breadcrumb-item active" id="jenis-absensi">Absensi</li>
        </ol>
    </div>

    <!-- End of Main Content -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="judul">Absensi</h6>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form id="absensiForm" action="absensi_check_in" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
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
                <input type="hidden" class="form-control" id="user_id" value="<?= $model["user"]["user_id"]; ?>" name="user_id" required>

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required readonly>
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="jenis">Options</label>
                        </div>
                        <select class="custom-select" id="jenis" name="jenis" required>
                            <option value="masuk">Masuk</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jam">Jam</label>
                    <input type="time" class="form-control" id="jam" name="jam" required readonly>
                </div>
                <div class="form-group" id="status-input-group">
                    <label for="status">Status</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="status">Options</label>
                        </div>
                        <select class="custom-select" id="status" name="status">
                            <option value="masuk">Masuk</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="bukti_gambar_input">
                    <label for="bukti_gambar">Bukti Gambar</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bukti Gambar</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="buktiGambar" name="bukti_gambar">
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

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>


<script>
    $(document).ready(function() {
        // Menyembunyikan input status dan bukti gambar saat halaman dimuat
        $("#status-input-group").hide();
        $("#bukti_gambar_input").hide();

        function setJenisBasedOnTime() {
            const now = new Date();
            const hours = now.getHours();
            const jenisSelect = $('#jenis');

            if (hours >= 5 && hours < 12) {
                // Between 5:00 and 12:00
                jenisSelect.val('masuk');
                $("#judul").text("Masuk");
                $('#absensiForm').attr('action', 'absensi_check_in');
            } else if (hours >= 12 && hours <= 20) {
                // Between 12:01 and 20:00
                jenisSelect.val('pulang');
                $("#judul").text("Pulang");
                $('#absensiForm').attr('action', 'absensi_check_out');
            } else {
                // Optionally handle times outside of the 5:00 - 20:00 range
                jenisSelect.val(''); // Or some default value
                $("#judul").text("Absensi");
                $('#absensiForm').attr('action', 'absensi_check_in'); // Default to check_in
            }

            // Trigger change event to update the form elements visibility
            jenisSelect.change();
        }

        // Set the select option and form action based on the current time
        setJenisBasedOnTime();

        // Event listener on dropdown change
        $("#jenis").change(function() {
            if ($(this).val() === "pulang") {
                // Hide status and proof of image inputs if "pulang" is selected
                $("#status-input-group").hide();
                $("#bukti_gambar_input").hide();
                $('#absensiForm').attr('action', 'absensi_check_out');
                $('#buktiGambar').removeAttr('required');
            } else {
                // Show status and proof of image inputs if not "pulang"
                $("#status-input-group").show();
                $("#bukti_gambar_input").show();
                $('#absensiForm').attr('action', 'absensi_check_in');
                $('#buktiGambar').attr('required', 'required');
            }
        });

        // Initialize visibility and form action based on the initial value of "jenis"
        if ($("#jenis").val() === "pulang") {
            $("#status-input-group").hide();
            $("#bukti_gambar_input").hide();
            $('#absensiForm').attr('action', 'absensi_check_out');
            $('#buktiGambar').removeAttr('required');
        } else {
            $("#status-input-group").show();
            $("#bukti_gambar_input").show();
            $('#absensiForm').attr('action', 'absensi_check_in');
            $('#buktiGambar').attr('required', 'required');
        }
    });
</script>