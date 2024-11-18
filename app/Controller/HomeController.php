<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Repository\HomeRepository;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\HomeService;
use BerkahSoloWeb\EKinerja\Service\SessionService;

class  HomeController
{
    private SessionService $sessionService;
    private HomeService $homeService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $homeRepository = new HomeRepository($connection);
        $this->homeService = new HomeService($homeRepository);
    }

    // Pimpinan
    function index()
    {
        $year = date('Y');
        $month = date('m');

        $user = $this->sessionService->current();

        $totalKaryawan = $this->homeService->getTotalKaryawan();

        $persentaseKehadiran = $this->homeService->getKehadiranBulananSemuaKaryawan($year, $month);

        $persentaseKehadiranTahunan = $this->homeService->getKehadiranTahunanBulananSemuaKaryawan($year);

        $totalGaji = $this->homeService->getTotalGaji($year, $month);

        $totalPerizinan = $this->homeService->getTotalPerizinan($year, $month);

        $jobdesk = $this->homeService->getKategoriByUserAll($year, $month);

        // Mengolah data jobdesk
        $jobdeskData = [
            'desain-web' => 0,
            'website' => 0,
            'post-artikel' => 0,
            'share-link' => 0,
            'list-seo' => 0,
            'maintenance-website' => 0
        ];

        foreach ($jobdesk as $kategoriData) {
            $kategori = $kategoriData['kategori'];
            if (isset($jobdeskData[$kategori])) {
                $jobdeskData[$kategori] = (int) $kategoriData['jumlah']; // Pastikan jumlah adalah integer
            }
        }

        // Hitung total jumlah jobdesk
        $totalJumlahJobdesk = array_sum($jobdeskData); // Total harus integer

        $model = [
            "title" => "Dashboard",
            "sidebar" => [
                "menu" => "dashboard"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "role" => $user->role
            ],
            "data" => [
                "total_karyawan" => $totalKaryawan,
                "persentase_kehadiran" => $persentaseKehadiran,
                "persentase_kehadiran_tahunan" => $persentaseKehadiranTahunan,
                "jobdesk" => $jobdesk,
                "totalJumlahJobdesk" => $totalJumlahJobdesk,
                "total_gaji" => $totalGaji,
                "total_perizinan" => $totalPerizinan
            ]
        ];

        $css = <<<EOD
            <!-- Custom fonts for this template-->
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

            <!-- Custom styles for this template-->
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
        EOD;

        $js = <<<EOD
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

        View::renderWithLayout('Dashboard/index', $model, $css, $js);
    }

    // Karyawan
    function karyawan()
    {
        $year = date('Y');
        $month = date('m');

        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $points = $this->homeService->getPoints($user->user_id);

        $persentaseKehadiran = $this->homeService->getKehadiranBulanan($user->user_id, $year, $month);

        $statusAbsensi = $this->homeService->getStatusAbsensiByUser($user->user_id);

        $perizinan = $this->homeService->getPerizinanByUser($user->user_id, $year, $month);

        $gajiPerBulan = $this->homeService->getGajiPerBulan($user->user_id, $year);

        $gaji = $this->homeService->getGajiByUser($user->user_id, $year, $month);

        $jobdesk = $this->homeService->getKategoriByUser($user->user_id, $year, $month);

        // Menghitung jumlah status
        $statusCount = [
            'masuk' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0 // Anggap jika ada status 'alpha' juga
        ];

        foreach ($statusAbsensi as $entry) {
            $status = $entry['status'];
            if (array_key_exists($status, $statusCount)) {
                $statusCount[$status]++;
            } else {
                $statusCount['alpha']++;
            }
        }

        // Mengonversi data ke format JSON
        $statusCount = json_encode($statusCount);
        $gajiPerBulan = json_encode($gajiPerBulan);

        // Mengolah data jobdesk
        $jobdeskData = [
            'desain-web' => 0,
            'website' => 0,
            'post-artikel' => 0,
            'share-link' => 0,
            'list-seo' => 0,
            'maintenance-website' => 0
        ];

        foreach ($jobdesk as $kategoriData) {
            $kategori = $kategoriData['kategori'];
            if (isset($jobdeskData[$kategori])) {
                $jobdeskData[$kategori] = (int) $kategoriData['jumlah']; // Pastikan jumlah adalah integer
            }
        }

        // Hitung total jumlah jobdesk
        $totalJumlahJobdesk = array_sum($jobdeskData); // Total harus integer

        $model = [
            "title" => "Dashboard",
            "sidebar" => [
                "menu" => "dashboard"
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "role" => $user->role
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => [
                "points" => $points,
                "statusAbsensi" => $statusCount,
                "persentaseKehadiran" => $persentaseKehadiran,
                "jobdesk" => $jobdesk,
                "totalJumlahJobdesk" => $totalJumlahJobdesk,
                "perizinan" => $perizinan,
                "gaji_per_bulan" => $gajiPerBulan,
                "gaji" => $gaji
            ]
        ];

        $css = <<<EOD
            <!-- Custom fonts for this template-->
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

            <!-- Custom styles for this template-->
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
        EOD;

        $js = <<<EOD
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

        View::renderWithLayout('Dashboard/dashboard_karyawan', $model, $css, $js);
    }
}
