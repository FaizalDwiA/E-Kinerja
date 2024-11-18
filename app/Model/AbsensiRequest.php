<?php

namespace BerkahSoloWeb\EKinerja\Model;

class AbsensiRequest
{
    public int $absensi_id;
    public int $user_id;
    public string $tanggal;
    public string $jam;
    public string $check_in;
    public string $check_out;
    public string $status;
    public string $terlambat;
    public string $bukti_gambar;
    public string $status_validasi;
}
