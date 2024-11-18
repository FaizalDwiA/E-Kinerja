<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\Absensi;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\KaryawanRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\SessionService;
use BerkahSoloWeb\EKinerja\Service\KaryawanService;
use BerkahSoloWeb\EKinerja\Model\AbsensiRequest;
use BerkahSoloWeb\EKinerja\Model\AbsensiResponse;
use BerkahSoloWeb\EKinerja\Model\JobdeskRequest;
use BerkahSoloWeb\EKinerja\Model\JobdeskResponse;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;
use BerkahSoloWeb\EKinerja\Model\PerizinanRequest;
use BerkahSoloWeb\EKinerja\Model\UserRequest;

class KaryawanController
{
    private SessionService $sessionService;
    private KaryawanService $karyawanService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $karyawanRepository = new KaryawanRepository($connection);
        $this->karyawanService = new KaryawanService($karyawanRepository);

        // $sessionRepository = new SessionRepository($connection);
        // $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function show($id)
    {
        $absensi = $this->karyawanService->getAbsensiById($id);
        include 'views/absensi/show.php';
    }



    // Master Karyawan

    public function masterKaryawan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $karyawan = $this->karyawanService->getAllKaryawan();

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
            "title" => "Master Karyawan",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "master_karyawan"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "data" => $karyawan,
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Karyawan/master_karyawan', $model, $css, $js);
    }

    public function postMasterKaryawan()
    {
        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new UserRequest();
            $request->username = $_POST['username'];
            $request->password = $_POST['password'];

            try {
                // Lanjutkan dengan pembuatan absensi
                $cek = $this->karyawanService->masterKaryawan($request);
                View::redirect('master_karyawan');
            } catch (ValidationException $exception) {
                View::renderWithLayout('Karyawan/master_karyawan', [
                    'title' => 'Master Karyawan',
                    'error' => $exception->getMessage()
                ]);
            }
        } else {
            // Tampilkan form pembuatan absensi
            View::renderWithLayout('Karyawan/master_karyawan', [
                'title' => 'Master Karyawan'
            ]);
        }
    }

    public function editKaryawan()
    {
        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new UserRequest();
            $request->user_id = $_POST['user_id'];
            $request->username = $_POST['username'];
            $request->email = $_POST['email'];
            $request->nama_lengkap = $_POST['nama_lengkap'];
            $request->alamat = $_POST['alamat'];
            $request->jabatan = $_POST['jabatan'];
            $request->foto_profil = $_FILES['foto_profil']['name']; // Handle file upload
            $request->wa = $_POST['wa'];
            $request->tgl_lahir = $_POST['tgl_lahir'];
            $request->catatan = $_POST['catatan'];

            // // Ambil nama file gambar lama
            // $oldFileName = $user->foto_profil;

            // // Tangani pengunggahan gambar
            // if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
            //     $newFileName = $this->karyawanService->uploadImage($_FILES['foto_profil'], 'profil');
            //     $request->foto_profil = $newFileName;

            //     // Hapus gambar lama jika ada
            //     if ($oldFileName) {
            //         $oldFilePath = 'uploads/profil/' . $oldFileName;
            //         if (file_exists($oldFilePath)) {
            //             unlink($oldFilePath);
            //         }
            //     }
            // } else {
            //     $request->foto_profil = $oldFileName; // Tetap gunakan file lama jika tidak ada file baru diunggah
            // }

            // Ambil nama file gambar lama
            $oldFileName = $this->karyawanService->getFotoProfilByUserId($request->user_id);

            // Tangani pengunggahan gambar
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                $newFileName = $this->karyawanService->uploadImage($_FILES['foto_profil'], 'profil');
                $request->foto_profil = $newFileName;

                // Hapus gambar lama jika ada
                if ($oldFileName) {
                    $oldFilePath = 'uploads/profil/' . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            } else {
                $request->foto_profil = $oldFileName; // Tetap gunakan file lama jika tidak ada file baru diunggah
            }

            try {
                // Lanjutkan dengan perubahan profil
                $this->karyawanService->ubahProfil($request);
                View::redirect('master_karyawan');
            } catch (ValidationException $exception) {
                // View::renderWithLayout('profil/ubah_profil', [
                //     'title' => 'Ubah Profil',
                //     'error' => $exception->getMessage(),
                //     'user' => $request
                // ]);
                $this->masterKaryawan($exception->getMessage());
            }
        } else {
            // Tampilkan form ubah profil
            View::renderWithLayout('profil/ubah_profil', [
                'title' => 'Ubah Profil'
            ]);
        }
    }

    public function hapusKaryawan()
    {
        $user_id = $_GET['user_id'] ?? null;
        $this->karyawanService->delete_karyawan($user_id);
        $this->masterKaryawan();
    }


    // Absensi

    public function absensi()
    {
        date_default_timezone_set('Asia/Jakarta');

        $currentHour = date('H');

        $fieldToCheck = '';
        // Menentukan field yang akan dicek berdasarkan waktu saat ini
        if ($currentHour <= 7 || $currentHour > 19) {
            View::redirect('dashboard_karyawan');
        }

        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">

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
            <script>
            // Ambil input file
            const fileInput = document.getElementById('buktiGambar');

            // Tambahkan event listener untuk mendengarkan perubahan pada input file
            fileInput.addEventListener('change', function() {
                // Ambil file yang dipilih
                const file = fileInput.files[0];

                // Validasi tipe file (hanya gambar)
                const fileType = file.type; // Tipe MIME file
                if (!fileType.startsWith('image/')) {
                    alert('File yang diunggah harus berupa gambar.');
                    return;
                }

                // Ambil nama file yang dipilih
                const fileName = file.name;

                // Ubah teks label untuk menampilkan nama file
                const label = document.getElementById('labelBuktiGambar');
                label.innerText = fileName;
            });
            </script>

            <script>
            // Mendapatkan tanggal dan waktu hari ini
            var now = new Date();
            var dd = String(now.getDate()).padStart(2, '0');
            var mm = String(now.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = now.getFullYear();
            var hh = String(now.getHours()).padStart(2, '0');
            var min = String(now.getMinutes()).padStart(2, '0');

            var today = yyyy + '-' + mm + '-' + dd;
            var timeNow = hh + ':' + min;

            // Set nilai default input tanggal dan waktu menjadi hari ini dan sekarang
            var initialDate = today;
            var initialTime = timeNow;
            document.getElementById("tanggal").value = initialDate;
            document.getElementById("jam").value = initialTime;

            // Fungsi validasi form
            function validateForm() {
                var tanggal = document.getElementById("tanggal").value;
                var jam = document.getElementById("jam").value;

                if (tanggal !== initialDate) {
                    alert("Tanggal tidak boleh diubah!");
                    return false;
                }

                if (jam !== initialTime) {
                    alert("Waktu masuk tidak boleh diubah!");
                    return false;
                }

                return true; // Jika validasi sukses, submit form
            }

            // Fungsi untuk menambahkan highlight jika input diubah
            function addHighlight(event) {
                if (event.target.id === 'tanggal' && event.target.value !== initialDate) {
                    event.target.classList.add('highlight');
                } else if (event.target.id === 'jam' && event.target.value !== initialTime) {
                    event.target.classList.add('highlight');
                } else {
                    event.target.classList.remove('highlight');
                }
            }

            document.getElementById("tanggal").addEventListener('change', addHighlight);
            document.getElementById("jam").addEventListener('change', addHighlight);
            </script>
        EOD;

        // Get the current time
        $now = new \DateTime();
        $currentHour = $now->format('H');
        $currentMinute = $now->format('i');

        // Determine the jenis and form action based on the time
        $jenis = 'masuk';
        $action = 'absensi_check_in';
        $judul = 'Masuk';

        if (($currentHour == 12 && $currentMinute == 0) || ($currentHour > 12 && $currentHour < 17) || ($currentHour == 17 && $currentMinute <= 30)) {
            // Check if user has checked in before allowing check out
            try {
                // Check if user has checked in before allowing check out
                if ($this->karyawanService->getCheckedIn($user->user_id)) {
                    $jenis = 'pulang';
                    $action = 'absensi_check_out';
                    $judul = 'Pulang';
                } else {
                    throw new ValidationException("Anda harus check-in terlebih dahulu sebelum check-out.");
                }
            } catch (ValidationException $exception) {
                $model = [
                    "title" => "Absensi",
                    "sidebar" => [
                        "menu" => "karyawan",
                        "sub" => "absensi"
                    ],
                    "user" => [
                        "nama_lengkap" => $user->nama_lengkap,
                        "foto" => $user->foto_profil,
                        "user_id" => $user->user_id,
                        "role" => $user->role
                    ],
                    "error" => $exception->getMessage()
                ];

                View::renderWithLayout('Karyawan/absensi', $model, $css, $js);
                return;
            }
        } elseif ($currentHour == 7 && $currentMinute >= 45) {
            $jenis = 'masuk';
            $action = 'absensi_check_in';
            $judul = 'Masuk';
        }

        $model = [
            "title" => "Absensi",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "absensi"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "action" => $action,
            "jenis" => $jenis,
            "judul" => $judul
        ];

        View::renderWithLayout('Karyawan/absensi', $model, $css, $js);
    }

    public function PostCheckIn()
    {
        // Ambil user yang sedang login dari session
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new AbsensiRequest();
            $request->user_id = $_POST['user_id'];
            $request->tanggal = $_POST['tanggal'];
            $request->jam = $_POST['jam'];
            $request->status = $_POST['status'];

            // Tangani pengunggahan gambar
            if (isset($_FILES['bukti_gambar'])) {
                $newFileName = $this->karyawanService->uploadImage($_FILES['bukti_gambar'], "bukti");
                $request->bukti_gambar = $newFileName;
            }

            // Status Validasi
            $request->status_validasi = "belum";

            // Tentukan status terlambat
            $checkInTime = new \DateTime($request->jam);
            $lateTime = new \DateTime('08:15:00');
            $request->terlambat = $checkInTime >= $lateTime ? 'ya' : 'no';

            try {
                // Lanjutkan dengan pembuatan absensi
                $this->karyawanService->check_in($request);
                View::redirect('dashboard_karyawan');
            } catch (ValidationException $exception) {
                $this->absensiWithErrorMessage($exception->getMessage(), $user);
            }
        } else {
            // Tampilkan form pembuatan absensi
            $this->absensi();
        }
    }

    public function PostCheckOut()
    {
        // Ambil user yang sedang login dari session
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new AbsensiRequest();
            $request->user_id = $_POST['user_id'];
            $request->tanggal = $_POST['tanggal'];
            $request->jam = $_POST['jam'];
            // $request->status = $_POST['status'];

            // Status Validasi
            // $request->status_validasi = "belum";

            try {
                // Lanjutkan dengan pembuatan absensi
                $this->karyawanService->check_out($request);
                View::redirect('dashboard_karyawan');
            } catch (ValidationException $exception) {
                $this->absensiWithErrorMessage($exception->getMessage(), $user);
            }
        } else {
            // Tampilkan form pembuatan absensi
            $this->absensi();
        }
    }

    private function absensiWithErrorMessage($errorMessage, $user)
    {
        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">

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

        $js = <<<EOD
            <script src="js/scripts.js"></script>
            <script>
                // Ambil input file
                const fileInput = document.getElementById('buktiGambar');
                // Tambahkan event listener untuk mendengarkan perubahan pada input file
                fileInput.addEventListener('change', function() {
                    // Ambil file yang dipilih
                    const file = fileInput.files[0];
                    // Validasi tipe file (hanya gambar)
                    const fileType = file.type; // Tipe MIME file
                    if (!fileType.startsWith('image/')) {
                        alert('File yang diunggah harus berupa gambar.');
                        return;
                    }
                    // Ambil nama file yang dipilih
                    const fileName = file.name;
                    // Ubah teks label untuk menampilkan nama file
                    const label = document.getElementById('labelBuktiGambar');
                    label.innerText = fileName;
                });
            </script>
            <script>
                // Mendapatkan tanggal dan waktu hari ini
                var now = new Date();
                var dd = String(now.getDate()).padStart(2, '0');
                var mm = String(now.getMonth() + 1).padStart(2, '0'); // January is 0!
                var yyyy = now.getFullYear();
                var hh = String(now.getHours()).padStart(2, '0');
                var min = String(now.getMinutes()).padStart(2, '0');
                var today = yyyy + '-' + mm + '-' + dd;
                var timeNow = hh + ':' + min;
                // Set nilai default input tanggal dan waktu menjadi hari ini dan sekarang
                var initialDate = today;
                var initialTime = timeNow;
                document.getElementById("tanggal").value = initialDate;
                document.getElementById("jam").value = initialTime;
                // Fungsi validasi form
                function validateForm() {
                    var tanggal = document.getElementById("tanggal").value;
                    var jam = document.getElementById("jam").value;
                    if (tanggal !== initialDate) {
                        alert("Tanggal tidak boleh diubah!");
                        return false;
                    }
                    if (jam !== initialTime) {
                        alert("Waktu masuk tidak boleh diubah!");
                        return false;
                    }
                    return true; // Jika validasi sukses, submit form
                }
                // Fungsi untuk menambahkan highlight jika input diubah
                function addHighlight(event) {
                    if (event.target.id === 'tanggal' && event.target.value !== initialDate) {
                        event.target.classList.add('highlight');
                    } else if (event.target.id === 'jam' && event.target.value !== initialTime) {
                        event.target.classList.add('highlight');
                    } else {
                        event.target.classList.remove('highlight');
                    }
                }
                document.getElementById("tanggal").addEventListener('change', addHighlight);
                document.getElementById("jam").addEventListener('change', addHighlight);
            </script>
        EOD;

        $now = new \DateTime();
        $currentHour = $now->format('H');
        $currentMinute = $now->format('i');

        $jenis = 'masuk';
        $action = 'absensi_check_in';
        $judul = 'Masuk';

        if (($currentHour == 12 && $currentMinute == 0) || ($currentHour > 12 && $currentHour < 17) || ($currentHour == 17 && $currentMinute <= 30)) {
            if ($this->karyawanService->getCheckedIn($user->user_id)) {
                $jenis = 'pulang';
                $action = 'absensi_check_out';
                $judul = 'Pulang';
            }
        } elseif ($currentHour == 7 && $currentMinute >= 45) {
            $jenis = 'masuk';
            $action = 'absensi_check_in';
            $judul = 'Masuk';
        }

        $model = [
            "title" => "Absensi",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "absensi"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ],
            "action" => $action,
            "jenis" => $jenis,
            "judul" => $judul,
            "error" => $errorMessage
        ];

        View::renderWithLayout('Karyawan/absensi', $model, $css, $js);
    }




    // Perizinan

    public function perizinan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
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
                <script>
                // Ambil input file
                const fileInput = document.getElementById('buktiGambar');

                // Tambahkan event listener untuk mendengarkan perubahan pada input file
                fileInput.addEventListener('change', function() {
                    // Ambil file yang dipilih
                    const file = fileInput.files[0];

                    // Validasi tipe file (hanya gambar)
                    const fileType = file.type; // Tipe MIME file
                    if (!fileType.startsWith('image/')) {
                        alert('File yang diunggah harus berupa gambar.');
                        return;
                    }

                    // Ambil nama file yang dipilih
                    const fileName = file.name;

                    // Ubah teks label untuk menampilkan nama file
                    const label = document.getElementById('labelBuktiGambar');
                    label.innerText = fileName;
                });
            </script>
        EOD;

        $model = [
            "title" => "Pengajuan Izin",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "perizinan"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Karyawan/perizinan', $model, $css, $js);
    }

    public function postPerizinan()
    {
        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new PerizinanRequest();
            $request->user_id = $_POST['user_id'];
            $request->jenis_izin = $_POST['jenis_izin'];
            $request->tgl_mulai = $_POST['tgl_mulai'];
            $request->tgl_selesai = $_POST['tgl_selesai'];
            $request->alasan = $_POST['alasan'];

            // Tangani pengunggahan gambar
            if (isset($_FILES['bukti_gambar'])) {
                $newFileName = $this->karyawanService->uploadImage($_FILES['bukti_gambar'], "bukti");
                $request->bukti_gambar = $newFileName;
            }

            try {
                $this->karyawanService->ajukanPerizinan($request);
                View::redirect('dashboard_karyawan');
            } catch (ValidationException $exception) {
                View::renderWithLayout('absensi/perizinan', [
                    'title' => 'Pengajuan Perizin',
                    'error' => $exception->getMessage()
                ]);
            }
        } else {
            // Tampilkan form pembuatan absensi
            View::renderWithLayout('Karyawan/perizinan', [
                'title' => 'Pengajuan Izin'
            ]);
        }
    }



    // Jobdesk

    public function jobdesk()
    {
        $user = $this->sessionService->current();

        date_default_timezone_set('Asia/Jakarta');

        $currentHour = date('H');

        $fieldToCheck = '';
        // Menentukan field yang akan dicek berdasarkan waktu saat ini
        if ($currentHour <= 7 || $currentHour > 19) {
            View::redirect('dashboard_karyawan');
        }

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
        EOD;

        $css .= <<<EOD
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
                <script>
                // Ambil input file
                const fileInput = document.getElementById('buktiGambar');

                // Tambahkan event listener untuk mendengarkan perubahan pada input file
                fileInput.addEventListener('change', function() {
                    // Ambil file yang dipilih
                    const file = fileInput.files[0];

                    // Validasi tipe file (hanya gambar)
                    const fileType = file.type; // Tipe MIME file
                    if (!fileType.startsWith('image/')) {
                        alert('File yang diunggah harus berupa gambar.');
                        return;
                    }

                    // Ambil nama file yang dipilih
                    const fileName = file.name;

                    // Ubah teks label untuk menampilkan nama file
                    const label = document.getElementById('labelBuktiGambar');
                    label.innerText = fileName;
                });
            </script>

            <script>
                // Mendapatkan tanggal dan waktu hari ini
                var now = new Date();
                var dd = String(now.getDate()).padStart(2, '0');
                var mm = String(now.getMonth() + 1).padStart(2, '0'); // January is 0!
                var yyyy = now.getFullYear();
                var hh = String(now.getHours()).padStart(2, '0');
                var min = String(now.getMinutes()).padStart(2, '0');

                var today = yyyy + '-' + mm + '-' + dd;
                var timeNow = hh + ':' + min;

                // Set nilai default input tanggal dan waktu menjadi hari ini dan sekarang
                var initialDate = today;
                var initialTime = timeNow;
                document.getElementById("tanggal").value = initialDate;
                document.getElementById("jam").value = initialTime;

                // Fungsi validasi form
                function validateForm() {
                    var tanggal = document.getElementById("tanggal").value;
                    var jam = document.getElementById("jam").value;

                    if (tanggal !== initialDate) {
                        alert("Tanggal tidak boleh diubah!");
                        return false;
                    }

                    if (jam !== initialTime) {
                        alert("Waktu masuk tidak boleh diubah!");
                        return false;
                    }

                    return true; // Jika validasi sukses, submit form
                }

                // Fungsi untuk menambahkan highlight jika input diubah
                function addHighlight(event) {
                    if (event.target.id === 'tanggal' && event.target.value !== initialDate) {
                        event.target.classList.add('highlight');
                    } else if (event.target.id === 'jam' && event.target.value !== initialTime) {
                        event.target.classList.add('highlight');
                    } else {
                        event.target.classList.remove('highlight');
                    }
                }

                document.getElementById("tanggal").addEventListener('change', addHighlight);
                document.getElementById("jam").addEventListener('change', addHighlight);
            </script>

            <script>
                // Cursor ke username langsung
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("nama_jobdesk").focus();
                });
            </script>
        EOD;

        $model = [
            "title" => "Jobdesk",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "jobdesk"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Karyawan/jobdesk', $model, $css, $js);
    }

    public function postJobdesk()
    {
        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Jobdesk
            $request = new JobdeskRequest();
            $request->user_id = $_POST['user_id'];
            $request->nama_jobdesk = $_POST['nama_jobdesk'];
            $request->kategori = $_POST['kategori'];
            $request->tanggal = $_POST['tanggal'];
            $request->mulai = $_POST['mulai'];
            $request->selesai = $_POST['selesai'];
            $request->status = $_POST['status'];
            $request->lampiran_url = $_POST['lampiran_url'];
            $request->keterangan = $_POST['keterangan'] ?? null;

            // Tentukan nilai point berdasarkan kategori
            $request->point = $this->karyawanService->getPoinByKategori($request->kategori);

            try {
                // Lanjutkan dengan pembuatan jobdesk
                $this->karyawanService->jobdesk($request);
                View::redirect('dashboard_karyawan');
            } catch (ValidationException $exception) {
                View::renderWithLayout('jobdesk/jobdesk', [
                    'title' => 'Create Jobdesk',
                    'error' => $exception->getMessage()
                ]);
            }
        } else {
            // Tampilkan form pembuatan jobdesk
            View::renderWithLayout('Karyawan/jobdesk', [
                'title' => 'Jobdesk'
            ]);
        }
    }



    // Konfirmasi Absensi

    public function konfirmasiAbsensi()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $absensi = $this->karyawanService->getUnconfirmedAbsensiWithUsernames();

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
            "title" => "Konfirmasi Absensi",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "konfirmasi_absensi"
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

        View::renderWithLayout('Karyawan/konfirmasi_absensi', $model, $css, $js);
    }

    public function editAbsensi()
    {
        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buat request object untuk Masuk
            $request = new AbsensiRequest();
            $request->absensi_id = $_POST['absensi_id'];
            $request->check_in = $_POST['check_in'];
            $request->check_out = $_POST['check_out'];
            $request->status = $_POST['status'];
            $request->terlambat = $_POST['terlambat'];
            $request->status_validasi = $_POST['status_validasi'];
            $request->bukti_gambar = $_FILES['bukti_gambar']['name']; // Handle file upload\

            // Ambil nama file gambar lama
            $oldFileName = $this->karyawanService->getBuktiGambarByAbsensiId($request->absensi_id);

            // Jika $oldFileName null, set nilai default
            if (!$oldFileName) {
                $oldFileName = ''; // atau null, tergantung kebutuhan
            }

            // Tangani pengunggahan gambar
            if (isset($_FILES['bukti_gambar']) && $_FILES['bukti_gambar']['error'] === UPLOAD_ERR_OK) {
                $newFileName = $this->karyawanService->uploadImage($_FILES['bukti_gambar'], 'bukti');
                $request->bukti_gambar = $newFileName;

                // Hapus gambar lama jika ada
                if ($oldFileName) {
                    $oldFilePath = 'uploads/bukti/' . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            } else {
                $request->bukti_gambar = $oldFileName; // Tetap gunakan file lama jika tidak ada file baru diunggah
            }

            try {
                // Lanjutkan dengan perubahan profil
                $this->karyawanService->ubahAbsensi($request);
                View::redirect('konfirmasi_absensi');
            } catch (ValidationException $exception) {
                // View::renderWithLayout('profil/ubah_profil', [
                //     'title' => 'Ubah Profil',
                //     'error' => $exception->getMessage(),
                //     'user' => $request
                // ]);
                $this->masterKaryawan($exception->getMessage());
            }
        } else {
            // Tampilkan form ubah profil
            View::renderWithLayout('profil/ubah_profil', [
                'title' => 'Ubah Profil'
            ]);
        }
    }

    public function hapusAbsensi()
    {
        $absensi_id = $_GET['absensi_id'] ?? null;
        $this->karyawanService->deleteAbsensi($absensi_id);
        $this->konfirmasiAbsensi();
    }

    public function setujuAbsensi()
    {
        $absensi_id = $_GET['absensi_id'] ?? null;
        $this->karyawanService->setujuAbsensi($absensi_id);
        $this->konfirmasiAbsensi();
    }



    // Konfirmasi Perizinan

    public function konfirmasiPerizinan()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);
        $perizinan = $this->karyawanService->getUnconfirmedPerizinanWithUsernames();

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
            "title" => "Konfirmasi Perizinan",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "konfirmasi_perizinan"
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

        View::renderWithLayout('Karyawan/konfirmasi_perizinan', $model, $css, $js);
    }

    public function editPerizinan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new PerizinanRequest();
            $request->perizinan_id = $_POST['perizinan_id'];
            $request->tgl_mulai = $_POST['tgl_mulai'];
            $request->tgl_selesai = $_POST['tgl_selesai'];
            $request->jenis_izin = $_POST['jenis_izin'];
            $request->alasan = $_POST['alasan'];
            $request->status_validasi = $_POST['status_validasi'];
            $request->bukti_gambar = $_FILES['bukti_gambar']['name'];

            $oldFileName = $this->karyawanService->getBuktiGambarByPerizinanId($request->perizinan_id);
            if (!$oldFileName) {
                $oldFileName = '';
            }

            if (isset($_FILES['bukti_gambar']) && $_FILES['bukti_gambar']['error'] === UPLOAD_ERR_OK) {
                $newFileName = $this->karyawanService->uploadImage($_FILES['bukti_gambar'], 'bukti');
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
                $this->karyawanService->ubahPerizinan($request);
                View::redirect('konfirmasi_perizinan');
            } catch (ValidationException $exception) {
                $this->konfirmasiPerizinan($exception->getMessage());
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
        $this->karyawanService->deletePerizinan($perizinan_id);
        $this->konfirmasiPerizinan();
    }

    public function setujuPerizinan()
    {
        $perizinan_id = $_GET['perizinan_id'] ?? null;

        $perizinan = $this->karyawanService->getPerizinanById($perizinan_id);

        // Lanjutkan dengan pembuatan absensi
        // Konversi tanggal ke format DateTime
        $start_date = new \DateTime($perizinan['tgl_mulai']);
        $end_date = new \DateTime($perizinan['tgl_selesai']);

        // Tambahkan satu hari ke tanggal selesai untuk menyertakan tanggal tersebut dalam loop
        $end_date->modify('+1 day');


        // Buat loop dari tanggal mulai hingga tanggal selesai
        $period = new \DatePeriod($start_date, new \DateInterval('P1D'), $end_date);

        foreach ($period as $date) {
            $current_date = $date->format('Y-m-d');
            $this->absensiPerizinan($perizinan['jenis_izin'], $current_date);
        };

        $this->karyawanService->setujuPerizinan($perizinan_id);
        $this->konfirmasiPerizinan();
    }

    private function absensiPerizinan($status, $tgl)
    {
        // Ambil user yang sedang login dari session
        $user = $this->sessionService->current();

        date_default_timezone_set('Asia/Jakarta');

        $request = new AbsensiRequest();
        $request->user_id = $user->user_id;
        $request->tanggal = $tgl;
        $request->status = $status;
        $request->status_validasi = "disetujui";
        $request->terlambat = "tidak";

        try {
            // Lanjutkan dengan pembuatan absensi
            $this->karyawanService->setAbsensiPerizinan($request);
        } catch (ValidationException $exception) {
            $this->absensiWithErrorMessage($exception->getMessage(), $user);
        }
    }




    // Konfirmasi Jobdesk

    public function konfirmasiJobdesk()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        // $jobdesk = $this->karyawanService->getAllJobdesk();
        $jobdesk = $this->karyawanService->getUnconfirmedJobdeskWithUsernames();

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
            "title" => "Konfirmasi Jobdesk",
            "sidebar" => [
                "menu" => "karyawan",
                "sub" => "konfirmasi_jobdesk"
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

        View::renderWithLayout('Karyawan/konfirmasi_jobdesk', $model, $css, $js);
    }

    public function tambahJobdesk()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new JobdeskRequest();
            $request->user_id = $_POST['user_id'];
            $request->nama_jobdesk = $_POST['nama_jobdesk'];
            $request->kategori = $_POST['kategori'];
            $request->tanggal = $_POST['tanggal'];
            $request->mulai = $_POST['mulai'];
            $request->selesai = $_POST['selesai'];
            $request->status = $_POST['status'];
            $request->point = $_POST['point'];
            $request->status_validasi = $_POST['status_validasi'];
            $request->keterangan = $_POST['keterangan'];
            $request->lampiran_url = $_FILES['lampiran_url']['name'];

            try {
                $this->karyawanService->tambahJobdesk($request);
                View::redirect('daftar_jobdesk');
            } catch (ValidationException $exception) {
                $this->tambahJobdesk($exception->getMessage());
            }
        } else {
            View::renderWithLayout('Jobdesk/tambah_jobdesk', [
                'title' => 'Tambah Jobdesk'
            ]);
        }
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
            $request->point = $_POST['point'];
            $request->status_validasi = $_POST['status_validasi'];
            $request->keterangan = $_POST['keterangan'];
            $request->lampiran_url = $_POST['lampiran_url'];

            try {
                $this->karyawanService->ubahJobdesk($request);
                View::redirect('konfirmasi_jobdesk');
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
        $this->karyawanService->deleteJobdesk($jobdesk_id);
        $this->konfirmasiJobdesk();
    }

    public function setujuJobdesk()
    {
        $jobdesk_id = $_GET['jobdesk_id'] ?? null;
        $this->karyawanService->setujuJobdesk($jobdesk_id);
        $this->konfirmasiJobdesk();
    }
}
