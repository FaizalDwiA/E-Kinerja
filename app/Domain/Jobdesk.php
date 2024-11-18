<?php

namespace BerkahSoloWeb\EKinerja\Domain;

class Jobdesk
{
    public int $jobdesk_id;
    public int $user_id;
    public string $nama_jobdesk;
    public string $kategori;
    public string $tanggal;
    public string $mulai;
    public string $selesai;
    public string $status;
    public string $lampiran_url;
    public ?int $point;
    public string $status_validasi;
    public ?string $keterangan;

    public string $nama_lengkap; // untuk laporan


    // public function __construct($jobdesk_id, $user_id, $nama_jobdesk, $kategori, $tgl_mulai, $tgl_selesai, $status, $lampiran_url, $keterangan = null)
    // {
    //     $this->jobdesk_id = $jobdesk_id;
    //     $this->user_id = $user_id;
    //     $this->nama_jobdesk = $nama_jobdesk;
    //     $this->kategori = $kategori;
    //     $this->tgl_mulai = $tgl_mulai;
    //     $this->tgl_selesai = $tgl_selesai;
    //     $this->status = $status;
    //     $this->lampiran_url = $lampiran_url;
    //     $this->keterangan = $keterangan;
    // }
}
