<?php

namespace App\Controllers;

class Kelas extends BaseController
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
            // Tambahkan relasi JOIN agar View bisa membaca nama_jenjang dan nama_guru
            'kelas_data' => $this->db->table('kelas')
                ->join('jenjang', 'jenjang.jenjang_id = kelas.jenjang_id', 'left')
                ->join('guru', 'guru.guru_id = kelas.walikelas', 'left')
                ->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('kelas/kelas_list', $data);
    }

    public function create()
    {
        $this->checkAuth();

        $data = [
            'button'     => 'Create',
            'guru'       => $this->db->table('guru')->get()->getResult(),
            'jenjang'    => $this->db->table('jenjang')->get()->getResult(),
            'action'     => base_url('kelas/create_action'),
            'kelas_id'   => '',
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'jenjang_id' => '',
            'nama_kelas' => '',
            'walikelas'  => '',
        ];
        return view('kelas/kelas_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        $rules = [
            'jenjang_id' => 'required',
            'nama_kelas' => 'required',
            'walikelas'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->create();
        }

        $this->db->table('kelas')->insert([
            'jenjang_id' => $this->request->getPost('jenjang_id'),
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'walikelas'  => $this->request->getPost('walikelas'),
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/kelas');
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('kelas')->where('kelas_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'     => 'Update',
                'guru'       => $this->db->table('guru')->get()->getResult(),
                'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'jenjang'    => $this->db->table('jenjang')->get()->getResult(),
                'action'     => base_url('kelas/update_action'),
                'kelas_id'   => $row->kelas_id,
                'jenjang_id' => $row->jenjang_id,
                'nama_kelas' => $row->nama_kelas,
                'walikelas'  => $row->walikelas,
            ];
            return view('kelas/kelas_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/kelas');
    }

    public function update_action()
    {
        $this->checkAuth();

        $rules = [
            'jenjang_id' => 'required',
            'nama_kelas' => 'required',
            'walikelas'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->update(encrypt_url($this->request->getPost('kelas_id')));
        }

        $this->db->table('kelas')->where('kelas_id', $this->request->getPost('kelas_id'))->update([
            'jenjang_id' => $this->request->getPost('jenjang_id'),
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'walikelas'  => $this->request->getPost('walikelas'),
        ]);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/kelas');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('kelas')->where('kelas_id', $real_id)->get()->getRow();

        if ($row) {
            try {
                $this->db->table('kelas')->where('kelas_id', $real_id)->delete();
                session()->setFlashdata('message', 'Delete Record Success');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                session()->setFlashdata('error', 'Tidak dapat dihapus, data sudah berelasi.');
            }
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }

        return redirect()->to('/kelas');
    }
}
