<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">LAPORAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Perizinan</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Perizinan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Izin</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Alasan</th>
                            <th>Bukti Gambar</th>
                            <th>Status Validasi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Izin</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Alasan</th>
                            <th>Bukti Gambar</th>
                            <th>Status Validasi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row->nama_lengkap; ?></td>
                                <td><?= $row->jenis_izin; ?></td>
                                <td><?= date('d-m-Y', strtotime($row->tgl_mulai)); ?></td>
                                <td><?= date('d-m-Y', strtotime($row->tgl_selesai)); ?></td>
                                <td><?= $row->alasan; ?></td>
                                <td>
                                    <?php if ($row->bukti_gambar != "") : ?>
                                        <img src="uploads/bukti/<?= $row->bukti_gambar; ?>" alt="Bukti Gambar" style="max-width: 100px; max-height: 100px; display: block">
                                        <span style="display: none;"><?= $row->bukti_gambar; ?></span>
                                    <?php else : ?>
                                        <p>Tidak Ada Gambar</p>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row->status_validasi; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->