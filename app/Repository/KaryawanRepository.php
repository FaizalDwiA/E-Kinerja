<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\Absensi;
use BerkahSoloWeb\EKinerja\Domain\Jobdesk;
use BerkahSoloWeb\EKinerja\Domain\User;
use BerkahSoloWeb\EKinerja\Domain\Perizinan;

class KaryawanRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }



    // Master Karyawan

    public function getAllKaryawan()
    {
        $stmt = $this->connection->prepare("SELECT * FROM users");
        $stmt->execute();

        try {
            $result = $stmt->fetchAll();

            $absensiArray = []; // Array untuk menyimpan objek Absensi

            foreach ($result as $row) {
                // Buat objek baru untuk menyimpan data
                $user = new User();
                $user->user_id = $row['user_id'];
                $user->username = $row['username'];
                $user->email = $row['email'];
                $user->nama_lengkap = $row['nama_lengkap'];
                $user->alamat = $row['alamat'];
                $user->jabatan = $row['jabatan'];
                $user->foto_profil = $row['foto_profil'];
                $user->wa = $row['wa'];
                $user->role = $row['role'];
                $user->status = $row['status'];
                $user->catatan = $row['catatan'] ?? null; // Mengatur nilai default untuk catatan jika null
                $user->tgl_lahir = $row['tgl_lahir'] ?? null;

                // Tambahkan objek user ke dalam array
                $userArray[] = $user;
            }

            return $userArray; // Kembalikan array yang berisi objek Absensi
        } finally {
            $stmt->closeCursor();
        }
    }

    public function saveKaryawanBaru(User $user)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO users (username, password) 
            VALUES (:username, :password)"
        );

        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':password', $user->password);

        $stmt->execute();
        // Get the last inserted user_id
        $user->user_id = $this->connection->lastInsertId();

        return $user;
    }

    public function findByUserId(string $user_id): ?User
    {
        $statement = $this->connection->prepare("SELECT user_id, username, email, password, nama_lengkap, alamat, jabatan, foto_profil, wa, catatan, tgl_lahir FROM users WHERE user_id = ?");
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
                $user->catatan = $row['catatan'];
                $user->tgl_lahir = $row['tgl_lahir'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function getFotoProfilByUserId($user_id)
    {
        $stmt = $this->connection->prepare("SELECT foto_profil FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function updateKaryawan(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET username = ?, email = ?, password = ?, nama_lengkap = ?, alamat = ?, jabatan = ?, foto_profil = ?, wa = ?, catatan = ?, tgl_lahir = ? WHERE user_id = ?");
        $statement->execute([
            $user->username,
            $user->email,
            $user->password,
            $user->nama_lengkap,
            $user->alamat,
            $user->jabatan,
            $user->foto_profil,
            $user->wa,
            $user->catatan,
            $user->tgl_lahir,
            $user->user_id
        ]);

        // $rowsAffected = $statement->rowCount(); //debugging

        return $user;
    }

    public function deleteKaryawan($user_id)
    {
        try {
            $this->connection->beginTransaction();

            // Hapus data terkait di tabel yang memiliki foreign key ke tabel `users`
            $stmt = $this->connection->prepare("DELETE FROM sessions WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM points WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM gaji WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM absensi WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM jobdesk WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Terakhir, hapus data di tabel `users`
            $stmt = $this->connection->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $this->connection->commit();
        } catch (\PDO $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function UnconfirmedAbsensiWithUsernames()
    {
        $stmt = $this->connection->prepare(
            "SELECT a.absensi_id, a.user_id, a.tanggal, a.check_in, a.check_out, a.status, a.bukti_gambar, a.status_validasi, a.terlambat, u.nama_lengkap 
                FROM absensi a
                JOIN users u ON a.user_id = u.user_id
                WHERE a.status_validasi != 'disetujui'"
        );
        $stmt->execute();
        try {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } finally {
            $stmt->closeCursor();
        }
    }



    // Absensi

    public function findById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM absensi WHERE absensi_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        try {
            return $stmt->fetchObject('BerkahSoloWeb\EKinerja\Domain\Absensi');
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findUsernameById($id)
    {
        $stmt = $this->connection->prepare("SELECT username FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        try {
            return $stmt->fetchObject('BerkahSoloWeb\EKinerja\Domain\User');
        } finally {
            $stmt->closeCursor();
        }
    }

    public function findAbsensiId($userId, $tgl)
    {
        $stmt = $this->connection->prepare("
            SELECT absensi_id FROM `absensi` WHERE user_id = :user_id and tanggal = :tanggal
        ");

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':tanggal', $tgl);

        $stmt->execute();

        // Fetch the result as an associative array
        $absensi = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Return the absensi_id if found, otherwise return null or false
        return $absensi ? $absensi['absensi_id'] : null;
    }

    public function findCheckInForDate($userId, $date)
    {
        // Query to get the check-in record for the given user and date
        $query = "SELECT * FROM absensi WHERE user_id = :user_id AND tanggal = :tanggal AND status = 'masuk' LIMIT 1";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'user_id' => $userId,
            'tanggal' => $date
        ]);

        return $statement->fetch();
    }

    public function saveCheckIn(Absensi $absensi)
    {
        $stmt = $this->connection->prepare("
            INSERT INTO absensi (user_id, tanggal, check_in, status, bukti_gambar, status_validasi, terlambat) 
            VALUES (:user_id, :tanggal, :check_in, :status, :bukti_gambar, :status_validasi, :terlambat)
        ");

        $stmt->bindParam(':user_id', $absensi->user_id);
        $stmt->bindParam(':tanggal', $absensi->tanggal);
        $stmt->bindParam(':check_in', $absensi->jam);
        $stmt->bindParam(':status', $absensi->status);
        $stmt->bindParam(':bukti_gambar', $absensi->bukti_gambar);
        $stmt->bindParam(':status_validasi', $absensi->status_validasi);
        $stmt->bindParam(':terlambat', $absensi->terlambat);

        $stmt->execute();

        return $absensi;
    }

    public function saveCheckOut(Absensi $absensi)
    {
        $stmt = $this->connection->prepare(
            "UPDATE absensi 
            SET check_out = :check_out
            WHERE user_id = :user_id AND tanggal = :tanggal AND absensi_id = :absensi_id"
        );

        $stmt->bindParam(':absensi_id', $absensi->absensi_id);
        $stmt->bindParam(':user_id', $absensi->user_id);
        $stmt->bindParam(':tanggal', $absensi->tanggal);
        $stmt->bindParam(':check_out', $absensi->jam);

        $stmt->execute();

        return $absensi;
    }




    // Jobdesk

    public function saveJobdesk(Jobdesk $jobdesk)
    {
        $stmt = $this->connection->prepare("INSERT INTO jobdesk (user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, point, lampiran_url, keterangan) 
                                            VALUES (:user_id, :nama_jobdesk, :kategori, :tanggal, :mulai, :selesai, :status, :point, :lampiran_url, :keterangan)");
        $stmt->bindParam(':user_id', $jobdesk->user_id);
        $stmt->bindParam(':nama_jobdesk', $jobdesk->nama_jobdesk);
        $stmt->bindParam(':kategori', $jobdesk->kategori);
        $stmt->bindParam(':tanggal', $jobdesk->tanggal);
        $stmt->bindParam(':mulai', $jobdesk->mulai);
        $stmt->bindParam(':selesai', $jobdesk->selesai);
        $stmt->bindParam(':status', $jobdesk->status);
        $stmt->bindParam(':point', $jobdesk->point);
        $stmt->bindParam(':lampiran_url', $jobdesk->lampiran_url);
        $stmt->bindParam(':keterangan', $jobdesk->keterangan);
        $stmt->execute();
        // $jobdesk->jobdesk_id = $this->connection->lastInsertId();
        return $jobdesk;
    }

    public function findAllJobdesk()
    {
        $stmt = $this->connection->prepare("SELECT jobdesk_id, user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, lampiran_url FROM jobdesk");
        $stmt->execute();
        try {
            $result = $stmt->fetchAll();

            $jobdeskArray = array(); // Array untuk menyimpan objek Jobdesk

            foreach ($result as $row) {
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
                $jobdesk->keterangan = $row['keterangan'] ?? null;

                $jobdeskArray[] = $jobdesk; // Tambahkan objek Jobdesk ke dalam array
            }

            return $jobdeskArray; // Kembalikan array yang berisi objek Jobdesk
        } finally {
            $stmt->closeCursor();
        }
    }

    public function getPoin($kategori)
    {
        // Tentukan nilai point berdasarkan kategori
        $kategoriToPoin = [
            'desain-web' => 10,
            'website' => 10,
            'maintanance-website' => 10,
            'post-artikel' => 5,
            'share-link' => 5,
            'list-seo' => 5
        ];

        // Kembalikan nilai point berdasarkan kategori, default ke 0 jika kategori tidak ditemukan
        return $kategoriToPoin[$kategori] ?? 0;
    }



    // Perizinan

    public function savePerizinan(Perizinan $perizinan)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO perizinan (user_id, jenis_izin, tgl_mulai, tgl_selesai, alasan, bukti_gambar) 
            VALUES (:user_id, :jenis_izin, :tgl_mulai, :tgl_selesai, :alasan, :bukti_gambar)"
        );
        $stmt->bindParam(':user_id', $perizinan->user_id);
        $stmt->bindParam(':jenis_izin', $perizinan->jenis_izin);
        $stmt->bindParam(':tgl_mulai', $perizinan->tgl_mulai);
        $stmt->bindParam(':tgl_selesai', $perizinan->tgl_selesai);
        $stmt->bindParam(':alasan', $perizinan->alasan);
        $stmt->bindParam(':bukti_gambar', $perizinan->bukti_gambar);
        $stmt->execute();
        return $perizinan;
    }

    public function saveAbsensiPerizinan(Absensi $absensi)
    {
        $stmt = $this->connection->prepare("
            INSERT INTO absensi (user_id, tanggal, status, status_validasi, terlambat) 
            VALUES (:user_id, :tanggal, :status, :status_validasi, :terlambat)
        ");

        $stmt->bindParam(':user_id', $absensi->user_id);
        $stmt->bindParam(':tanggal', $absensi->tanggal);
        $stmt->bindParam(':status', $absensi->status);
        $stmt->bindParam(':status_validasi', $absensi->status_validasi);
        $stmt->bindParam(':terlambat', $absensi->terlambat);

        $stmt->execute();

        return $absensi;
    }



    // Konfirmasi Absensi

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



    // Konfirmasi Jobdesk

    public function insertJobdesk(Jobdesk $jobdesk)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO jobdesk (user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, lampiran_url, point, status_validasi keterangan)
            VALUES (:user_id, :nama_jobdesk, :kategori, :tanggal, :mulai, :selesai, :status, :lampiran_url, :point, status_validasi, :keterangan)"
        );

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
        $statement = $this->connection->prepare(
            "SELECT jobdesk_id, user_id, nama_jobdesk, kategori, tanggal, mulai, selesai, status, lampiran_url, point, keterangan 
            FROM jobdesk 
            WHERE jobdesk_id = ?"
        );
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

    public function unconfirmedJobdeskWithUsernames()
    {
        $stmt = $this->connection->prepare(
            "SELECT j.jobdesk_id, j.user_id, j.nama_jobdesk, j.kategori, j.tanggal, j.mulai, j.selesai, j.status, j.lampiran_url, j.point, j.status_validasi, j.keterangan, u.nama_lengkap 
            FROM jobdesk j
            JOIN users u ON j.user_id = u.user_id
            WHERE j.status != 'disetujui'"
        );

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
        $stmt = $this->connection->prepare(
            "UPDATE jobdesk 
            SET nama_jobdesk = :nama_jobdesk, kategori = :kategori, tanggal = :tanggal, mulai = :mulai, selesai = :selesai, status = :status, lampiran_url = :lampiran_url, point = :point, keterangan = :keterangan
            WHERE jobdesk_id = :jobdesk_id"
        );

        $stmt->bindParam(':nama_jobdesk', $jobdesk->nama_jobdesk);
        $stmt->bindParam(':kategori', $jobdesk->kategori);
        $stmt->bindParam(':tanggal', $jobdesk->tanggal);
        $stmt->bindParam(':mulai', $jobdesk->mulai);
        $stmt->bindParam(':selesai', $jobdesk->selesai);
        $stmt->bindParam(':status', $jobdesk->status);
        $stmt->bindParam(':lampiran_url', $jobdesk->lampiran_url);
        $stmt->bindParam(':point', $jobdesk->point);
        $stmt->bindParam(':keterangan', $jobdesk->keterangan);
        $stmt->bindParam(':jobdesk_id', $jobdesk->jobdesk_id);

        $stmt->execute();
    }

    public function setujuJobdesk($jobdesk_id)
    {
        $stmt = $this->connection->prepare("UPDATE jobdesk SET status_validasi = 'disetujui' WHERE jobdesk_id = :jobdesk_id");
        $stmt->bindParam(':jobdesk_id', $jobdesk_id);
        $stmt->execute();
    }

    public function deleteJobdesk($jobdesk_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM jobdesk WHERE jobdesk_id = :jobdesk_id");
        $stmt->bindParam(':jobdesk_id', $jobdesk_id);
        $stmt->execute();
    }



    // Konfirmasi Perizinan

    public function UnconfirmedPerizinanWithUsernames()
    {
        $stmt = $this->connection->prepare(
            "SELECT p.perizinan_id, p.user_id, p.tgl_mulai, p.tgl_selesai, p.jenis_izin, p.alasan, p.bukti_gambar, p.status_validasi, u.nama_lengkap 
            FROM perizinan p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.status_validasi != 'disetujui'"
        );
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
        $statement = $this->connection->prepare(
            "SELECT perizinan_id, user_id, tgl_mulai, tgl_selesai, jenis_izin, alasan, bukti_gambar, status_validasi 
            FROM perizinan 
            WHERE perizinan_id = ?"
        );
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
        $stmt = $this->connection->prepare(
            "UPDATE perizinan 
            SET tgl_mulai = :tgl_mulai, tgl_selesai = :tgl_selesai, jenis_izin = :jenis_izin, alasan = :alasan, bukti_gambar = :bukti_gambar, status_validasi = :status_validasi
            WHERE perizinan_id = :perizinan_id"
        );

        $stmt->bindParam(':tgl_mulai', $perizinan->tgl_mulai);
        $stmt->bindParam(':tgl_selesai', $perizinan->tgl_selesai);
        $stmt->bindParam(':jenis_izin', $perizinan->jenis_izin);
        $stmt->bindParam(':alasan', $perizinan->alasan);
        $stmt->bindParam(':bukti_gambar', $perizinan->bukti_gambar);
        $stmt->bindParam(':status_validasi', $perizinan->status_validasi);
        $stmt->bindParam(':perizinan_id', $perizinan->perizinan_id);

        $stmt->execute();
    }

    public function PerizinanById($perizinan_id)
    {
        $stmt = $this->connection->prepare("SELECT tgl_mulai, tgl_selesai, jenis_izin FROM perizinan WHERE perizinan_id = :perizinan_id");
        $stmt->bindParam(':perizinan_id', $perizinan_id);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
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
