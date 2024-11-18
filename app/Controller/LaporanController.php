<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\LaporanRepository;
use BerkahSoloWeb\EKinerja\Repository\AbsensiRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\SessionService;
use BerkahSoloWeb\EKinerja\Service\AbsensiService;
use BerkahSoloWeb\EKinerja\Service\UserService;
use BerkahSoloWeb\EKinerja\Service\LaporanService;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;
use PhpParser\Node\Stmt\Foreach_;

class LaporanController
{
    private SessionService $sessionService;
    private LaporanService $laporanService;

    public function __construct()
    {
        $connection = Database::getConnection();

        // Laporan
        $laporanRepository = new LaporanRepository($connection);
        $this->laporanService = new LaporanService($laporanRepository);

        // Session
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function karyawan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $karyawan = $this->laporanService->getAllUsers();

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- DataTables -->
            <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
            <style>
                .highlight {
                    border: 2px solid red;
                }
            </style>
        EOD;

        $js = <<<EOD
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
                    function tambahkanKelasBerdasarkanLebarLayar() {
                        var elemen = $('#accordionSidebar');
                        if ($(window).width() <= 600) {
                            elemen.addClass('toggled');
                        } else {
                            elemen.removeClass('toggled');
                        }
                    }

                    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
                    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
                    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
                });
            </script>
        EOD;

        $js .= <<<EOD
                <!-- DataTables  & Plugins -->
                <script src="plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                <script src="plugins/jszip/jszip.min.js"></script>
                <script src="plugins/pdfmake/pdfmake.min.js"></script>
                <script src="plugins/pdfmake/vfs_fonts.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
                
                <!-- Page specific script -->
                <script>
                $(function () {
                    $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                });

                </script>
        EOD;

        $model = [
            "title" => "Laporan Karyawan",
            "sidebar" => [
                "menu" => "laporan",
                "sub" => "laporan_karyawan"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $karyawan
        ];

        View::renderWithLayout('Laporan/karyawan', $model, $css, $js);
    }

    public function absensi()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $absensi = $this->laporanService->getAllAbsensi();

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- DataTables -->
            <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
            <style>
                .highlight {
                    border: 2px solid red;
                }
            </style>
        EOD;

        $js = <<<EOD
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
                    function tambahkanKelasBerdasarkanLebarLayar() {
                        var elemen = $('#accordionSidebar');
                        if ($(window).width() <= 600) {
                            elemen.addClass('toggled');
                        } else {
                            elemen.removeClass('toggled');
                        }
                    }

                    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
                    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
                    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
                });
            </script>
        EOD;

        $js .= <<<EOD
                <!-- DataTables  & Plugins -->
                <script src="plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                <script src="plugins/jszip/jszip.min.js"></script>
                <script src="plugins/pdfmake/pdfmake.min.js"></script>
                <script src="plugins/pdfmake/vfs_fonts.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
                
                <!-- Page specific script -->
                <script>
                $(function () {
                    $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                });

                </script>
        EOD;

        $model = [
            "title" => "Laporan Absensi",
            "sidebar" => [
                "menu" => "laporan",
                "sub" => "laporan_absensi"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $absensi
        ];

        View::renderWithLayout('Laporan/absensi', $model, $css, $js);
    }

    public function jobdesk()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $jobdesk = $this->laporanService->getAllJobdesk();

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- DataTables -->
            <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
            <style>
                .highlight {
                    border: 2px solid red;
                }
            </style>
        EOD;

        $js = <<<EOD
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
                    function tambahkanKelasBerdasarkanLebarLayar() {
                        var elemen = $('#accordionSidebar');
                        if ($(window).width() <= 600) {
                            elemen.addClass('toggled');
                        } else {
                            elemen.removeClass('toggled');
                        }
                    }

                    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
                    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
                    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
                });
            </script>
        EOD;

        $js .= <<<EOD
                <!-- DataTables  & Plugins -->
                <script src="plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                <script src="plugins/jszip/jszip.min.js"></script>
                <script src="plugins/pdfmake/pdfmake.min.js"></script>
                <script src="plugins/pdfmake/vfs_fonts.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
                
                <!-- Page specific script -->
                <script>
                $(function () {
                    $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                });

                </script>
        EOD;

        $model = [
            "title" => "Laporan Jobdesk",
            "sidebar" => [
                "menu" => "laporan",
                "sub" => "laporan_jobdesk"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $jobdesk
        ];

        View::renderWithLayout('Laporan/jobdesk', $model, $css, $js);
    }

    public function perizinan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $perizinan = $this->laporanService->getAllPerizinan();

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- DataTables -->
            <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
            <style>
                .highlight {
                    border: 2px solid red;
                }
            </style>
        EOD;

        $js = <<<EOD
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
                    function tambahkanKelasBerdasarkanLebarLayar() {
                        var elemen = $('#accordionSidebar');
                        if ($(window).width() <= 600) {
                            elemen.addClass('toggled');
                        } else {
                            elemen.removeClass('toggled');
                        }
                    }

                    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
                    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
                    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
                });
            </script>
        EOD;

        $js .= <<<EOD
                <!-- DataTables  & Plugins -->
                <script src="plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                <script src="plugins/jszip/jszip.min.js"></script>
                <script src="plugins/pdfmake/pdfmake.min.js"></script>
                <script src="plugins/pdfmake/vfs_fonts.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
                
                <!-- Page specific script -->
                <script>
                $(function () {
                    $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                });

                </script>
        EOD;

        $model = [
            "title" => "Laporan Perizinan",
            "sidebar" => [
                "menu" => "laporan",
                "sub" => "laporan_perizinan"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $perizinan
        ];

        View::renderWithLayout('Laporan/perizinan', $model, $css, $js);
    }

    public function gaji()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $gaji = $this->laporanService->getAllGaji();

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- DataTables -->
            <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
            <style>
                .highlight {
                    border: 2px solid red;
                }
            </style>
        EOD;

        $js = <<<EOD
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Fungsi untuk menambahkan atau menghapus kelas berdasarkan lebar layar
                    function tambahkanKelasBerdasarkanLebarLayar() {
                        var elemen = $('#accordionSidebar');
                        if ($(window).width() <= 600) {
                            elemen.addClass('toggled');
                        } else {
                            elemen.removeClass('toggled');
                        }
                    }

                    // Panggil fungsi saat halaman dimuat dan saat ukuran layar berubah
                    tambahkanKelasBerdasarkanLebarLayar(); // Panggil sekali saat halaman dimuat
                    $(window).resize(tambahkanKelasBerdasarkanLebarLayar); // Panggil saat ukuran layar berubah
                });
            </script>
        EOD;

        $js .= <<<EOD
                <!-- DataTables  & Plugins -->
                <script src="plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
                <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
                <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
                <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
                <script src="plugins/jszip/jszip.min.js"></script>
                <script src="plugins/pdfmake/pdfmake.min.js"></script>
                <script src="plugins/pdfmake/vfs_fonts.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
                <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
                
                <!-- Page specific script -->
                <script>
                $(function () {
                    $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                });

                </script>
        EOD;

        $model = [
            "title" => "Laporan Gaji",
            "sidebar" => [
                "menu" => "laporan",
                "sub" => "laporan_gaji"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $gaji
        ];

        View::renderWithLayout('Laporan/gaji', $model, $css, $js);
    }
}
