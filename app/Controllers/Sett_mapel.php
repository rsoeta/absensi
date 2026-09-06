<?php

namespace App\Controllers;

class Sett_mapel extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Fungsi helper internal untuk mendapatkan tahun ajaran aktif
    private function cek_tahun()
    {
        $today = date('Y-m-d');
        $tahun = $this->db->table('tahun_ajaran')
            ->where('tgl_awal <=', $today)
            ->where('tgl_akhir >=', $today)
            ->get()->getRow();
        return $tahun ? $tahun->tahun_ajaran_id : null;
    }

    public function index()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $data = [
            'sett_mapel_data' => $this->db->table('sett_mapel')->get()->getResult(),
            'button'          => 'Create',
            'sett_mapel_id'   => '',
            'guru_id'         => '',
            'mapel_id'        => '',
            'kelas_id'        => '',
            'action'          => base_url('sett_mapel/create_action'),
            'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'kelas_data'      => $this->db->table('kelas')->get()->getResult(),
            'guru_data'       => $this->db->table('guru')->get()->getResult(),
            'mapel_data'      => $this->db->table('mapel')->get()->getResult(),
        ];
        return view('sett_mapel/sett_mapel_list', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $rules = [
            'guru_id'  => 'required',
            'mapel_id' => 'required',
            'kelas_id' => 'required'
        ];

        if (!$this->validate($rules)) return $this->index();

        $guru_id  = $this->request->getPost('guru_id');
        $mapel_id = $this->request->getPost('mapel_id');
        $kelas_id = $this->request->getPost('kelas_id');
        $tahun_id = $this->cek_tahun();

        $query = $this->db->table('sett_mapel')
            ->where('guru_id', $guru_id)
            ->where('mapel_id', $mapel_id)
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahun_id)
            ->countAllResults();

        if ($query > 0) {
            session()->setFlashdata('error', 'Sudah ada setting data Guru Mapel untuk Kelas ini pada tahun ajaran aktif.');
        } else {
            $data = [
                'guru_id'         => $guru_id,
                'mapel_id'        => $mapel_id,
                'kelas_id'        => $kelas_id,
                'tahun_ajaran_id' => $tahun_id,
            ];
            $this->db->table('sett_mapel')->insert($data);
            session()->setFlashdata('message', 'Create Record Success');
        }
        return redirect()->to('/sett_mapel');
    }

    public function delete($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('sett_mapel')->where('sett_mapel_id', $real_id)->get()->getRow();

        if ($row) {
            $this->db->table('sett_mapel')->where('sett_mapel_id', $real_id)->delete();
            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/sett_mapel');
    }
}
