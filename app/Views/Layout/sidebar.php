<?php
$status = $model['cek']['absensi'] ?? '';
$statusClass = '';
$modalAttributes = '';

switch ($status) {
    case 'belum':
        $statusClass = 'bg-danger text-white';
        break;
    case 'sudah':
        $statusClass = 'bg-success text-white';
        $modalAttributes = 'data-toggle="modal" data-target="#sudah"';
        break;
    case 'validasi':
        $statusClass = 'bg-primary text-white';
        $modalAttributes = 'data-toggle="modal" data-target="#validasi"';
        break;
    case 'izin':
        $statusClass = 'bg-warning text-white';
        $modalAttributes = 'data-toggle="modal" data-target="#izin"';
        break;
    case 'tutup':
        $statusClass = 'bg-dark text-white';
        $modalAttributes = 'data-toggle="modal" data-target="#tutup"';
        break;
    default:
        // Handle case when status is empty or not one of the expected values
        $statusClass = 'bg-secondary text-white';
        break;
}
?>

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar"> -->
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/ekinerja">
            <!-- <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div> -->
            <div class="sidebar-brand-text">Berkah Solo Web</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <?php if ($model['user']['role'] === 'pimpinan') : ?>
            <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <a class="nav-link" href="/ekinerja">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
        <?php endif; ?>

        <?php if ($model['user']['role'] === 'karyawan') : ?>
            <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <a class="nav-link" href="dashboard_karyawan">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
        <?php endif; ?>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'profil' ? 'active' : '' ?>">
            <!-- <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Components</span>
            </a> -->
            <a class="nav-link <?= ($model['sidebar']['menu'] ?? '') === 'profil' ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseProfil" aria-expanded="true" aria-controls="collapseProfil">
                <i class="far fas fa-user"></i>
                <span>Profil</span>
            </a>

            <div id="collapseProfil" class="collapse <?= ($model['sidebar']['menu'] ?? '') === 'profil' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Profil:</h6>
                    <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'profil_pribadi' ? 'active' : '' ?>" href="profil">Profil Pribadi</a>
                    <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'ubah_profil' ? 'active' : '' ?>" href="ubah_profil">Ubah Profil</a>
                    <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'ubah_password' ? 'active' : '' ?>" href="ubah_password">Ubah Password</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Karyawan -->
        <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'karyawan' ? 'active' : '' ?>">
            <a class="nav-link <?= ($model['sidebar']['menu'] ?? '') === 'karyawan' ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseAbsensi" aria-expanded="true" aria-controls="collapseAbsensi">
                <i class="far fa-calendar-check"></i>
                <span>Karyawan</span>
            </a>
            <div id="collapseAbsensi" class="collapse <?= ($model['sidebar']['menu'] ?? '') === 'karyawan' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Karyawan:</h6>
                    <?php if ($model['user']['role'] === 'pimpinan') : ?>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'master_karyawan' ? 'active' : '' ?>" href="master_karyawan">Master Karyawan</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'konfirmasi_absensi' ? 'active' : '' ?>" href="konfirmasi_absensi">Konfirmasi Absensi</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'konfirmasi_perizinan' ? 'active' : '' ?>" href="konfirmasi_perizinan">Konfirmasi Perizinan</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'konfirmasi_jobdesk' ? 'active' : '' ?>" href="konfirmasi_jobdesk">Konfirmasi Jobdesk</a>
                    <?php endif; ?>

                    <?php if ($model['user']['role'] === 'karyawan') : ?>
                        <a class="collapse-item <?= $statusClass ?>" <?= $modalAttributes ?> href="absensi">Absensi</a>

                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'jobdesk' ? 'active' : '' ?>" href="jobdesk">Jobdesk</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'perizinan' ? 'active' : '' ?>" href="perizinan">Perizinan</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>

        <!-- Nav Item - Riwayat -->
        <?php if ($model['user']['role'] === 'karyawan') : ?>
            <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'riwayat' ? 'active' : '' ?>">
                <a class="nav-link <?= ($model['sidebar']['menu'] ?? '') === 'riwayat' ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseRiwayat" aria-expanded="true" aria-controls="collapseRiwayat">
                    <i class="fas fa-archive"></i>
                    <span>Riwayat</span>
                </a>
                <div id="collapseRiwayat" class="collapse <?= ($model['sidebar']['menu'] ?? '') === 'riwayat' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Riwayat:</h6>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'riwayat_absensi' ? 'active' : '' ?>" href="riwayat_absensi">Absensi</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'riwayat_jobdesk' ? 'active' : '' ?>" href="riwayat_jobdesk">Jobdesk</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'riwayat_perizinan' ? 'active' : '' ?>" href="riwayat_perizinan">Perizinan</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>

        <!-- Nav Item - Gaji -->
        <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'gaji' ? 'active' : '' ?>">
            <a class="nav-link <?= ($model['sidebar']['menu'] ?? '') === 'gaji' ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseGaji" aria-expanded="true" aria-controls="collapseGaji">
                <i class="fas fa-money-bill"></i>
                <span>Gaji</span>
            </a>
            <div id="collapseGaji" class="collapse <?= ($model['sidebar']['menu'] ?? '') === 'gaji' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Gaji:</h6>
                    <?php if ($model['user']['role'] === 'pimpinan') : ?>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'gaji_pimpinan' ? 'active' : '' ?>" href="gaji">Gaji</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'gaji_riwayat' ? 'active' : '' ?>" href="gaji_riwayat">Riwayat Gaji</a>
                    <?php endif; ?>

                    <?php if ($model['user']['role'] === 'karyawan') : ?>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'gaji_karyawan' ? 'active' : '' ?>" href="gaji_karyawan">Gaji</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'gaji_user' ? 'active' : '' ?>" href="gaji_user">Riwayat Gaji</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>

        <!-- Nav Item - Laporan -->
        <?php if ($model['user']['role'] === 'pimpinan') : ?>
            <li class="nav-item <?= ($model['sidebar']['menu'] ?? '') === 'laporan' ? 'active' : '' ?>">
                <a class="nav-link <?= ($model['sidebar']['menu'] ?? '') === 'laporan' ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseLaporan">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapseLaporan" class="collapse <?= ($model['sidebar']['menu'] ?? '') === 'laporan' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Laporan:</h6>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'laporan_karyawan' ? 'active' : '' ?>" href="laporan_karyawan">Karyawan</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'laporan_absensi' ? 'active' : '' ?>" href="laporan_absensi">Absensi</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'laporan_jobdesk' ? 'active' : '' ?>" href="laporan_jobdesk">Jobdesk</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'laporan_perizinan' ? 'active' : '' ?>" href="laporan_perizinan">Perizinan</a>
                        <a class="collapse-item <?= ($model['sidebar']['sub'] ?? '') === 'laporan_gaji' ? 'active' : '' ?>" href="laporan_gaji">Gaji</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> -->

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $model['user']['nama_lengkap']; ?></span>
                            <img class="img-profile rounded-circle" src="uploads/profil/<?= $model['user']['foto']; ?>">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profil">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->


            <!-- Modal -->
            <div class="modal fade" id="sudah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Absensi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Kamu sudah absensi!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="validasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Absensi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Absensi kamu menunggu disetujui!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="izin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Absensi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Kamu sedang izin!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="tutup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Absensi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Absensi Tertutup!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>