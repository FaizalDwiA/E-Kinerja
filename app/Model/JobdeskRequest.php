<?php

namespace BerkahSoloWeb\EKinerja\Model;

class JobdeskRequest
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
    public string $point;
    public string $status_validasi;
    public ?string $keterangan;
}
