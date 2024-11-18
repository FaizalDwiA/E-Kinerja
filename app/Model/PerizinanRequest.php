<?php

namespace BerkahSoloWeb\EKinerja\Model;

class PerizinanRequest
{
    public int $perizinan_id;
    public int $user_id;
    public string $jenis_izin;
    public string $tgl_mulai;
    public string $tgl_selesai;
    public string $alasan;
    public string $bukti_gambar;
    public string $status_validasi;
}
