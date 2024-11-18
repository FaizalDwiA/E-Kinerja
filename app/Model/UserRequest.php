<?php

namespace BerkahSoloWeb\EKinerja\Model;

class UserRequest
{
    public ?string $user_id;
    public string $username;
    public string $email;
    public ?string $password;
    public ?string $password2;
    public string $nama_lengkap;
    public string $alamat;
    public string $jabatan;
    public ?string $foto_profil;
    public string $wa;
    public ?string $catatan;
    public ?string $tgl_lahir;
}
