<?php

namespace App\Controllers;

class Hari_libur extends BaseController
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
            'hari_libur_data' => $this->db->table('hari_libur')->get()->getResult(),
            'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('hari_libur/hari_libur_list', $data);
    }

    public function create()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $data = [
            'button'        => 'Create',
            'action'        => base_url('hari_libur/create_action'),
            'hari_libur_id' => '',
            'tanggal'       => '',
            'keterangan'    => '',
            'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('hari_libur/hari_libur_form', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        if (!$this->validate(['tanggal' => 'required', 'keterangan' => 'required'])) {
            return $this->create();
        }

        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->db->table('hari_libur')->insert($data);
        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/hari_libur');
    }

    public function update($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $row = $this->db->table('hari_libur')->where('hari_libur_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'        => 'Update',
                'action'        => base_url('hari_libur/update_action'),
                'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'hari_libur_id' => $row->hari_libur_id,
                'tanggal'       => $row->tanggal,
                'keterangan'    => $row->keterangan,
            ];
            return view('hari_libur/hari_libur_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/hari_libur');
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        if (!$this->validate(['tanggal' => 'required', 'keterangan' => 'required'])) {
            return $this->update(encrypt_url($this->request->getPost('hari_libur_id')));
        }

        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->db->table('hari_libur')->where('hari_libur_id', $this->request->getPost('hari_libur_id'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/hari_libur');
    }

    public function delete($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('hari_libur')->where('hari_libur_id', $real_id)->get()->getRow();

        if ($row) {
            $this->db->table('hari_libur')->where('hari_libur_id', $real_id)->delete();
            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/hari_libur');
    }
}
