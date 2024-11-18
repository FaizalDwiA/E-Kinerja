<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gaji</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Gaji</a></li>
            <li class="breadcrumb-item active">Gaji Karyawan</li>
        </ol>
    </div>

    <!-- DataTales Example -->
    <div class="card card-primary">
        <div class="card-header py-3">
            <h6 class="font-weight-bold text-primary">Gaji Karyawan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
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
                            <th>Nama Lengkap</th>
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
                            <tr class="<?= ($row['status_pembayaran'] == "dibayar") ? 'bg-success text-white' : ''; ?>">
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['bulan']; ?></td>
                                <td><?= $row['tahun']; ?></td>
                                <td><?= number_format($row['gaji_pokok'], 2, ',', '.'); ?></td>
                                <td><?= number_format($row['tunjangan'], 2, ',', '.'); ?></td>
                                <td><?= number_format($row['pemotongan'], 2, ',', '.'); ?></td>
                                <td><?= $row['status_pembayaran']; ?></td>
                                <td><?= number_format($row['gaji_total'], 2, ',', '.'); ?></td>
                                <td width="100%">
                                    <a href="setuju_gaji?gaji_id=<?= $row['gaji_id']; ?>" class="btn btn-success btn-circle">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                    <button class="btn btn-warning btn-circle btn-edit" data-gaji-id="<?= $row['gaji_id']; ?>" data-nama="<?= $row['nama']; ?>" data-bulan="<?= $row['bulan']; ?>" data-tahun="<?= $row['tahun']; ?>" data-gaji-pokok="<?= $row['gaji_pokok']; ?>" data-tunjangan="<?= $row['tunjangan']; ?>" data-pemotongan="<?= $row['pemotongan']; ?>" data-status-pembayaran="<?= $row['status_pembayaran']; ?>" data-total="<?= $row['gaji_total']; ?>" data-toggle="modal" data-target="#editGajiModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="batal_gaji?gaji_id=<?= $row['gaji_id']; ?>" class="btn btn-danger btn-circle">
                                        <i class="fas fa-window-close"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal Edit Gaji -->
<div class="modal fade" id="editGajiModal" tabindex="-1" aria-labelledby="editGajiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="user" method="post" action="edit_gaji">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGajiModalLabel">Edit Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_gaji_id" name="gaji_id">

                    <div class="form-group">
                        <label for="edit_nama" class="col-form-label">Nama:</label>
                        <input type="text" name="nama" class="form-control" id="edit_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_bulan" class="col-form-label">Bulan:</label>
                        <input type="text" name="bulan" class="form-control" id="edit_bulan" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_tahun" class="col-form-label">Tahun:</label>
                        <input type="text" name="tahun" class="form-control" id="edit_tahun" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_gaji_pokok" class="col-form-label">Gaji Pokok:</label>
                        <input type="text" name="gaji_pokok" class="form-control" id="edit_gaji_pokok">
                    </div>
                    <div class="form-group">
                        <label for="edit_tunjangan" class="col-form-label">Tunjangan:</label>
                        <input type="text" name="tunjangan" class="form-control" id="edit_tunjangan">
                    </div>
                    <div class="form-group">
                        <label for="edit_pemotongan" class="col-form-label">Pemotongan:</label>
                        <input type="text" name="pemotongan" class="form-control" id="edit_pemotongan">
                    </div>
                    <div class="form-group">
                        <label for="edit_status_pembayaran" class="col-form-label">Status Pembayaran:</label>
                        <select class="custom-select" id="edit_status_pembayaran" name="status_pembayaran">
                            <option value="diproses">Diproses</option>
                            <option value="dibayar">Dibayar</option>
                            <option value="disimpan">Disimpan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_total" class="col-form-label">Total:</label>
                        <input type="text" name="total" class="form-control" id="edit_total">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const gajiId = this.getAttribute('data-gaji-id');
                const nama = this.getAttribute('data-nama');
                const bulan = this.getAttribute('data-bulan');
                const tahun = this.getAttribute('data-tahun');
                const gajiPokok = this.getAttribute('data-gaji-pokok');
                const tunjangan = this.getAttribute('data-tunjangan');
                const pemotongan = this.getAttribute('data-pemotongan');
                const statusPembayaran = this.getAttribute('data-status-pembayaran');
                const total = this.getAttribute('data-total');

                document.getElementById('edit_gaji_id').value = gajiId;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_bulan').value = bulan;
                document.getElementById('edit_tahun').value = tahun;
                document.getElementById('edit_gaji_pokok').value = gajiPokok;
                document.getElementById('edit_tunjangan').value = tunjangan;
                document.getElementById('edit_pemotongan').value = pemotongan;
                document.getElementById('edit_status_pembayaran').value = statusPembayaran;
                document.getElementById('edit_total').value = total;
            });
        });
    });
</script>