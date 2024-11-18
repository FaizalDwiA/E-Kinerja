<?php

namespace BerkahSoloWeb\EKinerja\Domain;

class User
{
    public string $user_id;
    public string $username;
    public ?string $email = NULL;
    public string $password;
    public ?string $nama_lengkap = NULL;
    public ?string $alamat = NULL;
    public ?string $jabatan = NULL;
    public ?string $foto_profil = NULL;
    public ?string $wa = NULL;
    public ?string $status;
    public ?string $role;
    public ?string $catatan = NULL;
    public ?string $tgl_lahir = NULL;
    // public ?string $created_at;
    // public ?string $updated_at;
}
