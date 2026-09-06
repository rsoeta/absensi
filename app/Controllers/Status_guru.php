<?php

namespace App\Controllers;

class Status_guru extends BaseController
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
            'status_guru_data' => $this->db->table('status_guru')->get()->getResult(),
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('status_guru/status_guru_list', $data);
    }

    public function create()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $data = [
            'button'           => 'Create',
            'action'           => base_url('status_guru/create_action'),
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'status_guru_id'   => '',
            'nama_status_guru' => '',
        ];
        return view('status_guru/status_guru_form', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        if (!$this->validate(['nama_status_guru' => 'required'])) return $this->create();

        $data = ['nama_status_guru' => $this->request->getPost('nama_status_guru')];
        $this->db->table('status_guru')->insert($data);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/status_guru');
    }

    public function update($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $row = $this->db->table('status_guru')->where('status_guru_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'           => 'Update',
                'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'           => base_url('status_guru/update_action'),
                'status_guru_id'   => $row->status_guru_id,
                'nama_status_guru' => $row->nama_status_guru,
            ];
            return view('status_guru/status_guru_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/status_guru');
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        if (!$this->validate(['nama_status_guru' => 'required'])) {
            return $this->update(encrypt_url($this->request->getPost('status_guru_id')));
        }

        $data = ['nama_status_guru' => $this->request->getPost('nama_status_guru')];
        $this->db->table('status_guru')->where('status_guru_id', $this->request->getPost('status_guru_id'))->update($data);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/status_guru');
    }

    public function delete($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('status_guru')->where('status_guru_id', $real_id)->get()->getRow();

        if ($row) {
            try {
                $this->db->table('status_guru')->where('status_guru_id', $real_id)->delete();
                session()->setFlashdata('message', 'Delete Record Success');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                session()->setFlashdata('error', 'Data tidak dapat dihapus karena sudah berelasi.');
            }
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/status_guru');
    }
}
