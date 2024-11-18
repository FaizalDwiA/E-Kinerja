<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">RIWAYAT</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Riwayat</a></li>
            <li class="breadcrumb-item active">Riwayat Absensi</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Absensi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Terlambat</th>
                            <th>Status Validasi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Terlambat</th>
                            <th>Status Validasi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr class="
                                <?= ($row['status'] == "masuk") ? 'bg-success text-white' : ''; ?>
                                <?= ($row['status'] == "izin") ? 'bg-primary text-white' : ''; ?>
                                <?= ($row['status'] == "sakit") ? 'bg-warning text-white' : ''; ?> <?= ($row['status'] == "alpha") ? 'bg-danger text-white' : ''; ?>
                                <?= ($row['status'] == "alpha") ? 'bg-danger text-white' : ''; ?> <?= ($row['status'] == "alpha") ? 'bg-danger text-white' : ''; ?>
                            ">
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= $row['tanggal']; ?></td>
                                <td><?= $row['check_in']; ?></td>
                                <td><?= $row['check_out']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td>
                                    <img src=" uploads/bukti/<?= $row['bukti_gambar']; ?>" alt="Foto Profil" style="width:50px; height:50px;">
                                </td>
                                <td><?= $row['terlambat']; ?></td>
                                <td><?= $row['status_validasi']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->