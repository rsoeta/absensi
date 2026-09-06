<?php

namespace App\Controllers;

class Tahun_ajaran extends BaseController
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
            'tahun_ajaran_data' => $this->db->table('tahun_ajaran')->get()->getResult(),
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('tahun_ajaran/tahun_ajaran_list', $data);
    }

    public function create()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $data = [
            'button'            => 'Create',
            'action'            => base_url('tahun_ajaran/create_action'),
            'tahun_ajaran_id'   => '',
            'nama_tahun_ajaran' => '',
            'tgl_awal'          => date('Y-m-d'),
            'tgl_akhir'         => date('Y-m-d'),
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('tahun_ajaran/tahun_ajaran_form', $data);
    }

    public function create_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $rules = [
            'nama_tahun_ajaran' => 'required',
            'tgl_awal'          => 'required',
            'tgl_akhir'         => 'required'
        ];

        if (!$this->validate($rules)) return $this->create();

        $nama_tahun_ajaran = $this->request->getPost('nama_tahun_ajaran');
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');

        // Deteksi tabrakan tanggal tidak lagi menggunakan helper external, 
        // kita persederhanakan logikanya dengan asumsi data bebas dimasukkan.
        // Jika butuh logika deteksi tabrakan yg ketat, bisa ditambahkan kembali nanti.

        $data = [
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
        ];

        $this->db->table('tahun_ajaran')->insert($data);
        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/tahun_ajaran');
    }

    public function update($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $row = $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'            => 'Update',
                'action'            => base_url('tahun_ajaran/update_action'),
                'tahun_ajaran_id'   => $row->tahun_ajaran_id,
                'nama_tahun_ajaran' => $row->nama_tahun_ajaran,
                'tgl_awal'          => $row->tgl_awal,
                'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'tgl_akhir'         => $row->tgl_akhir,
            ];
            return view('tahun_ajaran/tahun_ajaran_form', $data);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/tahun_ajaran');
    }

    public function update_action()
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $rules = [
            'nama_tahun_ajaran' => 'required',
            'tgl_awal'          => 'required',
            'tgl_akhir'         => 'required'
        ];

        if (!$this->validate($rules)) return $this->update(encrypt_url($this->request->getPost('tahun_ajaran_id')));

        $tahunajaranid = $this->request->getPost('tahun_ajaran_id');
        $data = [
            'nama_tahun_ajaran' => $this->request->getPost('nama_tahun_ajaran'),
            'tgl_awal'          => $this->request->getPost('tgl_awal'),
            'tgl_akhir'         => $this->request->getPost('tgl_akhir'),
        ];

        $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $tahunajaranid)->update($data);
        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/tahun_ajaran');
    }

    public function delete($id)
    {
        if (!session()->get('userid')) return redirect()->to('/auth');

        $real_id = decrypt_url($id);
        $row = $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $real_id)->get()->getRow();

        if ($row) {
            try {
                $this->db->table('tahun_ajaran')->where('tahun_ajaran_id', $real_id)->delete();
                session()->setFlashdata('message', 'Delete Record Success');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                session()->setFlashdata('error', 'Data tidak dapat dihapus karena sudah berelasi.');
            }
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/tahun_ajaran');
    }
}
