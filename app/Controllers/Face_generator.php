<?php

namespace App\Controllers;

class Face_generator extends BaseController
{
    public function index()
    {
        $this->checkAuth(); // Pastikan hanya Admin yang bisa akses
        $db = \Config\Database::connect();

        // Ambil data yang ada fotonya DAN face_descriptor-nya benar-benar kosong (NULL atau string kosong)
        $data = [
            'sett_apps' => $db->table('app_setting')->where('id', 1)->get()->getRow(),
            'guru'      => $db->table('guru')->where("photo != '' AND photo != 'default.png' AND (face_descriptor IS NULL OR face_descriptor = '')")->get()->getResult(),
            'pegawai'   => $db->table('pegawai')->where("photo != '' AND photo != 'default.png' AND (face_descriptor IS NULL OR face_descriptor = '')")->get()->getResult(),
            'siswa'     => $db->table('siswa')->where("photo != '' AND photo != 'default.png' AND (face_descriptor IS NULL OR face_descriptor = '')")->get()->getResult(),
        ];

        return view('face_generator/index', $data);
    }

    public function save_descriptor()
    {
        $db = \Config\Database::connect();

        $jenis      = $this->request->getPost('jenis'); // 'guru', 'pegawai', atau 'siswa'
        $id         = $this->request->getPost('id');    // ID primary key
        $descriptor = $this->request->getPost('descriptor'); // String JSON Array 128-D

        if ($jenis == 'guru') {
            $db->table('guru')->where('guru_id', $id)->update(['face_descriptor' => $descriptor]);
        } elseif ($jenis == 'pegawai') {
            $db->table('pegawai')->where('pegawai_id', $id)->update(['face_descriptor' => $descriptor]);
        } elseif ($jenis == 'siswa') {
            $db->table('siswa')->where('siswa_id', $id)->update(['face_descriptor' => $descriptor]);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }
}
