<?php

namespace BerkahSoloWeb\EKinerja\Service;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\Laporan;
use BerkahSoloWeb\EKinerja\Model\MasukRequest;
use BerkahSoloWeb\EKinerja\Model\MasukResponse;
use BerkahSoloWeb\EKinerja\Repository\LaporanRepository;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;

class LaporanService
{
    private LaporanRepository $laporanRepository;

    public function __construct(LaporanRepository $laporanRepository)
    {
        $this->laporanRepository = $laporanRepository;
    }

    public function getAllUsers()
    {
        return $this->laporanRepository->findAllUsers();
    }

    public function getAllAbsensi()
    {
        return $this->laporanRepository->findAllAbsensi();
    }

    public function getAllJobdesk()
    {
        return $this->laporanRepository->findAllJobdesk();
    }

    public function getAllPerizinan()
    {
        return $this->laporanRepository->findAllPerizinan();
    }

    public function getAllGaji()
    {
        return $this->laporanRepository->findAllGaji();
    }


    // Bismillah

    private function getNamaById($user_id)
    {
        // return $this->laporanRepository->findUserById($user_id);
        // Mengambil data pengguna berdasarkan user_id
        $user = $this->laporanRepository->findUserById($user_id);

        // Mengembalikan nama lengkap
        return isset($user['nama_lengkap']) ? $user['nama_lengkap'] : null;
    }
}
