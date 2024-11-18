<?php

namespace BerkahSoloWeb\EKinerja\Service;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\Absensi;
use BerkahSoloWeb\EKinerja\Domain\Jobdesk;
use BerkahSoloWeb\EKinerja\Domain\Perizinan;
use BerkahSoloWeb\EKinerja\Domain\User;
use BerkahSoloWeb\EKinerja\Model\AbsensiRequest;
use BerkahSoloWeb\EKinerja\Model\AbsensiResponse;
use BerkahSoloWeb\EKinerja\Model\JobdeskRequest;
use BerkahSoloWeb\EKinerja\Model\JobdeskResponse;
use BerkahSoloWeb\EKinerja\Model\PerizinanRequest;
use BerkahSoloWeb\EKinerja\Model\PerizinanResponse;
use BerkahSoloWeb\EKinerja\Repository\RiwayatRepository;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;
use BerkahSoloWeb\EKinerja\Model\KaryawanBaruRequest;
use BerkahSoloWeb\EKinerja\Model\KaryawanBaruResponse;
use BerkahSoloWeb\EKinerja\Model\UserRequest;
use BerkahSoloWeb\EKinerja\Model\UserResponse;

class RiwayatService
{
    private RiwayatRepository $RiwayatRepository;

    public function __construct(RiwayatRepository $RiwayatRepository)
    {
        $this->RiwayatRepository = $RiwayatRepository;
    }


    // Absensi

    public function getUnconfirmedAbsensiWithUsernames($userId)
    {
        return $this->RiwayatRepository->UnconfirmedAbsensiWithUsernames($userId);
    }

    public function getBuktiGambarByAbsensiId(string $absensi_id): ?string
    {
        return $this->RiwayatRepository->findBuktiGambarByAbsensiId($absensi_id);
    }

