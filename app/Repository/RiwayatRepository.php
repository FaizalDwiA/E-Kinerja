<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\Absensi;
use BerkahSoloWeb\EKinerja\Domain\Jobdesk;
use BerkahSoloWeb\EKinerja\Domain\Perizinan;
use BerkahSoloWeb\EKinerja\Domain\User;

class RiwayatRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }


    // Absensi

    public function UnconfirmedAbsensiWithUsernames($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT a.absensi_id, a.user_id, a.tanggal, a.check_in, a.check_out, a.status, a.bukti_gambar, a.status_validasi, a.terlambat, u.nama_lengkap 
                FROM absensi a
                JOIN users u ON a.user_id = u.user_id
                WHERE u.user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        try {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findBuktiGambarByAbsensiId(string $absensi_id): ?string
    {
        $statement = $this->connection->prepare("SELECT bukti_gambar FROM absensi WHERE absensi_id = :absensi_id");
        $statement->bindParam(':absensi_id', $absensi_id);
        $statement->execute();
        try {
            if ($row = $statement->fetch()) {
                return $row['bukti_gambar'];
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByAbsensiId(string $absensi_id): ?Absensi
    {
        $statement = $this->connection->prepare("
            SELECT absensi_id, user_id, check_in, check_out, status, bukti_gambar, terlambat, status_validasi 
            FROM absensi 
            WHERE absensi_id = ?
        ");
        $statement->execute([$absensi_id]);
        try {
            if ($row = $statement->fetch()) {
                $absensi = new Absensi();
                $absensi->absensi_id = $row['absensi_id'];
                $absensi->user_id = $row['user_id'];
                $absensi->check_in = $row['check_in'];
                $absensi->check_out = $row['check_out'];
                $absensi->status = $row['status'];
                $absensi->bukti_gambar = $row['bukti_gambar'];
                $absensi->terlambat = $row['terlambat'];
                $absensi->status_validasi = $row['status_validasi'];

                return $absensi;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function updateAbsensi(Absensi $absensi)
    {
        $stmt = $this->connection->prepare("
            UPDATE absensi 
            SET check_in = :check_in, check_out = :check_out, status = :status, bukti_gambar = :bukti_gambar, status_validasi = :status_validasi, terlambat = :terlambat
            WHERE absensi_id = :absensi_id
        ");

        $stmt->bindParam(':check_in', $absensi->check_in);
        $stmt->bindParam(':check_out', $absensi->check_out);
        $stmt->bindParam(':status', $absensi->status);
        $stmt->bindParam(':bukti_gambar', $absensi->bukti_gambar);
        $stmt->bindParam(':status_validasi', $absensi->status_validasi);
        $stmt->bindParam(':terlambat', $absensi->terlambat);
        $stmt->bindParam(':absensi_id', $absensi->absensi_id);

        $stmt->execute();
    }

    public function setujuAbsensi($absensi_id)
    {
        $stmt = $this->connection->prepare("UPDATE absensi SET status_validasi = 'disetujui' WHERE absensi_id = :absensi_id");
        $stmt->bindParam(':absensi_id', $absensi_id);
        $stmt->execute();
    }

    public function deleteAbsensi($absensi_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM absensi WHERE absensi_id = :absensi_id");
        $stmt->bindParam(':absensi_id', $absensi_id);
        $stmt->execute();
    }





    // Jobdesk

    public function insertJobdesk(Jobdesk $jobdesk)
    {
        $stmt = $this->connection->prepare("
        INSERT INTO jobdesk (user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, lampiran_url, point, status_validasi keterangan)
        VALUES (:user_id, :nama_jobdesk, :kategori, :tanggal, :mulai, :selesai, :status, :lampiran_url, :point, status_validasi, :keterangan)
    ");

        $stmt->bindParam(':user_id', $jobdesk->user_id);
        $stmt->bindParam(':nama_jobdesk', $jobdesk->nama_jobdesk);
        $stmt->bindParam(':kategori', $jobdesk->kategori);
        $stmt->bindParam(':tanggal', $jobdesk->tanggal);
        $stmt->bindParam(':mulai', $jobdesk->mulai);
        $stmt->bindParam(':selesai', $jobdesk->selesai);
        $stmt->bindParam(':status', $jobdesk->status);
        $stmt->bindParam(':lampiran_url', $jobdesk->lampiran_url);
        $stmt->bindParam(':point', $jobdesk->point);
        $stmt->bindParam(':status_validasi', $jobdesk->status_validasi);
        $stmt->bindParam(':keterangan', $jobdesk->keterangan);

        $stmt->execute();
    }

    public function findByJobdeskId(string $jobdesk_id): ?Jobdesk
    {
        $statement = $this->connection->prepare("
        SELECT jobdesk_id, user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, lampiran_url, point, keterangan 
        FROM jobdesk 
        WHERE jobdesk_id = ?
    ");
        $statement->execute([$jobdesk_id]);
        try {
            if ($row = $statement->fetch()) {
                $jobdesk = new Jobdesk();
                $jobdesk->jobdesk_id = $row['jobdesk_id'];
                $jobdesk->user_id = $row['user_id'];
                $jobdesk->nama_jobdesk = $row['nama_jobdesk'];
                $jobdesk->kategori = $row['kategori'];
                $jobdesk->tanggal = $row['tanggal'];
                $jobdesk->mulai = $row['mulai'];
                $jobdesk->selesai = $row['selesai'];
                $jobdesk->status = $row['status'];
                $jobdesk->lampiran_url = $row['lampiran_url'];
                $jobdesk->point = $row['point'];
                $jobdesk->keterangan = $row['keterangan'];

                return $jobdesk;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function unconfirmedJobdeskWithUsernames($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT j.jobdesk_id, j.user_id, j.nama_jobdesk, j.kategori, j.tanggal, j.mulai, j.selesai, j.status, j.lampiran_url, j.point, j.status_validasi, j.keterangan, u.nama_lengkap 
            FROM jobdesk j
            JOIN users u ON j.user_id = u.user_id
            WHERE j.status != 'disetujui' AND u.user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        try {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findLampiranUrlByJobdeskId(string $jobdesk_id): ?string
    {
        $statement = $this->connection->prepare("SELECT lampiran_url FROM jobdesk WHERE jobdesk_id = :jobdesk_id");
        $statement->bindParam(':jobdesk_id', $jobdesk_id);
        $statement->execute();
        try {
            if ($row = $statement->fetch()) {
                return $row['lampiran_url'];
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function updateJobdesk(Jobdesk $jobdesk)
    {
        $stmt = $this->connection->prepare("
    UPDATE jobdesk 
    SET nama_jobdesk = :nama_jobdesk, kategori = :kategori, tanggal = :tanggal, mulai = :mulai, selesai = :selesai, status = :status, lampiran_url = :lampiran_url, keterangan = :keterangan
    WHERE jobdesk_id = :jobdesk_id
");

        $stmt->bindParam(':nama_jobdesk', $jobdesk->nama_jobdesk);
        $stmt->bindParam(':kategori', $jobdesk->kategori);
        $stmt->bindParam(':tanggal', $jobdesk->tanggal);
        $stmt->bindParam(':mulai', $jobdesk->mulai);
        $stmt->bindParam(':selesai', $jobdesk->selesai);
        $stmt->bindParam(':status', $jobdesk->status);
        $stmt->bindParam(':lampiran_url', $jobdesk->lampiran_url);
        $stmt->bindParam(':keterangan', $jobdesk->keterangan);
        $stmt->bindParam(':jobdesk_id', $jobdesk->jobdesk_id);

        $stmt->execute();
    }

    public function setujuJobdesk($jobdesk_id)
    {
        $stmt = $this->connection->prepare("UPDATE jobdesk SET status = 'disetujui' WHERE jobdesk_id = :jobdesk_id");
        $stmt->bindParam(':jobdesk_id', $jobdesk_id);
        $stmt->execute();
    }

    public function deleteJobdesk($jobdesk_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM jobdesk WHERE jobdesk_id = :jobdesk_id");
        $stmt->bindParam(':jobdesk_id', $jobdesk_id);
        $stmt->execute();
    }




    // Perizinan

    public function UnconfirmedPerizinanWithUsernames($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT p.perizinan_id, p.user_id, p.tgl_mulai, p.tgl_selesai, p.jenis_izin, p.alasan, p.bukti_gambar, p.status_validasi, u.nama_lengkap 
            FROM perizinan p
            JOIN users u ON p.user_id = u.user_id
            WHERE u.user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        try {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findBuktiGambarByPerizinanId(string $perizinan_id): ?string
    {
        $statement = $this->connection->prepare("SELECT bukti_gambar FROM perizinan WHERE perizinan_id = :perizinan_id");
        $statement->bindParam(':perizinan_id', $perizinan_id);
        $statement->execute();
        try {
            if ($row = $statement->fetch()) {
                return $row['bukti_gambar'];
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByPerizinanId(string $perizinan_id): ?Perizinan
    {
        $statement = $this->connection->prepare("
        SELECT perizinan_id, user_id, tgl_mulai, tgl_selesai, jenis_izin, alasan, bukti_gambar, status_validasi 
        FROM perizinan 
        WHERE perizinan_id = ?
    ");
        $statement->execute([$perizinan_id]);
        try {
            if ($row = $statement->fetch()) {
                $perizinan = new Perizinan();
                $perizinan->perizinan_id = $row['perizinan_id'];
                $perizinan->user_id = $row['user_id'];
                $perizinan->tgl_mulai = $row['tgl_mulai'];
                $perizinan->tgl_selesai = $row['tgl_selesai'];
                $perizinan->jenis_izin = $row['jenis_izin'];
                $perizinan->alasan = $row['alasan'];
                $perizinan->bukti_gambar = $row['bukti_gambar'];
                $perizinan->status_validasi = $row['status_validasi'];

                return $perizinan;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function updatePerizinan(Perizinan $perizinan)
    {
        $stmt = $this->connection->prepare("
        UPDATE perizinan 
        SET tgl_mulai = :tgl_mulai, tgl_selesai = :tgl_selesai, jenis_izin = :jenis_izin, alasan = :alasan, bukti_gambar = :bukti_gambar
        WHERE perizinan_id = :perizinan_id
    ");

        $stmt->bindParam(':tgl_mulai', $perizinan->tgl_mulai);
        $stmt->bindParam(':tgl_selesai', $perizinan->tgl_selesai);
        $stmt->bindParam(':jenis_izin', $perizinan->jenis_izin);
        $stmt->bindParam(':alasan', $perizinan->alasan);
        $stmt->bindParam(':bukti_gambar', $perizinan->bukti_gambar);
        $stmt->bindParam(':perizinan_id', $perizinan->perizinan_id);

        $stmt->execute();
    }

    public function setujuPerizinan($perizinan_id)
    {
        $stmt = $this->connection->prepare("UPDATE perizinan SET status_validasi = 'disetujui' WHERE perizinan_id = :perizinan_id");
        $stmt->bindParam(':perizinan_id', $perizinan_id);
        $stmt->execute();
    }

    public function deletePerizinan($perizinan_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM perizinan WHERE perizinan_id = :perizinan_id");
        $stmt->bindParam(':perizinan_id', $perizinan_id);
        $stmt->execute();
    }
}
