<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\RiwayatRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\SessionService;
use BerkahSoloWeb\EKinerja\Service\RiwayatService;
use BerkahSoloWeb\EKinerja\Model\AbsensiRequest;
use BerkahSoloWeb\EKinerja\Model\AbsensiResponse;
use BerkahSoloWeb\EKinerja\Model\JobdeskRequest;
use BerkahSoloWeb\EKinerja\Model\JobdeskResponse;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;
use BerkahSoloWeb\EKinerja\Model\PerizinanRequest;
use BerkahSoloWeb\EKinerja\Model\UserRequest;

class RiwayatController
{
    private SessionService $sessionService;
    private RiwayatService $RiwayatService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $RiwayatRepository = new RiwayatRepository($connection);
        $this->RiwayatService = new RiwayatService($RiwayatRepository);

        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    // Absensi

    public function absensi()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $absensi = $this->RiwayatService->getUnconfirmedAbsensiWithUsernames($user->user_id);

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
            "title" => "Absensi",
            "sidebar" => [
                "menu" => "riwayat",
                "sub" => "riwayat_absensi"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $absensi,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Riwayat/absensi', $model, $css, $js);
    }


    // Jobdesk

    public function jobdesk()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $jobdesk = $this->RiwayatService->getUnconfirmedJobdeskWithUsernames($user->user_id);

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
            "title" => "Jobdesk",
            "sidebar" => [
                "menu" => "riwayat",
                "sub" => "riwayat_jobdesk"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $jobdesk,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Riwayat/jobdesk', $model, $css, $js);
    }

    public function ubahJobdesk()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new JobdeskRequest();
            $request->jobdesk_id = $_POST['jobdesk_id'];
            $request->nama_jobdesk = $_POST['nama_jobdesk'];
            $request->kategori = $_POST['kategori'];
            $request->tanggal = $_POST['tanggal'];
            $request->mulai = $_POST['mulai'];
            $request->selesai = $_POST['selesai'];
            $request->status = $_POST['status'];
            $request->keterangan = $_POST['keterangan'];
            $request->lampiran_url = $_POST['lampiran_url'];

            try {
                $this->RiwayatService->ubahJobdesk($request);
                View::redirect('riwayat_jobdesk');
            } catch (ValidationException $exception) {
                $this->ubahJobdesk($exception->getMessage());
            }
        } else {
            View::renderWithLayout('Jobdesk/ubah_jobdesk', [
                'title' => 'Ubah Jobdesk'
            ]);
        }
    }

    public function hapusJobdesk()
    {
        $jobdesk_id = $_GET['jobdesk_id'] ?? null;
        $this->RiwayatService->deleteJobdesk($jobdesk_id);
        View::redirect('riwayat_jobdesk');
    }





    // Perizinan

    public function perizinan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $perizinan = $this->RiwayatService->getUnconfirmedPerizinanWithUsernames($user->user_id);

        $css = <<<EOD
            <!-- Custom fonts for this template-->
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

            <!-- Custom styles for this template-->
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
            "title" => "Perizinan",
            "sidebar" => [
                "menu" => "riwayat",
                "sub" => "riwayat_perizinan"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $perizinan,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Riwayat/perizinan', $model, $css, $js);
    }

    public function ubahPerizinan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new PerizinanRequest();
            $request->perizinan_id = $_POST['perizinan_id'];
            $request->tgl_mulai = $_POST['tgl_mulai'];
            $request->tgl_selesai = $_POST['tgl_selesai'];
            $request->jenis_izin = $_POST['jenis_izin'];
            $request->alasan = $_POST['alasan'];
            $request->bukti_gambar = $_FILES['bukti_gambar']['name'];

            $oldFileName = $this->RiwayatService->getBuktiGambarByPerizinanId($request->perizinan_id);
            if (!$oldFileName) {
                $oldFileName = '';
            }

            if (isset($_FILES['bukti_gambar']) && $_FILES['bukti_gambar']['error'] === UPLOAD_ERR_OK) {
                $newFileName = $this->RiwayatService->uploadImage($_FILES['bukti_gambar'], 'bukti');
                $request->bukti_gambar = $newFileName;

                if ($oldFileName) {
                    $oldFilePath = 'uploads/bukti/' . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            } else {
                $request->bukti_gambar = $oldFileName;
            }

            try {
                $this->RiwayatService->ubahPerizinan($request);
                View::redirect('riwayat_perizinan');
            } catch (ValidationException $exception) {
                $this->perizinan($exception->getMessage());
            }
        } else {
            View::renderWithLayout('profil/ubah_profil', [
                'title' => 'Ubah Profil'
            ]);
        }
    }

    public function hapusPerizinan()
    {
        $perizinan_id = $_GET['perizinan_id'] ?? null;
        $this->RiwayatService->deletePerizinan($perizinan_id);
        View::redirect('riwayat_perizinan');
    }
}
