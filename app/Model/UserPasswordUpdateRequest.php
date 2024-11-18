<?php

namespace BerkahSoloWeb\EKinerja\Model;

class UserPasswordUpdateRequest
{
    public ?string $user_id = null;
    public ?string $username = null;
    public ?string $password_lama = null;
    public ?string $password_baru = null;
}
