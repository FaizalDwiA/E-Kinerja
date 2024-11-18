<?php

namespace BerkahSoloWeb\EKinerja\Domain;

class Gaji
{
    public $gaji_id;
    public $user_id;
    public $bulan;
    public $tahun;
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
