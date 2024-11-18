<?php

namespace BerkahSoloWeb\EKinerja\Model;

class RiwayatGajiRequest
{
    public int $history_id;
    public int $gaji_id;
    public int $user_id;
    public string $tahun;
    public string $bulan;
    public float $gaji_pokok;
    public float $tunjangan;
    public float $pemotongan;
    public float $gaji_total;
    public string $status_pembayaran;
    public string $created_at;
    public string $updated_at;
}
