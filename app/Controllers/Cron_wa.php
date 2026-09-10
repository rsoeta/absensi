<?php

namespace App\Controllers;

class Cron_wa extends BaseController
{
    public function process()
    {
        $db = \Config\Database::connect();

        // Ganti dengan token Fonnte Anda yang sebenarnya
        $token_fonnte = "TOKEN_FONNTE_KANG_RIAN_DISINI";

        // Ambil maksimal 10 pesan yang masih mengantre
        $antrean = $db->table('tabel_antrean_wa')->where('status', 'pending')->limit(10)->get()->getResult();

        if (empty($antrean)) {
            return "Tidak ada antrean pesan WhatsApp.";
        }

        foreach ($antrean as $data) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $data->no_hp,
                    'message' => $data->pesan,
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token_fonnte"
                ),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            // Tandai pesan sebagai terkirim atau gagal agar tidak dieksekusi ulang
            $status_update = ($httpcode == 200) ? 'terkirim' : 'gagal';
            $db->table('tabel_antrean_wa')->where('id', $data->id)->update(['status' => $status_update]);

            // JEDA ANTI-BLOKIR (Sangat Penting)
            sleep(5);
        }

        return "Selesai memproses " . count($antrean) . " pesan.";
    }
}
