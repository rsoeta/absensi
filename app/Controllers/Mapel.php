<?php

namespace App\Controllers;

class Mapel extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $data = [
            'mapel_data' => $this->db->table('mapel')->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('mapel/mapel_list', $data);
    }

    public function create()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $data = [
            'button'     => 'Create',
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'     => base_url('mapel/create_action'),
            'mapel_id'   => '',
            'kode_mapel' => '',
            'nama_mapel' => '',
        ];
        return view('mapel/mapel_form', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        if (!$this->validate(['kode_mapel' => 'required', 'nama_mapel' => 'required'])) {
            return $this->create();
        }

        $data = [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel'),
        ];

        $this->db->table('mapel')->insert($data);
        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/mapel');
    }

    public function update($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $row = $this->db->table('mapel')->where('mapel_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'     => 'Update',
                'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'     => base_url('mapel/update_action'),
                'mapel_id'   => $row->mapel_id,
                'kode_mapel' => $row->kode_mapel,
                'nama_mapel' => $row->nama_mapel,
            ];
            return view('mapel/mapel_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/mapel');
    }

    public function update_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        if (!$this->validate(['kode_mapel' => 'required', 'nama_mapel' => 'required'])) {
            return $this->update(encrypt_url($this->request->getPost('mapel_id')));
        }

        $data = [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel'),
        ];

        $this->db->table('mapel')->where('mapel_id', $this->request->getPost('mapel_id'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/mapel');
    }

    public function delete($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('mapel')->where('mapel_id', $real_id)->get()->getRow();

        if ($row) {
            try {
                $this->db->table('mapel')->where('mapel_id', $real_id)->delete();
                session()->setFlashdata('message', 'Delete Record Success');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                // Penanganan Error jika data berelasi
                session()->setFlashdata('error', 'Tidak dapat dihapus data sudah berrelasi');
            }
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }

        return redirect()->to('/mapel');
    }
}
