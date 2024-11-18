<?php

namespace BerkahSoloWeb\EKinerja\Service;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\User;
use BerkahSoloWeb\EKinerja\Model\UserRequest;
use BerkahSoloWeb\EKinerja\Model\UserResponse;
use BerkahSoloWeb\EKinerja\Model\UserLoginRequest;
use BerkahSoloWeb\EKinerja\Model\UserLoginResponse;
use BerkahSoloWeb\EKinerja\Model\UserPasswordUpdateRequest;
use BerkahSoloWeb\EKinerja\Model\UserPasswordUpdateResponse;
use BerkahSoloWeb\EKinerja\Repository\HomeRepository;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;

class HomeService
{
    private HomeRepository $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    // Pimpinan

    public function getTotalKaryawan()
    {
        return $this->homeRepository->findTotalKaryawan();
    }

    public function getKehadiranBulananSemuaKaryawan($year, $month)
    {
        $dataKehadiran = $this->homeRepository->findKehadiranBulananSemuaKaryawan($year, $month);


        $totalHari = $dataKehadiran['total_hari'];
        $hariHadir = $dataKehadiran['hari_masuk'];
        $jumlahKaryawan = $dataKehadiran['total_karyawan'];

        $porsiKehadiran = ($totalHari > 0) ? ($hariHadir / ($jumlahKaryawan * $totalHari)) * 100 : 0;

        $hasilKehadiran = number_format($porsiKehadiran, 0);

        // Tampilkan hasil
        return $hasilKehadiran;
    }

    public function getTotalGaji($year, $month)
    {
        return number_format($this->homeRepository->findTotalGaji($year, $month), 2, ',', '.');
    }

    public function getTotalPerizinan($year, $month)
    {
        return $this->homeRepository->findTotalPerizinan($year, $month);
    }

    public function getKehadiranTahunanBulananSemuaKaryawan($year)
    {
        $dataKehadiran = $this->homeRepository->findKehadiranTahunanBulananSemuaKaryawan($year);

        // Prepare arrays to hold the data for chart
        $labels = [];
        $totalHari = [];
        $hariHadir = [];
        $jumlahKaryawan = [];

        // Initialize arrays for months
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $i; // Store month numbers
            $totalHari[$i] = 0;
            $hariHadir[$i] = 0;
            $jumlahKaryawan[$i] = 0;
        }

        // Populate data
        foreach ($dataKehadiran as $monthData) {
            $month = (int)$monthData['bulan'];
            $totalHari[$month] = (int)$monthData['total_hari'];
            $hariHadir[$month] = (int)$monthData['hari_masuk'];
            $jumlahKaryawan[$month] = (int)$monthData['total_karyawan'];
        }

        // Calculate percentage for each month
        $porsiKehadiran = [];
        foreach ($totalHari as $month => $total) {
            // Ensure we have the number of employees for the month to avoid division by zero
            $numKaryawan = $jumlahKaryawan[$month] > 0 ? $jumlahKaryawan[$month] : 1;
            $porsiKehadiran[] = ($total > 0) ? (($hariHadir[$month] / $total) * 100) / $numKaryawan : 0;
        }

        return [
            'labels' => $labels,
            'data' => $porsiKehadiran
        ];
    }


    public function getKategoriByUserAll($year, $month)
    {
        // Menyiapkan array kategori yang telah disebutkan
        $categories = ['desain-web', 'website', 'post-artikel', 'share-link', 'list-seo', 'maintenance-website'];

        $result = $this->homeRepository->findKategoriByUserAll($year, $month);

        // Membuat array untuk menyimpan hasil akhir dengan semua kategori
        $finalResult = [];

        foreach ($categories as $kategori) {
            $jumlah = 0;

            foreach ($result as $res) {
                if ($res['kategori'] == $kategori) {
                    $jumlah = $res['jumlah'];
                    break;
                }
            }

            $finalResult[] = ['kategori' => $kategori, 'jumlah' => $jumlah];
        }

        // var_dump($finalResult);
        // die;
        return $finalResult;
    }




    // Karyawan

    public function getPoints($user_id)
    {
        return $this->homeRepository->findPoints($user_id);
    }

    public function getKehadiranBulanan($userId, $year, $month)
    {
        $dataKehadiran = $this->homeRepository->findKehadiranBulanan($userId, $year, $month);
        $totalHari = $dataKehadiran['total_hari'];
        $hariHadir = $dataKehadiran['hari_masuk'];
        $porsiKehadiran = ($totalHari > 0) ? ($hariHadir / $totalHari) * 100 : 0;

        return number_format($porsiKehadiran, 0);
    }

    public function getStatusAbsensiByUser($user_id)
    {
        return $this->homeRepository->findStatusAbsensiByUser($user_id);
    }

    public function getPerizinanByUser($user_id, $year, $month)
    {
        return $this->homeRepository->findPerizinanByUser($user_id, $year, $month);
    }

    public function getGajiByUser($user_id, $year, $month)
    {
        return $this->homeRepository->findGajiByUser($user_id, $year, $month);
    }

    public function getGajiPerBulan($user_id, $year)
    {
        // Mendapatkan data gaji per bulan dari repository
        $data = $this->homeRepository->findGajiPerBulan($user_id, $year);

        // Jika data kosong, bisa mengembalikan array kosong atau menginisialisasi dengan data default
        if (!$data) {
            return [];
        }

        // Mengatur data untuk chart
        $gaji_per_bulan = array_fill(0, 12, 0); // Inisialisasi dengan 12 bulan, nilai default 0
        foreach ($data as $row) {
            $bulan = (int)$row['bulan'];
            $gaji_per_bulan[$bulan - 1] = (float)$row['gaji_total']; // Mengatur data gaji untuk bulan yang sesuai
        }

        return $gaji_per_bulan;
    }


    public function getKategoriByUser($user_id, $year, $month)
    {
        // Menyiapkan array kategori yang telah disebutkan
        $categories = ['desain-web', 'website', 'post-artikel', 'share-link', 'list-seo', 'maintenance-website'];

        $result = $this->homeRepository->findKategoriByUser($user_id, $year, $month);

        // Membuat array untuk menyimpan hasil akhir dengan semua kategori
        $finalResult = [];

        foreach ($categories as $kategori) {
            $jumlah = 0;

            foreach ($result as $res) {
                if ($res['kategori'] == $kategori) {
                    $jumlah = $res['jumlah'];
                    break;
                }
            }

            $finalResult[] = ['kategori' => $kategori, 'jumlah' => $jumlah];
        }

        // var_dump($finalResult);
        // die;
        return $finalResult;
    }
}
