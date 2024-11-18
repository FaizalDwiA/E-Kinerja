<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess(): void
    {
        $user = new User();
        $user->username = "Faizal";
        $user->email = "faizal@gmail.com";
        $user->password = "123";

        $this->userRepository->save($user);

        $result = $this->userRepository->findById(14);

        self::assertEquals($user->username, $result->username);
        self::assertEquals($user->email, $result->email);
        self::assertEquals($user->password, $result->password);
    }
}