<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findJumlahData()
    {
        $stmt = $this->connection->prepare("SELECT 
            SUM(CASE WHEN a.status = 'masuk' THEN 1 ELSE 0 END) AS jumlah_masuk,
            SUM(CASE WHEN a.status = 'izin' THEN 1 ELSE 0 END) AS jumlah_izin,
            SUM(CASE WHEN a.status = 'sakit' THEN 1 ELSE 0 END) AS jumlah_sakit
        FROM absensi a");

        $stmt->execute();

        try {
            $result = $stmt->fetch();
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findJumlahJobdesk($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT 
            COUNT(jobdesk_id) AS jumlah_jobdesk
        FROM jobdesk
        WHERE user_id = :user_id"
        );

        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        try {
            $result = $stmt->fetch();
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET username = ?, email = ?, password = ?, nama_lengkap = ?, alamat = ?, jabatan = ?, foto_profil = ?, wa = ?, tgl_lahir = ?, catatan = ? WHERE user_id = ?");
        $statement->execute([
            $user->username,
            $user->email,
            $user->password,
            $user->nama_lengkap,
            $user->alamat,
            $user->jabatan,
            $user->foto_profil,
            $user->wa,
            $user->tgl_lahir,
            $user->catatan,
            $user->user_id
        ]);

        // $rowsAffected = $statement->rowCount(); //debugging

        return $user;
    }

    public function findByUsername(string $username): ?User
    {
        $statement = $this->connection->prepare("SELECT user_id, username, email, password, nama_lengkap, alamat, jabatan, foto_profil, wa, role, tgl_lahir, catatan FROM users WHERE username = ?");
        $statement->execute([$username]);
        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->user_id = $row['user_id'];
                $user->username = $row['username'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->nama_lengkap = $row['nama_lengkap'];
                $user->alamat = $row['alamat'];
                $user->jabatan = $row['jabatan'];
                $user->foto_profil = $row['foto_profil'];
                $user->wa = $row['wa'];
                $user->role = $row['role'];
                $user->tgl_lahir = $row['tgl_lahir'];
                $user->catatan = $row['catatan'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByUserId(string $user_id): ?User
    {
        $statement = $this->connection->prepare("SELECT user_id, username, email, password, nama_lengkap, alamat, jabatan, foto_profil, wa, role, tgl_lahir, catatan FROM users WHERE user_id = ?");
        $statement->execute([$user_id]);
        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->user_id = $row['user_id'];
                $user->username = $row['username'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->nama_lengkap = $row['nama_lengkap'];
                $user->alamat = $row['alamat'];
                $user->jabatan = $row['jabatan'];
                $user->foto_profil = $row['foto_profil'];
                $user->wa = $row['wa'];
                $user->role = $row['role'];
                $user->tgl_lahir = $row['tgl_lahir'];
                $user->catatan = $row['catatan'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM Users");
    }
}
