<?php

namespace App\Controllers;

class Waktu_absen extends BaseController
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
            'waktu_absen_data' => $this->db->table('waktu_absen')->get()->getResult(),
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('waktu_absen/waktu_absen_list', $data);
    }

    public function create()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $data = [
            'button'                => 'Simpan',
            'sett_apps'             => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'                => base_url('waktu_absen/create_action'),
            'waktu_absen'           => set_value('waktu_absen'),
            'nama_hari'             => set_value('nama_hari'),
            'jam_masuk_guru'        => set_value('jam_masuk_guru'),
            'absen_terlambat_guru'  => set_value('absen_terlambat_guru'),
            'jam_masuk_siswa'       => set_value('jam_masuk_siswa'),
            'absen_terlambat_siswa' => set_value('absen_terlambat_siswa'),
            'jam_pulang_guru'       => set_value('jam_pulang_guru'),
            'jam_pulang_siswa'      => set_value('jam_pulang_siswa'),
        ];
        return view('waktu_absen/waktu_absen_form', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $rules = [
            'nama_hari'             => 'required',
            'jam_masuk_guru'        => 'required',
            'absen_terlambat_guru'  => 'required',
            'jam_masuk_siswa'       => 'required',
            'absen_terlambat_siswa' => 'required',
            'jam_pulang_guru'       => 'required',
            'jam_pulang_siswa'      => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->create();
        }

        $data = [
            'nama_hari'             => $this->request->getPost('nama_hari'),
            'jam_masuk_guru'        => $this->request->getPost('jam_masuk_guru'),
            'absen_terlambat_guru'  => $this->request->getPost('absen_terlambat_guru'),
            'jam_masuk_siswa'       => $this->request->getPost('jam_masuk_siswa'),
            'absen_terlambat_siswa' => $this->request->getPost('absen_terlambat_siswa'),
            'jam_pulang_guru'       => $this->request->getPost('jam_pulang_guru'),
            'jam_pulang_siswa'      => $this->request->getPost('jam_pulang_siswa'),
        ];

        $this->db->table('waktu_absen')->insert($data);
        session()->setFlashdata('message', 'Berhasil menambahkan data waktu absen');
        return redirect()->to('/waktu_absen');
    }

    public function update($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $row = $this->db->table('waktu_absen')->where('waktu_absen', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'                => 'Update',
                'sett_apps'             => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'                => base_url('waktu_absen/update_action'),
                'waktu_absen'           => $row->waktu_absen,
                'nama_hari'             => $row->nama_hari,
                'jam_masuk_guru'        => $row->jam_masuk_guru,
                'absen_terlambat_guru'  => $row->absen_terlambat_guru,
                'jam_masuk_siswa'       => $row->jam_masuk_siswa,
                'absen_terlambat_siswa' => $row->absen_terlambat_siswa,
                'jam_pulang_guru'       => $row->jam_pulang_guru,
                'jam_pulang_siswa'      => $row->jam_pulang_siswa,
            ];
            return view('waktu_absen/waktu_absen_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/waktu_absen');
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $rules = [
            'nama_hari'             => 'required',
            'jam_masuk_guru'        => 'required',
            'absen_terlambat_guru'  => 'required',
            'jam_masuk_siswa'       => 'required',
            'absen_terlambat_siswa' => 'required',
            'jam_pulang_guru'       => 'required',
            'jam_pulang_siswa'      => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->update(encrypt_url($this->request->getPost('waktu_absen')));
        }

        $data = [
            'nama_hari'             => $this->request->getPost('nama_hari'),
            'jam_masuk_guru'        => $this->request->getPost('jam_masuk_guru'),
            'absen_terlambat_guru'  => $this->request->getPost('absen_terlambat_guru'),
            'jam_masuk_siswa'       => $this->request->getPost('jam_masuk_siswa'),
            'absen_terlambat_siswa' => $this->request->getPost('absen_terlambat_siswa'),
            'jam_pulang_guru'       => $this->request->getPost('jam_pulang_guru'),
            'jam_pulang_siswa'      => $this->request->getPost('jam_pulang_siswa'),
        ];

        $this->db->table('waktu_absen')->where('waktu_absen', $this->request->getPost('waktu_absen'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/waktu_absen');
    }
}
