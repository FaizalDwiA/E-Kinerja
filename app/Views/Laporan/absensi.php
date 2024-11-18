<style>
    /* CSS untuk mencetak path file */
    @media print {
        .file-path::before {
            content: "File Path: " attr(data-file-path);
            display: block;
            margin-bottom: 5px;
        }
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">LAPORAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Absensi</h6>
        </div>
        <div class="card-body">
            <!-- <div class="table-responsive"> -->
            <table id="example1" class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <!-- <table id="example1" class="table table-bordered table-striped"> -->
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                        <th>Bukti Gambar</th>
                        <th>Status Validasi</th>
                        <th>Terlambat</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                        <th>Bukti Gambar</th>
                        <th>Status Validasi</th>
                        <th>Terlambat</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($model['data'] as $row) : ?>
                        <tr>
                            <td><?= $row->nama_lengkap; ?></td>
                            <td><?= $row->tanggal; ?></td>
                            <td><?= $row->check_in; ?></td>
                            <td><?= $row->check_out; ?></td>
                            <td><?= $row->status; ?></td>
                            <td>
                                <?php if ($row->bukti_gambar != "") : ?>
                                    <img src="uploads/bukti/<?= $row->bukti_gambar; ?>" alt="Bukti Gambar" style="max-width: 100px; max-height: 100px; display: block; object-fit: contain;">
                                    <span style="display: none;"><?= $row->bukti_gambar; ?></span>
                                <?php else : ?>
                                    <p>Tidak Ada Gambar</p>
                                <?php endif; ?>
                            </td>
                            <td><?= $row->status_validasi; ?></td>
                            <td><?= $row->terlambat; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- </div> -->
</div>
<!-- /.container-fluid -->