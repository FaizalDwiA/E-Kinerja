<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\Gaji;
use BerkahSoloWeb\EKinerja\Domain\Riwayat_gaji;

class GajiRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $stmt = $this->connection->prepare("
        SELECT g.* 
        FROM gaji g
        INNER JOIN users u ON g.user_id = u.user_id
        WHERE u.role != 'pimpinan'
    ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findUserById($user_id)
    {
        $stmt = $this->connection->prepare("SELECT nama_lengkap FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result["nama_lengkap"];
    }

    public function findStatusPembayaranByUserId($userId)
    {
        $stmt = $this->connection->prepare("SELECT status_pembayaran FROM gaji WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['status_pembayaran'] ?? 0;
    }

    public function findBulanByUserId($userId)
    {
        $stmt = $this->connection->prepare("SELECT bulan FROM points WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['bulan'] ?? 0;
    }

    public function update(Gaji $gaji)
    {
        $stmt = $this->connection->prepare("
            UPDATE gaji SET
                user_id = :user_id,
                bulan = :bulan,
                tahun = :tahun,
                gaji_pokok = :gaji_pokok,
                tunjangan = :tunjangan,
                pemotongan = :pemotongan,
                gaji_total = :gaji_total,
                status_pembayaran = :status_pembayaran,
                updated_at = :updated_at
            WHERE gaji_id = :gaji_id
        ");
        $stmt->bindValue(':gaji_id', $gaji->gaji_id);
        $stmt->bindValue(':user_id', $gaji->user_id);
        $stmt->bindValue(':bulan', $gaji->bulan);
        $stmt->bindValue(':tahun', $gaji->tahun);
        $stmt->bindValue(':gaji_pokok', $gaji->gaji_pokok);
        $stmt->bindValue(':tunjangan', $gaji->tunjangan);
        $stmt->bindValue(':pemotongan', $gaji->pemotongan);
        $stmt->bindValue(':gaji_total', $gaji->gaji_total);
        $stmt->bindValue(':status_pembayaran', $gaji->status_pembayaran);
        $stmt->bindValue(':updated_at', $gaji->updated_at);
        $stmt->execute();
    }

    public function delete($gaji_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM gaji WHERE gaji_id = :gaji_id");
        $stmt->bindParam(':gaji_id', $gaji_id);
        $stmt->execute();
    }

    public function setuju($gaji_id)
    {
        $stmt = $this->connection->prepare("UPDATE gaji SET status_pembayaran = 'dibayar' WHERE gaji_id = :gaji_id");
        $stmt->bindParam(':gaji_id', $gaji_id);
        $stmt->execute();
    }

    public function batal($gaji_id)
    {
        $stmt = $this->connection->prepare("UPDATE gaji SET status_pembayaran = 'diproses' WHERE gaji_id = :gaji_id");
        $stmt->bindParam(':gaji_id', $gaji_id);
        $stmt->execute();
    }

    public function getTotalPoints($userId, $month)
    {
        $stmt = $this->connection->prepare("SELECT total_point FROM points WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['totalPoints'] ?? 0;
    }

    public function getAbsensiDeduction($userId, $month)
    {
        // Ambil data absensi untuk pengurangan
        $query = "SELECT COUNT(*) as totalTerlambat FROM absensi WHERE user_id = :user_id AND MONTH(tanggal) = :bulan AND status = 'Terlambat'";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':bulan', $month);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int)$result['totalTerlambat'];
    }

    public function findAllGajiRiwayat()
    {
        $stmt = $this->connection->prepare("SELECT * FROM riwayat_gaji  ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    // bismillah

    public function findAllUsers()
    {
        $stmt = $this->connection->prepare("SELECT * FROM users");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateGaji(Gaji $gaji)
    {
        $stmt = $this->connection->prepare("
        UPDATE gaji 
        SET 
            bulan = :bulan, 
            tahun = :tahun, 
            gaji_pokok = :gaji_pokok, 
            tunjangan = :tunjangan, 
            pemotongan = :pemotongan, 
            gaji_total = :gaji_total, 
            status_pembayaran = :status_pembayaran 
        WHERE 
            user_id = :user_id
    ");
        $stmt->bindValue(':user_id', $gaji->user_id);
        $stmt->bindValue(':bulan', $gaji->bulan);
        $stmt->bindValue(':tahun', $gaji->tahun);
        $stmt->bindValue(':gaji_pokok', $gaji->gaji_pokok);
        $stmt->bindValue(':tunjangan', $gaji->tunjangan);
        $stmt->bindValue(':pemotongan', $gaji->pemotongan);
        $stmt->bindValue(':gaji_total', $gaji->gaji_total);
        $stmt->bindValue(':status_pembayaran', $gaji->status_pembayaran);

        $result = $stmt->execute();
    }

    public function getJobdeskPoints($userId, $bulan, $tahun)
    {
        $query = "
            SELECT total_point
            FROM points
            WHERE user_id = :user_id
            AND bulan = :bulan AND tahun = :tahun
        ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result["total_point"] ?? 0;
    }

    public function getTotalTerlambat($userId, $bulan, $tahun)
    {
        $bulan_tahun = $bulan . "-" . $tahun;

        $query = "
            SELECT COUNT(*) as totalTerlambat
            FROM absensi
            WHERE user_id = :user_id
            AND DATE_FORMAT(tanggal, '%Y-%m') = :month
            AND terlambat = 'ya'
        ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':month', $bulan_tahun);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['totalTerlambat'] ?? 0;
    }

    public function getDistinctWorkingDays($bulan, $tahun)
    {
        $bulan_tahun = $tahun . '-' . $bulan;

        $query = "
            SELECT COUNT(DISTINCT DATE(tanggal)) as distinct_days
            FROM absensi
            WHERE DATE_FORMAT(tanggal, '%Y-%m') = :bulan_tahun;
        ";

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':bulan_tahun', $bulan_tahun);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['distinct_days'] ?? 0;
    }




    // Karyawan

    public function findGajiByUserDibayar($user_id, $status)
    {
        $status = "dibayar";
        $stmt = $this->connection->prepare("SELECT * FROM gaji WHERE user_id = :user_id AND status_pembayaran = :status_pembayaran");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':status_pembayaran', $status);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            $result = NULL;
        }

        return $result;
    }

    public function findGajiById($gaji_id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM gaji WHERE gaji_id = :gaji_id");
        $stmt->bindParam(':gaji_id', $gaji_id);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            $result = NULL;
        }

        return $result;
    }

    public function simpanGaji($gaji)
    {
        $gaji['status_pembayaran'] = "disimpan";

        $stmt = $this->connection->prepare(
            "INSERT INTO riwayat_gaji (
                user_id, tahun, bulan, gaji_pokok, tunjangan, pemotongan, gaji_total, status_pembayaran
            ) VALUES (
                :user_id, :tahun, :bulan, :gaji_pokok, :tunjangan, :pemotongan, :gaji_total, :status_pembayaran
            )
            "
        );

        $stmt->bindParam(':user_id', $gaji['user_id']);
        $stmt->bindParam(':tahun', $gaji['tahun']);
        $stmt->bindParam(':bulan', $gaji['bulan']);
        $stmt->bindParam(':gaji_pokok', $gaji['gaji_pokok']);
        $stmt->bindParam(':tunjangan', $gaji['tunjangan']);
        $stmt->bindParam(':pemotongan', $gaji['pemotongan']);
        $stmt->bindParam(':gaji_total', $gaji['gaji_total']);
        $stmt->bindParam(':status_pembayaran', $gaji['status_pembayaran']);

        $stmt->execute();
    }

    public function tolakGaji($gaji_id)
    {
        $stmt = $this->connection->prepare("UPDATE gaji SET status_pembayaran = 'diproses' WHERE gaji_id = :gaji_id");
        $stmt->bindParam(':gaji_id', $gaji_id);
        $stmt->execute();
    }

    public function restartGaji($gaji, $bulan, $tahun)
    {
        $status_pembayaran = "diproses";

        $stmt = $this->connection->prepare(
            "UPDATE gaji 
            SET 
                bulan = :bulan,
                tahun = :tahun,
                status_pembayaran = :status_pembayaran 
            WHERE 
                user_id = :user_id
            "
        );

        $stmt->bindValue(':user_id', $gaji['user_id']);
        $stmt->bindValue(':bulan', $bulan);
        $stmt->bindValue(':tahun', $tahun);
        $stmt->bindValue(':status_pembayaran', $status_pembayaran);

        $result = $stmt->execute();
    }

    public function restartPoints($gaji, $bulan, $tahun)
    {
        $jobdesk_point = 0;

        $stmt = $this->connection->prepare(
            "UPDATE points 
            SET 
                jobdesk_point = :jobdesk_point,
                bulan = :bulan,
                tahun = :tahun
            WHERE 
                user_id = :user_id
            "
        );

        $stmt->bindValue(':user_id', $gaji['user_id']);
        $stmt->bindValue(':jobdesk_point', $jobdesk_point);
        $stmt->bindValue(':bulan', $bulan);
        $stmt->bindValue(':tahun', $tahun);

        $stmt->execute();
    }

    public function findGajiRiwayatByUserId($user_id, $status)
    {
        $stmt = $this->connection->prepare("SELECT * FROM riwayat_gaji WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) {
            $result = NULL;
        }

        return $result;
    }

    public function findUserByHistoryId($user_id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function findRiwayatId($history_id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM riwayat_gaji WHERE history_id = :history_id");
        $stmt->bindParam(':history_id', $history_id);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }
}
