<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">LAPORAN</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Jobdesk</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Jobdesk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nama Jobdesk</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Lampiran URL</th>
                            <th>Point</th>
                            <th>Status Validasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Nama Jobdesk</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Lampiran URL</th>
                            <th>Point</th>
                            <th>Status Validasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($model['data'] as $row) : ?>
                            <tr>
                                <td><?= $row->nama_lengkap; ?></td>
                                <td><?= $row->nama_jobdesk; ?></td>
                                <td><?= $row->kategori; ?></td>
                                <td><?= $row->tanggal; ?></td>
                                <td><?= $row->mulai; ?></td>
                                <td><?= $row->selesai; ?></td>
                                <td><?= $row->status; ?></td>
                                <td><?= $row->lampiran_url; ?></td>
                                <td><?= $row->point; ?></td>
                                <td><?= $row->status_validasi; ?></td>
                                <td><?= $row->keterangan; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- </div> -->
</div>
<!-- /.container-fluid -->