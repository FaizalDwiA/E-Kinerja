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
use BerkahSoloWeb\EKinerja\Repository\KaryawanRepository;
use BerkahSoloWeb\EKinerja\Model\UserRequest;
use BerkahSoloWeb\EKinerja\Model\UserResponse;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;

class KaryawanService
{
    private KaryawanRepository $karyawanRepository;

    public function __construct(KaryawanRepository $karyawanRepository)
    {
        $this->karyawanRepository = $karyawanRepository;
    }



    // Master Karyawan

    public function getAllKaryawan()
    {
        return $this->karyawanRepository->getAllKaryawan();
    }

    public function delete_karyawan($karyawan_id)
    {
        $this->karyawanRepository->deleteKaryawan($karyawan_id);
    }

    public function masterKaryawan(UserRequest $request): UserResponse
    {
        $this->validateKaryawanRequest($request);

        try {
            Database::beginTransaction();
            $karyawan = new User();
            $karyawan->username = $request->username;
            $karyawan->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->karyawanRepository->saveKaryawanBaru($karyawan);

            $response = new UserResponse();
            $response->user = $karyawan;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateKaryawanRequest(UserRequest $request)
    {
        if (
            $request->username == null ||
            $request->password == null ||
            trim($request->username) == "" ||
            trim($request->password) == ""
        ) {
            throw new ValidationException("tidak bisa kosong");
        }
    }

    public function getFotoProfilByUserId($user_id)
    {
        return $this->karyawanRepository->getFotoProfilByUserId($user_id);
    }

    public function ubahProfil(UserRequest $request): UserResponse
    {
        try {
            Database::beginTransaction();

            $user = $this->karyawanRepository->findByUserId($request->user_id);

            if ($user == null) {
                throw new ValidationException("User tidak ditemukan");
            }

            // Update data profil
            $user->username = $request->username;
            $user->email = $request->email;
            $user->nama_lengkap = $request->nama_lengkap;
            $user->alamat = $request->alamat;
            $user->jabatan = $request->jabatan;
            $user->foto_profil = $request->foto_profil;
            $user->wa = $request->wa;
            $user->tgl_lahir = $request->tgl_lahir;
            $user->catatan = $request->catatan;

            $this->karyawanRepository->updateKaryawan($user);

            Database::commitTransaction();

            $response = new UserResponse();
            $response->user = $user;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }



    // Absensi

    public function getAbsensiById($id)
    {
        return $this->karyawanRepository->findById($id);
    }

    public function getAbsensiId($userId, $tgl)
    {
        return $this->karyawanRepository->findById($userId, $tgl);
    }

    public function getCheckedIn($userId)
    {
        $tgl = (new \DateTime())->format('Y-m-d');

        $checkInRecord = $this->karyawanRepository->findCheckInForDate($userId, $tgl);;

        return !empty($checkInRecord);
    }

    public function check_in(AbsensiRequest $request): AbsensiResponse
    {
        $this->validateAbsensiRequest($request);

        try {
            Database::beginTransaction();
            $absensi = new Absensi();
            $absensi->user_id = $request->user_id;
            $absensi->tanggal = $request->tanggal;
            $absensi->jam = $request->jam;
            $absensi->status = $request->status;
            $absensi->bukti_gambar = $request->bukti_gambar;
            $absensi->status_validasi = $request->status_validasi;
            $absensi->terlambat = $request->terlambat;

            $this->karyawanRepository->saveCheckIn($absensi);

            $response = new AbsensiResponse();
            $response->absensi = $absensi;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function check_out(AbsensiRequest $request): AbsensiResponse
    {
        $this->validateAbsensiRequest($request);

        try {
            Database::beginTransaction();

            // Temukan absensi_id untuk user yang sama pada tanggal yang sama
            $absensi_id = $this->karyawanRepository->findAbsensiId($request->user_id, $request->tanggal);

            if (!$absensi_id) {
                throw new ValidationException('Belum ada check-in untuk tanggal ini.');
                // throw new ValidationException("tidak bisa kosong");
            }

            $absensi = new Absensi();
            $absensi->user_id = $request->user_id;
            $absensi->tanggal = $request->tanggal;
            $absensi->jam = $request->jam;
            $absensi->absensi_id = $absensi_id;  // Set absensi_id dari database

            // Simpan data check-out ke database
            $this->karyawanRepository->saveCheckOut($absensi);

            $response = new AbsensiResponse();
            $response->absensi = $absensi;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateAbsensiRequest(AbsensiRequest $request)
    {
        if (
            $request->user_id == null ||
            $request->tanggal == null ||
            $request->jam == null ||
            trim($request->user_id) == "" ||
            trim($request->tanggal) == "" ||
            trim($request->jam) == ""
        ) {
            throw new ValidationException("tidak bisa kosong");
        }
    }

    public function getUsername($user_id)
    {
        $username = $this->getUsernameById($user_id);
        if ($username) {
            echo "Username: " . $username . "\n";
        } else {
            echo "Username tidak ditemukan.\n";
        }
    }

    public function getUsernameById($user_id)
    {
        return $this->karyawanRepository->findUsernameById($user_id);
    }







    // Jobdesk

    public function jobdesk(JobdeskRequest $request): JobdeskResponse
    {
        $this->validateJobdeskRequest($request);

        try {
            Database::beginTransaction();

            $jobdesk = new Jobdesk();
            $jobdesk->user_id = $request->user_id;
            $jobdesk->nama_jobdesk = $request->nama_jobdesk;
            $jobdesk->kategori = $request->kategori;
            $jobdesk->tanggal = $request->tanggal;
            $jobdesk->mulai = $request->mulai;
            $jobdesk->selesai = $request->selesai;
            $jobdesk->status = $request->status;
            $jobdesk->lampiran_url = $request->lampiran_url;
            $jobdesk->point = $request->point;
            $jobdesk->keterangan = $request->keterangan;

            $this->karyawanRepository->saveJobdesk($jobdesk);

            $response = new JobdeskResponse();
            $response->jobdesk = $jobdesk;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateJobdeskRequest(JobdeskRequest $request)
    {
        if (
            $request->user_id == null ||
            $request->nama_jobdesk == null ||
            $request->kategori == null ||
            $request->tanggal == null ||
            $request->mulai == null ||
            $request->selesai == null ||
            $request->status == null ||
            $request->lampiran_url == null ||
            trim($request->user_id) == "" ||
            trim($request->nama_jobdesk) == "" ||
            trim($request->kategori) == "" ||
            trim($request->tanggal) == "" ||
            trim($request->mulai) == "" ||
            trim($request->selesai) == "" ||
            trim($request->status) == "" ||
            trim($request->lampiran_url) == ""
        ) {
            throw new ValidationException("Fields tidak boleh kosong");
        }
    }

    public function getAllJobdesk()
    {
        return $this->karyawanRepository->findAllJobdesk();
    }

    public function getPoinByKategori($id)
    {
        return $this->karyawanRepository->getPoin($id);
    }



    // Perizinan

    public function ajukanPerizinan(PerizinanRequest $request): PerizinanResponse
    {
        $this->validatePerizinanRequest($request);

        try {
            Database::beginTransaction();

            $perizinan = new Perizinan();
            $perizinan->user_id = $request->user_id;
            $perizinan->jenis_izin = $request->jenis_izin;
            $perizinan->tgl_mulai = $request->tgl_mulai;
            $perizinan->tgl_selesai = $request->tgl_selesai;
            $perizinan->alasan = $request->alasan;
            $perizinan->bukti_gambar = $request->bukti_gambar;

            $this->karyawanRepository->savePerizinan($perizinan);

            $response = new PerizinanResponse();
            $response->perizinan = $perizinan;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validatePerizinanRequest(PerizinanRequest $request)
    {
        if (
            $request->user_id == null ||
            $request->jenis_izin == null ||
            $request->tgl_mulai == null ||
            $request->tgl_selesai == null ||
            $request->alasan == null ||
            $request->bukti_gambar == null ||
            trim($request->user_id) == "" ||
            trim($request->jenis_izin) == "" ||
            trim($request->tgl_mulai) == "" ||
            trim($request->tgl_selesai) == "" ||
            trim($request->alasan) == "" ||
            trim($request->bukti_gambar) == ""
        ) {
            throw new ValidationException("Fields tidak boleh kosong");
        }

        // Validasi tambahan (misalnya, tanggal mulai harus sebelum tanggal selesai)
        if (strtotime($request->tgl_mulai) > strtotime($request->tgl_selesai)) {
            throw new ValidationException("Tanggal mulai harus sebelum atau sama dengan tanggal selesai");
        }
    }



    // Konfirmasi Absensi

    public function getUnconfirmedAbsensiWithUsernames()
    {
        return $this->karyawanRepository->UnconfirmedAbsensiWithUsernames();
    }

    public function getBuktiGambarByAbsensiId(string $absensi_id): ?string
    {
        return $this->karyawanRepository->findBuktiGambarByAbsensiId($absensi_id);
    }

    public function ubahAbsensi(AbsensiRequest $request): AbsensiResponse
    {
        try {
            Database::beginTransaction();

            $absensi = $this->karyawanRepository->findByAbsensiId($request->absensi_id);

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

            $this->karyawanRepository->updateAbsensi($absensi);

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
        $this->karyawanRepository->setujuAbsensi($absensi_id);
    }

    public function deleteAbsensi($absensi_id)
    {
        $this->karyawanRepository->deleteAbsensi($absensi_id);
    }




    // Konfirmasi Perizinan

    public function getUnconfirmedPerizinanWithUsernames()
    {
        return $this->karyawanRepository->UnconfirmedPerizinanWithUsernames();
    }

    public function getBuktiGambarByPerizinanId(string $perizinan_id): ?string
    {
        return $this->karyawanRepository->findBuktiGambarByPerizinanId($perizinan_id);
    }

    public function ubahPerizinan(PerizinanRequest $request): PerizinanResponse
    {
        try {
            Database::beginTransaction();

            $perizinan = $this->karyawanRepository->findByPerizinanId($request->perizinan_id);

            if ($perizinan == null) {
                throw new ValidationException("Perizinan tidak ditemukan");
            }

            $perizinan->tgl_mulai = $request->tgl_mulai;
            $perizinan->tgl_selesai = $request->tgl_selesai;
            $perizinan->jenis_izin = $request->jenis_izin;
            $perizinan->alasan = $request->alasan;
            $perizinan->bukti_gambar = $request->bukti_gambar;
            $perizinan->status_validasi = $request->status_validasi;

            $this->karyawanRepository->updatePerizinan($perizinan);

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
        $this->karyawanRepository->setujuPerizinan($perizinan_id);
    }

    public function getPerizinanById($perizinan_id)
    {
        return $this->karyawanRepository->PerizinanById($perizinan_id);
    }

    public function setAbsensiPerizinan(AbsensiRequest $request): AbsensiResponse
    {
        try {
            Database::beginTransaction();
            $absensi = new Absensi();
            $absensi->user_id = $request->user_id;
            $absensi->tanggal = $request->tanggal;
            $absensi->status = $request->status;
            $absensi->status_validasi = $request->status_validasi;
            $absensi->terlambat = $request->terlambat;

            $this->karyawanRepository->saveAbsensiPerizinan($absensi);

            $response = new AbsensiResponse();
            $response->absensi = $absensi;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function deletePerizinan($perizinan_id)
    {
        $this->karyawanRepository->deletePerizinan($perizinan_id);
    }



    // Konfirmasi Jobdesk

    public function tambahJobdesk(JobdeskRequest $request): JobdeskResponse
    {
        try {
            Database::beginTransaction();

            $jobdesk = new Jobdesk();
            $jobdesk->user_id = $request->user_id;
            $jobdesk->nama_jobdesk = $request->nama_jobdesk;
            $jobdesk->kategori = $request->kategori;
            $jobdesk->tanggal = $request->tanggal;
            $jobdesk->mulai = $request->mulai;
            $jobdesk->selesai = $request->selesai;
            $jobdesk->status = $request->status;
            $jobdesk->lampiran_url = $request->lampiran_url;
            $jobdesk->point = $request->point;
            $jobdesk->status_validasi = $request->status_validasi;
            $jobdesk->keterangan = $request->keterangan;

            $this->karyawanRepository->insertJobdesk($jobdesk);

            Database::commitTransaction();

            $response = new JobdeskResponse();
            $response->jobdesk = $jobdesk;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function getUnconfirmedJobdeskWithUsernames()
    {
        return $this->karyawanRepository->unconfirmedJobdeskWithUsernames();
    }

    public function ubahJobdesk(JobdeskRequest $request): JobdeskResponse
    {
        try {
            Database::beginTransaction();

            $jobdesk = $this->karyawanRepository->findByJobdeskId($request->jobdesk_id);

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
            $jobdesk->point = $request->point;
            $jobdesk->keterangan = $request->keterangan;

            $this->karyawanRepository->updateJobdesk($jobdesk);

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
        $this->karyawanRepository->setujuJobdesk($jobdesk_id);
    }

    public function deleteJobdesk($jobdesk_id)
    {
        $this->karyawanRepository->deleteJobdesk($jobdesk_id);
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
        } elseif ($imageFileType == '') {
            $imageFileType = "tidak ada";
        }

        throw new ValidationException("Unsupported image type: $imageFileType");
        throw new ValidationException("Id or password is wrong");
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
