<?php

namespace BerkahSoloWeb\EKinerja\Controller;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;
use BerkahSoloWeb\EKinerja\Model\UserRequest;
use BerkahSoloWeb\EKinerja\Model\UserLoginRequest;
use BerkahSoloWeb\EKinerja\Model\UserPasswordUpdateRequest;
use BerkahSoloWeb\EKinerja\Model\UserPasswordUpdateResponse;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Service\UserService;
use BerkahSoloWeb\EKinerja\Service\SessionService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function login()
    {
        View::renderSingleFile('User/login', []);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->user_id);

            if ($response->user->role == 'pimpinan') {
                View::redirect('/ekinerja', [
                    'nama_lengkap' => $response->user->nama_lengkap
                ]);
            } else {
                View::redirect('dashboard_karyawan', [
                    'nama_lengkap' => $response->user->nama_lengkap
                ]);
            }
        } catch (ValidationException $exception) {
            View::renderSingleFile('User/login', [
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect("login");
    }


    // Profil

    public function profil($error = null)
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $absen = $this->userService->getJumlahData();
        $jobdesk = $this->userService->getJumlahJobdesk($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
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

        $model = [
            "title" => "Profil Pribadi",
            'error' => $error,
            "sidebar" => [
                "menu" => "profil",
                "sub" => "profil_pribadi"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "email" => $user->email,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "jabatan" => $user->jabatan,
                "alamat" => $user->alamat,
                "wa" => $user->wa,
                "tgl_lahir" => $user->tgl_lahir,
                "catatan" => $user->catatan,
                "role" => $user->role
            ],
            "data" => [
                "masuk" => $absen['jumlah_masuk'],
                "izin" => $absen['jumlah_izin'],
                "sakit" => $absen['jumlah_sakit'],
                "jobdesk" => $jobdesk['jumlah_jobdesk']
            ]
        ];

        View::renderWithLayout('Profil/profil', $model, $css, $js);
    }

    public function ubahProfil($error = null)
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
                const fileInput = document.getElementById('foto_profil');

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
                    const label = document.getElementById('label_foto_profil');
                    label.innerText = fileName;
                });
            </script>
        EOD;

        $model = [
            "title" => "Ubah Profil",
            "sidebar" => [
                "menu" => "profil",
                "sub" => "ubah_profil"
            ],
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "user_id" => $user->user_id,
                "username" => $user->username,
                "email" => $user->email,
                "nama_lengkap" => $user->nama_lengkap,
                "alamat" => $user->alamat,
                "jabatan" => $user->jabatan,
                "foto" => $user->foto_profil,
                "wa" => $user->wa,
                "tgl_lahir" => $user->tgl_lahir,
                "catatan" => $user->catatan,
                "role" => $user->role
            ],
            "error" => $error
        ];

        View::renderWithLayout('Profil/ubah_profil', $model, $css, $js);
    }

    public function postUbahProfil()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        // Periksa apakah request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new UserRequest();
            $request->user_id = $user->user_id;
            $request->username = $user->username;
            $request->email = $_POST['email'];
            $request->nama_lengkap = $_POST['nama_lengkap'];
            $request->alamat = $_POST['alamat'];
            $request->jabatan = $_POST['jabatan'];
            $request->wa = $_POST['wa'];
            $request->tgl_lahir = $_POST['tgl_lahir'];
            $request->catatan = $_POST['catatan'];

            // Ambil nama file gambar lama
            $oldFileName = $user->foto_profil;

            // Tangani pengunggahan gambar
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                $newFileName = $this->userService->uploadImage($_FILES['foto_profil'], 'profil');
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
                $cek = $this->userService->ubahProfil($request);
                View::redirect('profil');
            } catch (ValidationException $exception) {
                // View::renderWithLayout('profil/ubah_profil', [
                //     'title' => 'Ubah Profil',
                //     'error' => $exception->getMessage(),
                //     'user' => $request
                // ]);
                $this->ubahProfil($exception->getMessage());
            }
        } else {
            // Tampilkan form ubah profil
            View::renderWithLayout('profil/profil', [
                'title' => 'Ubah Profil'
            ]);
        }
    }

    public function updatePassword($error = null)
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);

        $css = <<<EOD
            <link href="css/styles.css" rel="stylesheet">
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

        $model = [
            "title" => "Ubah Password",
            "sidebar" => [
                "menu" => "profil",
                "sub" => "ubah_password"
            ],
            'error' => $error,
            "cek" => [
                "absensi" => $absensiHariIni
            ],
            "user" => [
                "nama_lengkap" => $user->nama_lengkap,
                "username" => $user->username,
                "foto" => $user->foto_profil,
                "user_id" => $user->user_id,
                "role" => $user->role
            ]
        ];

        View::renderWithLayout('Profil/ubah_password', $model, $css, $js);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();

        $absensiHariIni = $this->sessionService->getAbsensiHariIni($user->user_id);
        $request = new UserPasswordUpdateRequest();
        $request->user_id = $user->user_id;
        $request->username = $user->username;
        $request->password_lama = $_POST['password_lama'];
        $request->password_baru = $_POST['password_baru'];

        try {
            $this->userService->updatePassword($request);
            $this->logout();
        } catch (ValidationException $exception) {
            $this->updatePassword($exception->getMessage());
        }
    }





    // Penyusup
    public function penyusup()
    {
        View::renderSingleFile('404/index', []);
    }
}
