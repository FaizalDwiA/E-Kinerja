<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">LAPORAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Gaji</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Gaji</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Pemotongan</th>
                            <th>Gaji Total</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Pemotongan</th>
                            <th>Gaji Total</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row->nama_lengkap; ?></td>
                                <td><?= $row->bulan; ?></td>
                                <td><?= $row->tahun; ?></td>
                                <td><?= number_format($row->gaji_pokok, 2, ',', '.'); ?></td>
                                <td><?= number_format($row->tunjangan, 2, ',', '.'); ?></td>
                                <td><?= number_format($row->pemotongan, 2, ',', '.'); ?></td>
                                <td><?= number_format($row->gaji_total, 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->