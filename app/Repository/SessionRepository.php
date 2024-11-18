<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\Session;

class SessionRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $statement = $this->connection->prepare("INSERT INTO sessions(id, user_id) VALUES (?, ?)");
        $statement->execute([$session->id, $session->userId]);
        return $session;
    }

    public function findById(string $id): ?Session
    {
        $statement = $this->connection->prepare("SELECT id, user_id from sessions WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['user_id'];
                return $session;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
        $statement->execute([$id]);
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM sessions");
    }

    public function getAbsensiHariIni($user_id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $tgl = date('Y-m-d');

        $currentHour = date('H');
        $fieldToCheck = '';

        // Menentukan field yang akan dicek berdasarkan waktu saat ini
        if ($currentHour >= 7 && $currentHour < 12) {
            $fieldToCheck = 'check_in';
        } elseif ($currentHour >= 12 && $currentHour < 19) {
            $fieldToCheck = 'check_out';
        } else {
            // Jika waktu tidak sesuai dengan rentang yang diberikan
            return "tutup";
        }

        // Menyiapkan statement
        $stmt = $this->connection->prepare("SELECT * FROM absensi WHERE user_id = :user_id AND tanggal = :tgl");

        // Mengikat parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tgl', $tgl);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            // Mengecek field yang ditentukan
            if (!empty($result[$fieldToCheck] && $result['status_validasi'] == "disetujui")) {
                return "sudah";
            } elseif ($result['status_validasi'] == "belum") {
                return "validasi";
            } elseif ($result['status'] == "izin" || $result['status'] == "sakit") {
                return "izin";
            } else {
                return "belum";
            }
        } else {
            return NULL;
        }
    }
}
