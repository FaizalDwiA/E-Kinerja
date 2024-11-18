<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\User;

class HomeRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    // Pimpinan
    public function findTotalKaryawan()
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS total_karyawan FROM users WHERE role = 'karyawan'");

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Mengembalikan nilai total_karyawan sebagai string
        return $result['total_karyawan'];
    }

    public function findKehadiranBulananSemuaKaryawan($year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("
        SELECT COUNT(DISTINCT tanggal) AS total_hari, 
            (
                SELECT COUNT(tanggal) 
                FROM absensi
                WHERE YEAR(tanggal) = :year
                    AND MONTH(tanggal) = :month
                    AND (status != 'terlambat' AND status != 'alpha' AND status != 'sakit') 
            ) AS hari_masuk,
            (
                SELECT COUNT(DISTINCT user_id) 
                FROM absensi
                WHERE YEAR(tanggal) = :year
                    AND MONTH(tanggal) = :month
            ) AS total_karyawan
        FROM absensi
        WHERE YEAR(tanggal) = :year
            AND MONTH(tanggal) = :month
    ");

        // Mengikat parameter
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function findTotalGaji($year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("SELECT SUM(gaji_total) AS total_gaji
            FROM gaji;
            WHERE YEAR(tanggal) = :year
                AND MONTH(tanggal) = :month
        ");

        // Mengikat parameter
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['total_gaji'];
    }

    public function findTotalPerizinan($year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("SELECT COUNT(perizinan_id) AS total_perizinan
            FROM perizinan;
            WHERE YEAR(tanggal) = :year
                AND MONTH(tanggal) = :month
        ");

        // Mengikat parameter
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['total_perizinan'];
    }

    public function findKehadiranTahunanBulananSemuaKaryawan($year)
    {
        $stmt = $this->connection->prepare("
        SELECT m.bulan,
            IFNULL(t.total_hari, 0) AS total_hari, 
            IFNULL(t.hari_masuk, 0) AS hari_masuk,
            IFNULL(t.total_karyawan, 0) AS total_karyawan
        FROM (
            SELECT 1 AS bulan UNION ALL
            SELECT 2 UNION ALL
            SELECT 3 UNION ALL
            SELECT 4 UNION ALL
            SELECT 5 UNION ALL
            SELECT 6 UNION ALL
            SELECT 7 UNION ALL
            SELECT 8 UNION ALL
            SELECT 9 UNION ALL
            SELECT 10 UNION ALL
            SELECT 11 UNION ALL
            SELECT 12
        ) m
        LEFT JOIN (
            SELECT MONTH(tanggal) AS bulan,
                COUNT(DISTINCT DATE(tanggal)) AS total_hari, 
                SUM(CASE WHEN status != 'terlambat' AND status != 'alpha' AND status != 'sakit' THEN 1 ELSE 0 END) AS hari_masuk,
                COUNT(DISTINCT user_id) AS total_karyawan
            FROM absensi
            WHERE YEAR(tanggal) = :year
            GROUP BY MONTH(tanggal)
        ) t ON m.bulan = t.bulan
        ORDER BY m.bulan
    ");

        $stmt->bindParam(':year', $year);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findKategoriByUserAll($year, $month)
    {
        // Menyiapkan statement untuk menghitung jumlah data per kategori
        $stmt = $this->connection->prepare("
            SELECT kategori, COUNT(*) as jumlah
            FROM jobdesk
            WHERE YEAR(tanggal) = :year
                AND MONTH(tanggal) = :month
            GROUP BY kategori
        ");

        // Mengikat parameter
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }







    // Karyawan
    public function findPoints(string $user_id): ?string
    {
        $statement = $this->connection->prepare("SELECT total_point FROM points WHERE user_id = :user_id");
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        try {
            if ($row = $statement->fetch()) {
                return $row['total_point'];
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findKehadiranBulanan($userId, $year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("
            SELECT COUNT(DISTINCT DATE(tanggal)) AS total_hari, 
                SUM(status = 'masuk') AS hari_masuk
            FROM absensi
            WHERE user_id = :user_id
                AND YEAR(tanggal) = :year
                AND MONTH(tanggal) = :month
        ");

        // Mengikat parameter
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findStatusAbsensiByUser(string $user_id): array
    {
        $statement = $this->connection->prepare("SELECT status FROM absensi WHERE user_id = :user_id");
        $statement->bindParam(':user_id', $user_id, \PDO::PARAM_STR);

        try {
            $statement->execute();
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC); // Mengambil semua baris hasil

            // Mengembalikan array dari status yang ditemukan
            return $rows;
        } catch (\PDOException $e) {
            // Menangani exception dan mencatat kesalahan jika perlu
            error_log('Database query error: ' . $e->getMessage());
            return []; // Mengembalikan array kosong jika terjadi kesalahan
        } finally {
            $statement->closeCursor(); // Menutup cursor
        }
    }

    public function findPerizinanByUser($user_id, $year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) as perizinan_total
            FROM perizinan
            WHERE user_id = :user_id
                AND YEAR(tgl_mulai) = :year
                AND MONTH(tgl_mulai) = :month
        "
        );

        // Mengikat parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['perizinan_total'];
    }

    public function findGajiByUser($user_id, $year, $month)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("SELECT gaji_total
            FROM gaji
            WHERE user_id = :user_id
                AND tahun = :year
                AND bulan = :month
        ");

        // Mengikat parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['gaji_total'];
    }

    // Repository: GajiRepository.php
    public function findGajiPerBulan($user_id, $year)
    {
        // Menyiapkan statement
        $stmt = $this->connection->prepare("
        SELECT bulan, gaji_total
        FROM riwayat_gaji
        WHERE user_id = :user_id
            AND tahun = :year
        GROUP BY bulan
        ORDER BY bulan
    ");

        // Mengikat parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }


    public function findKategoriByUser($user_id, $year, $month)
    {
        // Menyiapkan statement untuk menghitung jumlah data per kategori
        $stmt = $this->connection->prepare("
            SELECT kategori, COUNT(*) as jumlah
            FROM jobdesk
            WHERE user_id = :user_id
                AND YEAR(tanggal) = :year
                AND MONTH(tanggal) = :month
            GROUP BY kategori
        ");

        // Mengikat parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);

        // Mengeksekusi statement
        $stmt->execute();

        // Mengambil hasil
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
