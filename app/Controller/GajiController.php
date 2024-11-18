<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\GajiRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\SessionService;
use BerkahSoloWeb\EKinerja\Service\GajiService;

class GajiController
{
    private SessionService $sessionService;
    private GajiService $gajiService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $gajiRepository = new GajiRepository($connection);
        $this->gajiService = new GajiService($gajiRepository);

        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    // Pimpinan

    public function gaji()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $month = date('Y-m'); // Mendapatkan bulan dan tahun saat ini

        $gaji = $this->gajiService->getAllGajiKaryawan();

        $this->gajiService->generateMonthlySalaries($month); // Menghasilkan gaji bulanan

        foreach ($gaji as $index => $row) {
            $nama = $this->gajiService->getUserById($row['user_id']);
            $gaji[$index]['nama'] = $nama; // Asumsi $user mengandung nama atau objek dengan nama
        }

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- Custom styles for this page -->
            <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/datatables-demo.js"></script>
        EOD;

        $model = [
            "title" => "Gaji",
            "sidebar" => [
                "menu" => "gaji",
                "sub" => "gaji_pimpinan"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $gaji,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Gaji/gaji', $model, $css, $js);
    }

    public function hapusGaji()
    {
        $gaji_id = $_GET['gaji_id'] ?? null;
        if ($gaji_id) {
            $this->gajiService->deleteGaji($gaji_id);
        }
        $this->gaji();
    }

    public function batalGaji()
    {
        $gaji_id = $_GET['gaji_id'] ?? null;
        if ($gaji_id) {
            $this->gajiService->setBatalGaji($gaji_id);
        }
        $this->gaji();
    }

    public function setujuGaji()
    {
        $gaji_id = $_GET['gaji_id'] ?? null;
        if ($gaji_id) {
            $this->gajiService->setujuGaji($gaji_id);
        }
        $this->gaji();
    }

    public function gajiRiwayat()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $gaji = $this->gajiService->getAllGajiRiwayat();

        foreach ($gaji as $index => $row) {
            $nama = $this->gajiService->getUserById($row['user_id']);
            $gaji[$index]['nama'] = $nama; // Asumsi $user mengandung nama atau objek dengan nama
        }

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- Custom styles for this page -->
            <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/datatables-demo.js"></script>
        EOD;

        $model = [
            "title" => "Riwayat Gaji",
            "sidebar" => [
                "menu" => "gaji",
                "sub" => "gaji_riwayat"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $gaji,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Gaji/gaji_riwayat', $model, $css, $js);
    }




    // Gaji Karyawan

    public function gajiKaryawan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $gaji = $this->gajiService->getGajiByUserDibayar($user->user_id);

        $gaji = $gaji ?? "";

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- Custom styles for this page -->
            <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/datatables-demo.js"></script>
        EOD;

        $model = [
            "title" => "Gaji Karyawan",
            "sidebar" => [
                "menu" => "gaji",
                "sub" => "gaji_karyawan"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $gaji,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Gaji/gaji_karyawan', $model, $css, $js);
    }

    public function gajiUser()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $gaji = $this->gajiService->getGajiRiwayatByUserId($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
            <!-- Custom styles for this page -->
            <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/datatables-demo.js"></script>
        EOD;

        $model = [
            "title" => "Riwayat Gaji",
            "sidebar" => [
                "menu" => "gaji",
                "sub" => "gaji_user"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $gaji,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Gaji/gaji_user', $model, $css, $js);
    }

    public function simpanGaji()
    {
        $gaji_id = $_GET['gaji_id'] ?? null;
        if ($gaji_id) {
            $gaji = $this->gajiService->getGajiById($gaji_id);

            $this->gajiService->setRestartGaji($gaji);
            $this->gajiService->setRestartPoints($gaji);

            $this->gajiService->setSimpanGaji($gaji);
        }
        $this->gaji();
    }

    public function tolakGaji()
    {
        $gaji_id = $_GET['gaji_id'] ?? null;
        if ($gaji_id) {
            $this->gajiService->getTolakGaji($gaji_id);
        }
        $this->gajiKaryawan();
    }

    public function slipGaji()
    {
        $history_id = $_GET['history_id'] ?? null;

        $gaji = $this->gajiService->getRiwayatId($history_id);

        $user = $this->gajiService->getUserByHistoryId($gaji['user_id']);

        $model = [
            "data" => $gaji,
            "user" => $user
        ];

        View::renderSingleFile('Gaji/slip_gaji', $model);
    }
}
