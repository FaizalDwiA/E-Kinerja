<?php

namespace BerkahSoloWeb\EKinerja\Domain;

class Absensi
{
    public int $absensi_id;
    public int $user_id;
    public string $tanggal;
    public ?string $check_in;
    public ?string $check_out;
    public string $jam;
    public string $status;
    public string $terlambat;
    public string $bukti_gambar;
    public string $status_validasi;

    public string $nama_lengkap; // untuk laporan

    // public function __construct($kehadiran_id, $user_id, $tanggal, $check_in, $check_out, $status)
    // {
    //     $this->kehadiran_id = $kehadiran_id;
    //     $this->user_id = $user_id;
    //     $this->tanggal = $tanggal;
    //     $this->check_in = $check_in;
    //     $this->check_out = $check_out;
    //     $this->status = $status;
    // }
}
