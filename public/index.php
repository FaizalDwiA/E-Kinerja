<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BerkahSoloWeb\EKinerja\App\Router;
use BerkahSoloWeb\EKinerja\Controller\HomeController;
use BerkahSoloWeb\EKinerja\Controller\UserController;
use BerkahSoloWeb\EKinerja\Controller\KaryawanController;
use BerkahSoloWeb\EKinerja\Controller\RiwayatController;
use BerkahSoloWeb\EKinerja\Controller\GajiController;
use BerkahSoloWeb\EKinerja\Controller\LaporanController;
use BerkahSoloWeb\EKinerja\Middleware\MustLoginMiddleware;
use BerkahSoloWeb\EKinerja\Middleware\MustNotLoginMiddleware;
use BerkahSoloWeb\EKinerja\Middleware\MustBePimpinanMiddleware;
use BerkahSoloWeb\EKinerja\Middleware\MustBeKaryawanMiddleware;

// Home Controller
Router::add('GET', '/', HomeController::class, 'index', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/dashboard_karyawan', HomeController::class, 'karyawan', [MustBeKaryawanMiddleware::class]);

// User & Profil Controller
// User
Router::add('GET', '/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);

// Profil
Router::add('GET', '/profil', UserController::class, 'profil', [MustLoginMiddleware::class]);
Router::add('GET', '/ubah_profil', UserController::class, 'ubahProfil', [MustLoginMiddleware::class]);
Router::add('POST', '/ubah_profil', UserController::class, 'postUbahProfil', [MustLoginMiddleware::class]);
Router::add('GET', '/ubah_password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/ubah_password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);


// Karyawan Controller
Router::add('GET', '/master_karyawan', KaryawanController::class, 'masterKaryawan', [MustBePimpinanMiddleware::class]);
Router::add('POST', '/master_karyawan', KaryawanController::class, 'postMasterKaryawan', [MustBePimpinanMiddleware::class]);
Router::add('POST', '/edit_karyawan', KaryawanController::class, 'editKaryawan', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/hapus_karyawan', KaryawanController::class, 'hapusKaryawan', [MustBePimpinanMiddleware::class]);

Router::add('GET', '/absensi', KaryawanController::class, 'absensi', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/absensi_check_in', KaryawanController::class, 'PostCheckIn', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/absensi_check_out', KaryawanController::class, 'PostCheckOut', [MustBeKaryawanMiddleware::class]);

Router::add('GET', '/perizinan', KaryawanController::class, 'perizinan', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/perizinan', KaryawanController::class, 'postPerizinan', [MustBeKaryawanMiddleware::class]);

Router::add('GET', '/jobdesk', KaryawanController::class, 'jobdesk', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/jobdesk', KaryawanController::class, 'postJobdesk', [MustBeKaryawanMiddleware::class]);

Router::add('GET', '/konfirmasi_absensi', KaryawanController::class, 'konfirmasiAbsensi', [MustBePimpinanMiddleware::class]);
Router::add('POST', '/edit_absensi', KaryawanController::class, 'editAbsensi', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/hapus_absensi', KaryawanController::class, 'hapusAbsensi', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/setuju_absensi', KaryawanController::class, 'setujuAbsensi', [MustBePimpinanMiddleware::class]);

Router::add('GET', '/konfirmasi_perizinan', KaryawanController::class, 'konfirmasiPerizinan', [MustBePimpinanMiddleware::class]);
Router::add('POST', '/edit_perizinan', KaryawanController::class, 'editPerizinan', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/hapus_perizinan', KaryawanController::class, 'hapusPerizinan', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/setuju_perizinan', KaryawanController::class, 'setujuPerizinan', [MustBePimpinanMiddleware::class]);

Router::add('GET', '/konfirmasi_jobdesk', KaryawanController::class, 'konfirmasiJobdesk', [MustBePimpinanMiddleware::class]);
Router::add('POST', '/ubah_jobdesk', KaryawanController::class, 'ubahJobdesk', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/hapus_jobdesk', KaryawanController::class, 'hapusJobdesk', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/setuju_jobdesk', KaryawanController::class, 'setujuJobdesk', [MustBePimpinanMiddleware::class]);


// Riwayat Karyawan
Router::add('GET', '/riwayat_absensi', RiwayatController::class, 'absensi', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/riwayat_jobdesk', RiwayatController::class, 'jobdesk', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/ubah_jobdesk_karyawan', RiwayatController::class, 'ubahJobdesk', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/hapus_jobdesk_karyawan', RiwayatController::class, 'hapusJobdesk', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/riwayat_perizinan', RiwayatController::class, 'perizinan', [MustBeKaryawanMiddleware::class]);
Router::add('POST', '/ubah_perizinan_karyawan', RiwayatController::class, 'ubahPerizinan', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/hapus_perizinan_karyawan', RiwayatController::class, 'hapusPerizinan', [MustBeKaryawanMiddleware::class]);


// Gaji Controller
Router::add('GET', '/gaji', GajiController::class, 'gaji', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/gaji_riwayat', GajiController::class, 'gajiRiwayat', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/hapus_gaji', GajiController::class, 'hapusGaji', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/setuju_gaji', GajiController::class, 'setujuGaji', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/batal_gaji', GajiController::class, 'batalGaji', [MustBePimpinanMiddleware::class]);
// Router::add('GET', '/generate_monthly_salaries', GajiController::class, 'generateMonthlySalaries', [MustLoginMiddleware::class]);


// Gaji Karyawan
Router::add('GET', '/gaji_karyawan', GajiController::class, 'gajiKaryawan', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/gaji_user', GajiController::class, 'gajiUser', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/simpan_gaji', GajiController::class, 'simpanGaji', [MustBeKaryawanMiddleware::class]);
Router::add('GET', '/tolak_gaji', GajiController::class, 'tolakGaji', [MustBeKaryawanMiddleware::class]);

// Slip Gaji
Router::add('GET', '/slip_gaji', GajiController::class, 'slipGaji', [MustLoginMiddleware::class]);


// Laporan Controller
Router::add('GET', '/laporan_karyawan', LaporanController::class, 'karyawan', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/laporan_absensi', LaporanController::class, 'absensi', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/laporan_gaji', LaporanController::class, 'gaji', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/laporan_perizinan', LaporanController::class, 'perizinan', [MustBePimpinanMiddleware::class]);
Router::add('GET', '/laporan_jobdesk', LaporanController::class, 'jobdesk', [MustBePimpinanMiddleware::class]);
// Router::add('POST', '/jobdesk', JobdeskController::class, 'postJobdesk', [MustBePimpinanMiddleware::class]);


// Penyusup
Router::add('GET', '/penyusup', UserController::class, 'penyusup', []);


// Bismillah
Router::run();
