<?php

namespace App\Controllers;

class Mapel_peminatan extends BaseController
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
            'mapel_peminatan_data' => $this->db->table('mapel_peminatan')->get()->getResult(),
            'sett_apps'            => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        if (session()->get('level_id') == 1) {
            return view('mapel_peminatan/mapel_peminatan_list', $data);
        } else {
            return view('web_user/mapel_peminatan_list', $data); // Asumsi folder web_user sudah digabungkan ke Views
        }
    }

    public function create()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        // Mengambil data guru berdasarkan userid (Menerjemahkan fungsi helper guru_id)
        $user_id = session()->get('userid');
        $guru = $this->db->table('guru')->where('nip', $this->db->table('user')->where('user_id', $user_id)->get()->getRow()->username ?? '')->get()->getRow();
        $guru_id = $guru ? $guru->id_guru : null;

        $data = [
            'button'               => 'Create',
            'action'               => base_url('mapel_peminatan/create_action'),
            'id'                   => '',
            'guru_id'              => $guru_id,
            'nama_mapel_peminatan' => '',
            'sett_apps'            => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        if (session()->get('level_id') == 1) {
            return view('mapel_peminatan/mapel_peminatan_form', $data);
        } else {
            return view('web_user/mapel_peminatan_form', $data);
        }
    }

    public function create_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        if (!$this->validate(['nama_mapel_peminatan' => 'required'])) return $this->create();

        $user_id = session()->get('userid');
        $guru = $this->db->table('guru')->where('nip', $this->db->table('user')->where('user_id', $user_id)->get()->getRow()->username ?? '')->get()->getRow();
        $guru_id = $guru ? $guru->id_guru : null;

        $data = [
            'guru_id'              => $guru_id,
            'nama_mapel_peminatan' => $this->request->getPost('nama_mapel_peminatan'),
        ];

        $this->db->table('mapel_peminatan')->insert($data);
        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/mapel_peminatan');
    }

    public function update($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $row = $this->db->table('mapel_peminatan')->where('id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'               => 'Update',
                'action'               => base_url('mapel_peminatan/update_action'),
                'id'                   => $row->id,
                'guru_id'              => $row->guru_id,
                'nama_mapel_peminatan' => $row->nama_mapel_peminatan,
                'sett_apps'            => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];

            if (session()->get('level_id') == 1) {
                return view('mapel_peminatan/mapel_peminatan_form', $data);
            } else {
                return view('web_user/mapel_peminatan_form', $data);
            }
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/mapel_peminatan');
    }

    public function update_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        if (!$this->validate(['nama_mapel_peminatan' => 'required'])) {
            return $this->update(encrypt_url($this->request->getPost('id')));
        }

        $data = [
            'nama_mapel_peminatan' => $this->request->getPost('nama_mapel_peminatan'),
        ];

        $this->db->table('mapel_peminatan')->where('id', $this->request->getPost('id'))->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/mapel_peminatan');
    }

    public function delete($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('mapel_peminatan')->where('id', $real_id)->get()->getRow();

        if ($row) {
            $this->db->table('mapel_peminatan')->where('id', $real_id)->delete();
            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }

        return redirect()->to('/mapel_peminatan');
    }
}