    public function ubahAbsensi(AbsensiRequest $request): AbsensiResponse
    {
        try {
            Database::beginTransaction();

            $absensi = $this->RiwayatRepository->findByAbsensiId($request->absensi_id);

            if ($absensi == null) {
                throw new ValidationException("Absensi tidak ditemukan");
            }

            // Update data absensi
            $absensi->check_in = $request->check_in;
            $absensi->check_out = $request->check_out;
            $absensi->status = $request->status;
            $absensi->terlambat = $request->terlambat;
            $absensi->bukti_gambar = $request->bukti_gambar;
            $absensi->status_validasi = $request->status_validasi;

            $this->RiwayatRepository->updateAbsensi($absensi);

            Database::commitTransaction();

            $response = new AbsensiResponse();
            $response->absensi = $absensi;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function setujuAbsensi($absensi_id)
    {
        $this->RiwayatRepository->setujuAbsensi($absensi_id);
    }

    public function deleteAbsensi($absensi_id)
    {
        $this->RiwayatRepository->deleteAbsensi($absensi_id);
    }




    // Jobdesk

    public function getUnconfirmedJobdeskWithUsernames($userId)
    {
        return $this->RiwayatRepository->unconfirmedJobdeskWithUsernames($userId);
    }

    public function ubahJobdesk(JobdeskRequest $request): JobdeskResponse
    {
        try {
            Database::beginTransaction();

            $jobdesk = $this->RiwayatRepository->findByJobdeskId($request->jobdesk_id);

            if ($jobdesk == null) {
                throw new ValidationException("Jobdesk tidak ditemukan");
            }

            // Update data jobdesk
            $jobdesk->nama_jobdesk = $request->nama_jobdesk;
            $jobdesk->kategori = $request->kategori;
            $jobdesk->tanggal = $request->tanggal;
            $jobdesk->mulai = $request->mulai;
            $jobdesk->selesai = $request->selesai;
            $jobdesk->status = $request->status;
            $jobdesk->lampiran_url = $request->lampiran_url;
            $jobdesk->keterangan = $request->keterangan;

            $this->RiwayatRepository->updateJobdesk($jobdesk);

            Database::commitTransaction();

            $response = new JobdeskResponse();
            $response->jobdesk = $jobdesk;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function setujuJobdesk($jobdesk_id)
    {
        $this->RiwayatRepository->setujuJobdesk($jobdesk_id);
    }

    public function deleteJobdesk($jobdesk_id)
    {
        $this->RiwayatRepository->deleteJobdesk($jobdesk_id);
    }




    // Konfirmasi Perizinan

    public function getUnconfirmedPerizinanWithUsernames($userId)
    {
        return $this->RiwayatRepository->UnconfirmedPerizinanWithUsernames($userId);
    }

    public function getBuktiGambarByPerizinanId(string $perizinan_id): ?string
    {
        return $this->RiwayatRepository->findBuktiGambarByPerizinanId($perizinan_id);
    }

    public function ubahPerizinan(PerizinanRequest $request): PerizinanResponse
    {
        try {
            Database::beginTransaction();

            $perizinan = $this->RiwayatRepository->findByPerizinanId($request->perizinan_id);

            if ($perizinan == null) {
                throw new ValidationException("Perizinan tidak ditemukan");
            }

            $perizinan->tgl_mulai = $request->tgl_mulai;
            $perizinan->tgl_selesai = $request->tgl_selesai;
            $perizinan->jenis_izin = $request->jenis_izin;
            $perizinan->alasan = $request->alasan;
            $perizinan->bukti_gambar = $request->bukti_gambar;

            $this->RiwayatRepository->updatePerizinan($perizinan);

            Database::commitTransaction();

            $response = new PerizinanResponse();
            $response->perizinan = $perizinan;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function setujuPerizinan($perizinan_id)
    {
        $this->RiwayatRepository->setujuPerizinan($perizinan_id);
    }

    public function deletePerizinan($perizinan_id)
    {
        $this->RiwayatRepository->deletePerizinan($perizinan_id);
    }






    // Bismillah
    // Gambar

    public function uploadImage($file, $target)
    {
        $targetDir = $this->getTargetDir($target);

        $originalFileName = basename($file["name"]);
        $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        // Buat nama file baru yang unik
        $newFileName = $this->generateUniqueFileName($originalFileName, $imageFileType);
        $targetFile = $targetDir . $newFileName;

        // Validasi tipe file (hanya gambar) dan kompresi serta simpan gambar
        $this->processAndSaveImage($file["tmp_name"], $targetFile, $imageFileType);

        return $newFileName;
    }

    private function getTargetDir($opsi)
    {
        if ($opsi == "bukti") {
            return "uploads/bukti/";
        } elseif ($opsi == "profil") {
            return "uploads/profil/";
        }
        throw new \Exception("Invalid opsi: $opsi");
    }

    private function processAndSaveImage($sourceFile, $targetFile, $imageFileType)
    {
        $image = $this->createImageFromFile($sourceFile, $imageFileType);

        $compressionQuality = 90;
        do {
            if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
                imagejpeg($image, $targetFile, $compressionQuality);
            } elseif ($imageFileType == 'png') {
                imagepng($image, $targetFile, (int)($compressionQuality / 10 - 1));
            } elseif ($imageFileType == 'gif') {
                imagegif($image, $targetFile);
            }

            $fileSize = filesize($targetFile);
            $compressionQuality -= 10;
        } while ($fileSize > 1 * 1024 * 1024 && $compressionQuality > 10);

        imagedestroy($image);
    }

    private function createImageFromFile($sourceFile, $imageFileType)
    {
        if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
            return imagecreatefromjpeg($sourceFile);
        } elseif ($imageFileType == 'png') {
            return imagecreatefrompng($sourceFile);
        } elseif ($imageFileType == 'gif') {
            return imagecreatefromgif($sourceFile);
        }
        throw new \Exception("Unsupported image type: $imageFileType");
    }

    private function generateUniqueFileName($originalFileName, $imageFileType)
    {
        $timestamp = time(); // Waktu saat ini
        $randomString = bin2hex(random_bytes(8)); // String acak

        // Gabungkan waktu dan string acak untuk membuat nama file baru
        $newFileName = $timestamp . '_' . $randomString . '.' . $imageFileType;

        return $newFileName;
    }
}
