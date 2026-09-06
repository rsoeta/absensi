<?php

namespace App\Controllers;

class Jenjang extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    protected function checkAuth()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) {
            header('Location: ' . base_url('auth'));
            exit;
        }
    }

    public function index()
    {
        $this->checkAuth();
        $data = [
            'jenjang_data' => $this->db->table('jenjang')->get()->getResult(),
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('jenjang/jenjang_list', $data);
    }

    public function read($id)
    {
        $this->checkAuth();
        $row = $this->db->table('jenjang')->where('jenjang_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'nama_jenjang' => $row->nama_jenjang,
                'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('jenjang/jenjang_read', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/jenjang');
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'       => 'Create',
            'action'       => base_url('jenjang/create_action'),
            'jenjang_id'   => '',
            'nama_jenjang' => '',
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('jenjang/jenjang_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();
        if (!$this->validate(['nama_jenjang' => 'required'])) {
            return redirect()->to('/jenjang/create');
        }

        $this->db->table('jenjang')->insert([
            'nama_jenjang' => $this->request->getPost('nama_jenjang')
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/jenjang');
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('jenjang')->where('jenjang_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'       => 'Update',
                'action'       => base_url('jenjang/update_action'),
                'jenjang_id'   => $row->jenjang_id,
                'nama_jenjang' => $row->nama_jenjang,
                'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('jenjang/jenjang_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/jenjang');
    }

    public function update_action()
    {
        $this->checkAuth();
        if (!$this->validate(['nama_jenjang' => 'required'])) {
            return redirect()->to('/jenjang/update/' . encrypt_url($this->request->getPost('jenjang_id')));
        }

        $this->db->table('jenjang')->where('jenjang_id', $this->request->getPost('jenjang_id'))->update([
            'nama_jenjang' => $this->request->getPost('nama_jenjang')
        ]);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/jenjang');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('jenjang')->where('jenjang_id', $real_id)->get()->getRow();

        if ($row) {
            try {
                $this->db->table('jenjang')->where('jenjang_id', $real_id)->delete();
                session()->setFlashdata('message', 'Delete Record Success');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                session()->setFlashdata('error', 'Tidak dapat dihapus, data jenjang ini sedang digunakan oleh Kelas.');
            }
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }

        return redirect()->to('/jenjang');
    }
}
