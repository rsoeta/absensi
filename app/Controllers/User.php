<?php

namespace App\Controllers;

class User extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();

        $data = [
            'user_data' => $this->db->table('user')->get()->getResult(),
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('user/user_list', $data);
    }

    public function read($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'username'  => $row->username,
                'level_id'  => $row->level_id,
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('user/user_read', $data);
        } else {
            session()->setFlashdata('error', 'Record Not Found');
            return redirect()->to('/user');
        }
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'    => 'Create',
            'action'    => base_url('user/create_action'),
            'user_id'   => '',
            'username'  => '',
            'password'  => '',
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('user/user_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $this->db->table('user')->insert([
            'username' => $username,
            'password' => sha1($password),
            'level_id' => 1 // Level 1 untuk Admin Aplikasi
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/user');
    }

    public function update($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'button'    => 'Update',
                'action'    => base_url('user/update_action'),
                'user_id'   => $row->user_id,
                'username'  => $row->username,
                'password'  => '',
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];
            return view('user/user_form', $data);
        } else {
            session()->setFlashdata('error', 'Record Not Found');
            return redirect()->to('/user');
        }
    }

    public function update_action()
    {
        $this->checkAuth();
        $user_id  = $this->request->getPost('user_id');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $data = [
            'username' => $username
        ];

        // Jika password diisi, update password baru (terenkripsi SHA1)
        if (!empty($password)) {
            $data['password'] = sha1($password);
        }

        $this->db->table('user')->where('user_id', $user_id)->update($data);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/user');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('user')->where('user_id', $real_id)->get()->getRow();

        if ($row) {
            $this->db->table('user')->where('user_id', $real_id)->delete();
            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/user');
    }
}
