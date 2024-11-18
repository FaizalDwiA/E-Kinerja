<?php

namespace BerkahSoloWeb\EKinerja\Repository;

use BerkahSoloWeb\EKinerja\Domain\User;
use BerkahSoloWeb\EKinerja\Domain\Jobdesk;
use BerkahSoloWeb\EKinerja\Domain\Absensi;
use BerkahSoloWeb\EKinerja\Domain\Gaji;
use BerkahSoloWeb\EKinerja\Domain\Perizinan;

class LaporanRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAllUsers()
    {
        $statement = $this->connection->prepare("SELECT user_id, username, email, password, nama_lengkap, alamat, jabatan, foto_profil, wa, status, tgl_lahir, catatan FROM users");
        $statement->execute();

        try {
            $users = [];
            foreach ($statement as $row) {
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
                $user->status = $row['status'];
                $user->tgl_lahir = $row['tgl_lahir'];
                $user->catatan = $row['catatan'];
                $users[] = $user;
            }
            return $users;
        } finally {
            $statement->closeCursor();
        }
    }

    public function findAllAbsensi()
    {
        $statement = $this->connection->prepare("
        SELECT 
            absensi.absensi_id, 
            absensi.user_id, 
            absensi.tanggal, 
            absensi.check_in, 
            absensi.check_out, 
            absensi.status, 
            absensi.bukti_gambar, 
            absensi.status_validasi, 
            absensi.terlambat,
            users.nama_lengkap
        FROM absensi
        JOIN users ON absensi.user_id = users.user_id
    ");
        $statement->execute();

        try {
            $absensiList = [];
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $absensi = new Absensi();
                $absensi->absensi_id = $row['absensi_id'];
                $absensi->user_id = $row['user_id'];
                $absensi->tanggal = $row['tanggal'];
                $absensi->check_in = $row['check_in'];
                $absensi->check_out = $row['check_out'];
                $absensi->status = $row['status'];
                $absensi->bukti_gambar = $row['bukti_gambar'];
                $absensi->status_validasi = $row['status_validasi'];
                $absensi->terlambat = $row['terlambat'];

                // Tambahkan properti nama_lengkap ke objek Absensi
                $absensi->nama_lengkap = $row['nama_lengkap'];

                // Masukkan objek Absensi ke dalam array
                $absensiList[] = $absensi;
            }
            return $absensiList;
        } finally {
            $statement->closeCursor();
        }
    }


    public function findAllJobdesk()
    {
        $statement = $this->connection->prepare("
        SELECT 
            jobdesk.user_id, 
            jobdesk.nama_jobdesk, 
            jobdesk.kategori, 
            jobdesk.tanggal, 
            jobdesk.mulai, 
            jobdesk.selesai, 
            jobdesk.status, 
            jobdesk.lampiran_url, 
            jobdesk.point, 
            jobdesk.status_validasi, 
            jobdesk.keterangan,
            users.nama_lengkap
        FROM jobdesk
        JOIN users ON jobdesk.user_id = users.user_id
    ");
        $statement->execute();

        try {
            $jobdesks = [];
            foreach ($statement as $row) {
                $jobdesk = new Jobdesk();
                $jobdesk->user_id = $row['user_id'];
                $jobdesk->nama_jobdesk = $row['nama_jobdesk'];
                $jobdesk->kategori = $row['kategori'];
                $jobdesk->tanggal = $row['tanggal'];
                $jobdesk->mulai = $row['mulai'];
                $jobdesk->selesai = $row['selesai'];
                $jobdesk->status = $row['status'];
                $jobdesk->point = $row['point'];
                $jobdesk->status_validasi = $row['status_validasi'];
                $jobdesk->lampiran_url = $row['lampiran_url'];
                $jobdesk->keterangan = $row['keterangan'];

                // Tambahkan properti nama_lengkap ke objek Jobdesk
                $jobdesk->nama_lengkap = $row['nama_lengkap'];

                // Masukkan objek Jobdesk ke dalam array
                $jobdesks[] = $jobdesk;
            }
            return $jobdesks;
        } finally {
            $statement->closeCursor();
        }
    }

    public function findAllPerizinan()
    {
        $statement = $this->connection->prepare("
    SELECT 
        perizinan.user_id, 
        perizinan.jenis_izin, 
        perizinan.tgl_mulai, 
        perizinan.tgl_selesai, 
        perizinan.alasan, 
        perizinan.bukti_gambar, 
        perizinan.status_validasi,
        users.nama_lengkap
    FROM perizinan
    JOIN users ON perizinan.user_id = users.user_id
    ");
        $statement->execute();

        try {
            $perizinans = [];
            foreach ($statement as $row) {
                $perizinan = new Perizinan();
                $perizinan->user_id = (int) $row['user_id'];
                $perizinan->jenis_izin = $row['jenis_izin'];
                $perizinan->tgl_mulai = $row['tgl_mulai'];
                $perizinan->tgl_selesai = $row['tgl_selesai'];
                $perizinan->alasan = $row['alasan'];
                $perizinan->bukti_gambar = $row['bukti_gambar'];
                $perizinan->status_validasi = $row['status_validasi'];
                $perizinan->nama_lengkap = $row['nama_lengkap'];

                // Masukkan objek Perizinan ke dalam array
                $perizinans[] = $perizinan;
            }
            return $perizinans;
        } finally {
            $statement->closeCursor();
        }
    }

    public function findAllGaji()
    {
        $statement = $this->connection->prepare("
        SELECT 
            riwayat_gaji.bulan,
            riwayat_gaji.tahun,
            riwayat_gaji.gaji_pokok,
            riwayat_gaji.tunjangan,
            riwayat_gaji.pemotongan,
            riwayat_gaji.gaji_total,
            users.nama_lengkap
        FROM riwayat_gaji
        JOIN users ON riwayat_gaji.user_id = users.user_id
    ");
        $statement->execute();

        try {
            $result = [];
            foreach ($statement as $row) {
                $gaji = new Gaji();
                $gaji->bulan = $row['bulan'];
                $gaji->tahun = $row['tahun'];
                $gaji->gaji_pokok = $row['gaji_pokok'];
                $gaji->tunjangan = $row['tunjangan'];
                $gaji->pemotongan = $row['pemotongan'];
                $gaji->gaji_total = $row['gaji_total'];

                // Tambahkan properti nama_lengkap ke objek Gaji
                $gaji->nama_lengkap = $row['nama_lengkap'];

                // Masukkan objek Gaji ke dalam array
                $result[] = $gaji;
            }
            return $result;
        } finally {
            $statement->closeCursor();
        }
    }




    // Bismillah

    public function findUserById($user_id)
    {
        $stmt = $this->connection->prepare("SELECT nama_lengkap FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result["nama_lengkap"];
    }
}
