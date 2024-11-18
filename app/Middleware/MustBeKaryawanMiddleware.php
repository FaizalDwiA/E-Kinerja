<?php

namespace BerkahSoloWeb\EKinerja\Middleware;

use BerkahSoloWeb\EKinerja\App\View;
use BerkahSoloWeb\EKinerja\Config\Database;
use BerkahSoloWeb\EKinerja\Repository\SessionRepository;
use BerkahSoloWeb\EKinerja\Repository\UserRepository;
use BerkahSoloWeb\EKinerja\Service\SessionService;

class MustBeKaryawanMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();

        if ($user == null) {
            View::redirect('login');
        } elseif ($user->role != 'karyawan') {
            View::redirect('/ekinerja');
        }
    }
}
