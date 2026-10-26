<?php

namespace App\Controllers;

class Konfigurasi_tambahan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $defaults = [
            'audio_masuk_sukses' => [
                'file' => 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav',
                'desc' => 'Diputar saat siswa, guru, atau pegawai berhasil melakukan absen masuk tepat waktu.'
            ],
            'audio_pulang_sukses' => [
                'file' => 'audio_UUdXKzNPRzE2THZweGRTOWMvMnVFdz09.wav',
                'desc' => 'Diputar saat siswa, guru, atau pegawai berhasil melakukan absen pulang.'
            ],
            'audio_sudah_absen' => [
                'file' => 'popup.ogg',
                'desc' => 'Diputar jika pengguna sudah absen sebelumnya atau mencoba absen sebelum jam pulang.'
            ],
            'audio_terlambat' => [
                'file' => 'audio_Umhxc2ZDeHlpc1JpYWNIUVdzNG1sZz09.wav',
                'desc' => 'Diputar saat siswa, guru, atau pegawai berhasil absen masuk namun melewati batas toleransi keterlambatan.'
            ],
        ];

        $data = [
            'button'                    => 'Update',
            'action'                    => base_url('konfigurasi_tambahan/update_action'),
            'sett_apps'                 => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'konfigurasi_tambahan_data' => $this->db->table('konfigurasi_tambahan')->get()->getResult(),
            'defaults'                  => $defaults,
        ];
        return view('konfigurasi_tambahan/page', $data);
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $id_config = (int) $this->request->getPost('id_config');
        $configs = $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->get()->getRow();

        if (!$configs) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Konfigurasi tidak ditemukan']);
        }

        $file = $this->request->getFile('file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getClientExtension() ?: 'wav');
            $allowed = ['wav', 'mp3', 'ogg', 'm4a'];
            if (!in_array($ext, $allowed)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Format file tidak didukung. Harap upload audio berformat .wav, .mp3, atau .ogg'
                ]);
            }

            $targetDir = FCPATH . 'assets/audio/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Hapus file lama jika ada dan merupakan file custom
            if (!empty($configs->value) && file_exists($targetDir . $configs->value)) {
                @unlink($targetDir . $configs->value);
            }

            $name_sound = $file->getClientName();
            $newName = 'audio_' . encrypt_url($id_config) . '_' . time() . '.' . $ext;

            $file->move($targetDir, $newName, true);

            $data = [
                'value'      => $newName,
                'name_sound' => $name_sound,
            ];

            $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->update($data);

            return $this->response->setJSON([
                'status'     => 'ok',
                'message'    => 'Audio berhasil diunggah dan disimpan!',
                'name_sound' => $name_sound,
                'file_name'  => $newName,
                'audio_url'  => base_url('assets/audio/' . $newName)
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $file ? $file->getErrorString() : 'Pilih file audio terlebih dahulu'
        ]);
    }

    public function reset_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $id_config = (int) $this->request->getPost('id_config');
        $configs = $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->get()->getRow();

        if ($configs) {
            $targetDir = FCPATH . 'assets/audio/';
            if (!empty($configs->value) && file_exists($targetDir . $configs->value)) {
                @unlink($targetDir . $configs->value);
            }

            $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->update([
                'value'      => null,
                'name_sound' => null,
            ]);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Konfigurasi audio berhasil dikembalikan ke bawaan sistem'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Konfigurasi tidak ditemukan'
        ]);
    }
}