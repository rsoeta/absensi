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

        $data = [
            'button'                    => 'Update',
            'action'                    => base_url('konfigurasi_tambahan/update_action'),
            'sett_apps'                 => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'konfigurasi_tambahan_data' => $this->db->table('konfigurasi_tambahan')->get()->getResult(),
        ];
        return view('konfigurasi_tambahan/page', $data);
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $id_config = $this->request->getPost('id_config');
        $configs = $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->get()->getRow();

        $file = $this->request->getFile('file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus file lama jika ada
            if ($configs->value && file_exists('assets/audio/' . $configs->value)) {
                unlink('assets/audio/' . $configs->value);
            }

            $name_sound = $file->getClientName();
            $newName = 'audio_' . encrypt_url($id_config) . '.wav';

            $file->move('assets/audio/', $newName, true);

            $data = [
                'value'      => $newName,
                'name_sound' => $name_sound,
            ];

            $this->db->table('konfigurasi_tambahan')->where('id_config', $id_config)->update($data);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'ggwp'
            ]);
        }
    }
}
