<?php

namespace BerkahSoloWeb\EKinerja\Domain;

class RiwayatGaji
{
    public $history_id;
    public $user_id;
    public $tahun;
    public $bulan;
    public $gaji_pokok;
    public $tunjangan;
    public $pemotongan;
    public $gaji_total;
    public $status_pembayaran;
    public $created_at;
    public $updated_at;

    // nama lengkap
    public $nama_lengkap;
}
