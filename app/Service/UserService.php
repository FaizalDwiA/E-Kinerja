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
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Exception\ValidationException;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function uploadImage($file, $opsi)
    {
        $targetDir = $this->getTargetDir($opsi);

        $originalFileName = basename($file["name"]);
        $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        // Buat nama file baru yang unik
        $newFileName = $this->generateUniqueFileName($originalFileName, $imageFileType);
        $targetFile = $targetDir . $newFileName;
        var_dump($targetFile);

        // Validasi tipe file (hanya gambar) dan kompresi serta simpan gambar
        $this->processAndSaveImage($file["tmp_name"], $targetFile, $imageFileType);

        return $newFileName;
    }

    private function getTargetDir($opsi)
    {
        if ($opsi == "profil") {
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

    private function validateUserRegistrationRequest(UserRequest $request)
    {
        if (
            $request->username == null || $request->email == null || $request->password == null || $request->nama_lengkap == null || $request->alamat == null || $request->jabatan == null || $request->foto_profil == null || $request->wa == null ||
            trim($request->username) == "" || trim($request->email) == "" || trim($request->password) == "" || trim($request->nama_lengkap) == "" || trim($request->alamat) == "" || trim($request->jabatan) == "" || trim($request->foto_profil) == "" || trim($request->wa) == ""
        ) {
            throw new ValidationException("can not blank");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findByUsername($request->username);

        if ($user == null) {
            throw new ValidationException("Id or password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id or password is wrong");
        }
    }


    public function getJumlahData()
    {
        return $this->userRepository->findJumlahData();
    }

    public function getJumlahJobdesk($userId)
    {
        return $this->userRepository->findJumlahJobdesk($userId);
    }

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if (
            $request->username == null ||
            $request->password == null ||
            trim($request->username) == "" ||
            trim($request->password) == ""
        ) {
            throw new ValidationException("Informasi login tidak valid");
        }
    }

    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByUsername($request->username);
            if ($user == null) {
                throw new ValidationException("Username tidak ditemukan");
            }

            if (!password_verify($request->password_lama, $user->password)) {
                throw new ValidationException("Password Lama Salah");
            }

            $user->password = password_hash($request->password_baru, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if (
            $request->username === null ||
            $request->password_lama === null ||
            $request->password_baru === null ||
            trim($request->username) === "" ||
            trim($request->password_lama) === "" ||
            trim($request->password_baru) === ""
        ) {
            throw new ValidationException("Username, Password Lama, dan Password Baru tidak boleh kosong");
        }
    }

    public function ubahProfil(UserRequest $request): UserResponse
    {
        // $this->validateUbahProfilRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByUserId($request->user_id);

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

            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserResponse();
            $response->user = $user;

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    // private function validateUbahProfilRequest(UserRequest $request)
    // {
    //     if (
    //         $request->user_id === null ||
    //         $request->username === null ||
    //         $request->email === null ||
    //         $request->nama_lengkap === null ||
    //         $request->foto_profil === null ||
    //         $request->alamat === null ||
    //         $request->jabatan === null ||

    //         $request->wa === null ||
    //         trim($request->username) === "" ||
    //         trim($request->email) === "" ||
    //         trim($request->nama_lengkap) === "" ||
    //         trim($request->foto_profil) === "" ||
    //         trim($request->alamat) === "" ||
    //         trim($request->jabatan) === "" ||

    //         trim($request->wa) === ""
    //     ) {
    //         throw new ValidationException("User ID, Username, Email, Password, Nama Lengkap, Alamat, Jabatan, Foto Profil, dan Whatsapp tidak boleh kosong");
    //     }
    // }
}
