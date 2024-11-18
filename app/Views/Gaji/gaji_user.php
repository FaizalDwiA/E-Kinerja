<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">GAJI</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Gaji</a></li>
            <li class="breadcrumb-item active">Gaji User</li>
        </ol>
    </div>

    <!-- Notification for unprocessed salary -->
    <?php if ($model['data'] === "") : ?>
        <div class="alert alert-warning" role="alert">
            Gaji untuk bulan ini belum diproses. Harap menunggu hingga gaji diproses oleh Pimpinan.
        </div>
    <?php else : ?>

        <!-- DataTales Example -->
        <div class="card card-primary">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary">Gaji User</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Pemotongan</th>
                                <th>Status Pembayaran</th>
                                <th>Total</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Pemotongan</th>
                                <th>Status Pembayaran</th>
                                <th>Total</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($model['data'] as $row) : ?>
                                <tr>
                                    <td><?= $row['bulan']; ?></td>
                                    <td><?= $row['tahun']; ?></td>
                                    <td><?= number_format($row['gaji_pokok'], 2, ',', '.'); ?></td>
                                    <td><?= number_format($row['tunjangan'], 2, ',', '.'); ?></td>
                                    <td><?= number_format($row['pemotongan'], 2, ',', '.'); ?></td>
                                    <td><?= $row['status_pembayaran']; ?></td>
                                    <td><?= number_format($row['gaji_total'], 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="slip_gaji?history_id=<?= $row['history_id']; ?>" class="btn btn-success btn-circle" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>
<!-- /.container-fluid -->