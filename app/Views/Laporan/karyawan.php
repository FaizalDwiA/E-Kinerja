<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">LAPORAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Karyawan</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Karyawan</h6>
        </div>
        <div class="card-body">
            <!-- <div class="table-responsive"> -->
            <table id="example1" class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <!-- <table id="example1" class="table table-bordered table-striped"> -->
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Jabatan</th>
                        <th>Whatsapp</th>
                        <th>Status</th>
                        <th>Tanggal Lahir</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Jabatan</th>
                        <th>Whatsapp</th>
                        <th>Status</th>
                        <th>Tanggal Lahir</th>
                        <th>Catatan</th>
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
                            <td><?= $row->wa; ?></td>
                            <td><?= $row->status; ?></td>
                            <td><?= $row->tgl_lahir; ?></td>
                            <td><?= $row->catatan; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- </div> -->
</div>
<!-- /.container-fluid -->