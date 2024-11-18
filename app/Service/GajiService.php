<?php

namespace BerkahSoloWeb\EKinerja\Service;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\Gaji;
use BerkahSoloWeb\EKinerja\Domain\RiwayatGaji;
use BerkahSoloWeb\EKinerja\Repository\GajiRepository;
use BerkahSoloWeb\EKinerja\Model\RiwayatGajiRequest;
use BerkahSoloWeb\EKinerja\Model\RiwayatGajiResponse;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;

class GajiService
{
    private GajiRepository $gajiRepository;

    public function __construct(GajiRepository $gajiRepository)
    {
        $this->gajiRepository = $gajiRepository;
    }

    public function getAllGajiKaryawan()
    {
        return $this->gajiRepository->findAll();
    }

    // public function getAllUsersById($user_id)
    // {
    //     return $this->gajiRepository->findAllUsersById($user_id);
    // }

    public function getUserById($user_id)
    {
        return $this->gajiRepository->findUserById($user_id);
    }

    public function updateGaji($gaji_id, $user_id, $bulan, $tahun, $gaji_pokok, $tunjangan, $pemotongan, $gaji_total, $status_pembayaran)
    {
        $gaji = new Gaji();
        $gaji->gaji_id = $gaji_id;
        $gaji->user_id = $user_id;
        $gaji->bulan = $bulan;
        $gaji->tahun = $tahun;
        $gaji->gaji_pokok = $gaji_pokok;
        $gaji->tunjangan = $tunjangan;
        $gaji->pemotongan = $pemotongan;
        $gaji->gaji_total = $gaji_total;
        $gaji->status_pembayaran = $status_pembayaran;

        $this->gajiRepository->update($gaji);
    }

    public function setujuGaji($gaji_id)
    {
        $this->gajiRepository->setuju($gaji_id);
    }

    public function setBatalGaji($gaji_id)
    {
        $this->gajiRepository->batal($gaji_id);
    }

    public function deleteGaji($gaji_id)
    {
        $this->gajiRepository->delete($gaji_id);
    }

    public function getAllGajiRiwayat()
    {
        return $this->gajiRepository->findAllGajiRiwayat();
    }


    // bismillah

    public function generateMonthlySalaries($month)
    {
        $users = $this->gajiRepository->findAllUsers();

        foreach ($users as $user) {
            $gaji = $this->createGajiForUser($user, $month);
            $this->gajiRepository->updateGaji($gaji);
        }
    }

    private function createGajiForUser($user, $month)
    {
        $parts = explode('-', $month);

        // Ambil tahun dan bulan
        $tahun = $parts[0];
        $bulan = $parts[1];

        $gaji = new Gaji();
        $gaji->user_id = $user["user_id"];

        // Ambil tahun dan bulan
        $parts = explode('-', $month);

        $tahun = $parts[0];
        $bulan = $parts[1];

        $getBulan = "0" . $this->getBulanByUserId($user["user_id"]);

        $hasilBulan = ($getBulan < $bulan) ? $getBulan : $bulan;

        $gaji->bulan = $hasilBulan;
        $gaji->tahun = $tahun;

        // Hitung gaji pokok
        $gaji->gaji_pokok = $this->calculateGajiPokok($user, $gaji->bulan, $gaji->tahun);
        $gaji->gaji_pokok = round($gaji->gaji_pokok / 1000) * 1000;

        // Placeholder untuk tunjangan
        $gaji->tunjangan = 0;

        // Hitung pemotongan berdasarkan absensi
        $gaji->pemotongan = $this->calculatePemotongan($user, $gaji->bulan, $gaji->tahun, $gaji->gaji_pokok);
        $gaji->pemotongan = round($gaji->pemotongan, 2);

        // Hitung total gaji dan bulatkan
        $gaji->gaji_total = round($gaji->gaji_pokok + $gaji->tunjangan - $gaji->pemotongan, 2);

        $status_pembayaran = $this->getStatusPembayaranByUserId($user["user_id"]);

        $gaji->status_pembayaran = !in_array("dibayar", ['dibayar', 'disimpan']) ? 'diproses' : $status_pembayaran;

        $gaji->created_at = date('Y-m-d H:i:s');
        $gaji->updated_at = date('Y-m-d H:i:s');

        return $gaji;
    }

    private function getStatusPembayaranByUserId($user_id)
    {
        return $this->gajiRepository->findStatusPembayaranByUserId($user_id);
    }

    private function getBulanByUserId($user_id)
    {
        return $this->gajiRepository->findBulanByUserId($user_id);
    }

    public function calculateGajiPokok($user, $bulan, $tahun)
    {
        $baseSalary = 2000000; // Gaji pokok tetap

        // Get the number of distinct working days for the user in the given month
        $distinctDays = $this->gajiRepository->getDistinctWorkingDays($bulan, $tahun);

        // Calculate the minimum points based on distinct working days
        $minimumPoints = $distinctDays * 10;

        // Ensure minimumPoints is not zero to avoid division by zero
        if ($minimumPoints == 0) {
            // Handle the case where minimumPoints is zero, for example:
            return 0; // or return some default value
        }


        // Fetch total jobdesk points
        $totalPoints = $this->gajiRepository->getJobdeskPoints($user["user_id"], $bulan, $tahun);

        // Calculate the percentage based on the minimum points
        $percentage = min(100, ($totalPoints / $minimumPoints) * 100);

        return $baseSalary * ($percentage / 100);
    }


    private function calculatePemotongan($user, $bulan, $tahun, $gajiPokok)
    {
        $totalTerlambat = $this->gajiRepository->getTotalTerlambat($user["user_id"], $bulan, $tahun);
        $persentasePemotongan = $totalTerlambat * 0.01;

        return $gajiPokok * $persentasePemotongan;
    }



    // Karyawan

    public function getGajiByUserDibayar($user_id)
    {
        return $this->gajiRepository->findGajiByUserDibayar($user_id, "dibayar");
    }

    public function getGajiById($gaji_id)
    {
        return $this->gajiRepository->findGajiById($gaji_id);
    }

    public function getGajiRiwayatByUserId($user_id)
    {
        return $this->gajiRepository->findGajiRiwayatByUserId($user_id, "disimpan");
    }

    public function setSimpanGaji($gaji)
    {
        $this->gajiRepository->simpanGaji($gaji);
    }

    public function setRestartGaji($gaji_id)
    {
        $bulan = date('m');
        $tahun = date('Y');

        return $this->gajiRepository->restartGaji($gaji_id, $bulan, $tahun);
    }

    public function setRestartPoints($gaji_id)
    {
        $bulan = date('m');
        $tahun = date('Y');

        return $this->gajiRepository->restartPoints($gaji_id, $bulan, $tahun);
    }

    public function getTolakGaji($gaji_id)
    {
        $this->gajiRepository->TolakGaji($gaji_id);
    }

    public function getRiwayatId($gaji_id)
    {
        return $this->gajiRepository->findRiwayatId($gaji_id);
    }

    public function getUserByHistoryId($user_id)
    {
        return $this->gajiRepository->findUserByHistoryId($user_id);
    }
}
