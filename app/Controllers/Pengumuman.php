<?php

namespace App\Controllers;

class Pengumuman extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function update($id)
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        $row = $this->db->table('pengumuman')->where('running_text_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'          => 'Update',
                'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'          => base_url('pengumuman/update_action'),
                'running_text_id' => $row->running_text_id,
                'text'            => $row->text,
                'status'          => $row->status,
            ];
            return view('pengumuman/pengumuman_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/dashboard');
    }

    public function update_action()
    {
        if (!session()->get('userid') || session()->get('level_id') != 1) return redirect()->to('/auth');

        if (!$this->validate(['text' => 'required', 'status' => 'required'])) {
            return $this->update(encrypt_url($this->request->getPost('running_text_id')));
        }

        $data = [
            'text'   => $this->request->getPost('text'),
            'status' => $this->request->getPost('status'),
        ];

        $this->db->table('pengumuman')->where('running_text_id', $this->request->getPost('running_text_id'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/pengumuman/update/' . encrypt_url($this->request->getPost('running_text_id')));
    }
}
