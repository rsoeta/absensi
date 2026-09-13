<?php

namespace App\Controllers;

class Absen_mapel extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->checkAuth();
        $level_id = session()->get('level_id');
        $userid   = session()->get('userid');

        if ($level_id == 1) {
            $absen_mapel = $this->db->query("SELECT guru.nama_guru, mapel.nama_mapel, kelas.nama_kelas, absen_mapel.absen_mapel_id, absen_mapel.tanggal, absen_mapel.set_mapel_id
                FROM absen_mapel
                JOIN sett_mapel ON sett_mapel.sett_mapel_id = absen_mapel.set_mapel_id
                JOIN guru ON guru.guru_id = sett_mapel.guru_id
                JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
                JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
                GROUP BY absen_mapel.set_mapel_id, absen_mapel.tanggal")->getResult();
        } else {
            $guru_id = guru_id($userid);
            $absen_mapel = $this->db->query("SELECT sett_mapel.guru_id, guru.nama_guru, mapel.nama_mapel, kelas.nama_kelas, absen_mapel.absen_mapel_id, absen_mapel.tanggal, absen_mapel.set_mapel_id
                FROM absen_mapel
                JOIN sett_mapel ON sett_mapel.sett_mapel_id = absen_mapel.set_mapel_id
                JOIN guru ON guru.guru_id = sett_mapel.guru_id
                JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
                JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
                WHERE sett_mapel.guru_id = ?
                GROUP BY absen_mapel.set_mapel_id, absen_mapel.tanggal", [$guru_id])->getResult();
        }

        $data = [
            'absen_mapel_data' => $absen_mapel,
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('absen_mapel/absen_mapel_list', $data);
    }

    public function create()
    {
        $this->checkAuth();
        $level_id = session()->get('level_id');
        $userid   = session()->get('userid');

        // Ambil data setting mapel berdasarkan role login
        if ($level_id == 1) {
            $set_mapel = $this->db->query("SELECT * FROM sett_mapel JOIN guru ON guru.guru_id = sett_mapel.guru_id JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id")->getResult();
        } else {
            $g_id = guru_id($userid);
            $set_mapel = $this->db->query("SELECT * FROM sett_mapel JOIN guru ON guru.guru_id = sett_mapel.guru_id JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id WHERE sett_mapel.guru_id = ?", [$g_id])->getResult();
        }

        $data = [
            'sett_apps'       => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'sett_mapel_data' => $set_mapel
        ];

        return view('absen_mapel/absen_mapel_form', $data);
    }

    public function doInput()
    {
        $this->checkAuth();

        $sett_mapel_id = $this->request->getPost('sett_mapel_id');
        $tanggal       = $this->request->getPost('tanggal');
        $siswa_id      = $this->request->getPost('siswa_id');
        $keterangan    = $this->request->getPost('keterangan');

        if ($siswa_id && is_array($siswa_id)) {
            $jumlah_data = count($siswa_id);
            for ($i = 0; $i < $jumlah_data; $i++) {
                $s_id = $siswa_id[$i];
                $ket  = $keterangan[$i] ?? 'A'; // Nilai default jika radio tidak terpilih

                $query = $this->db->query("SELECT * FROM absen_mapel WHERE set_mapel_id = ? AND tanggal = ? AND siswa_id = ?", [$sett_mapel_id, $tanggal, $s_id])->getRow();

                if ($query) {
                    $this->db->table('absen_mapel')
                        ->where('set_mapel_id', $sett_mapel_id)
                        ->where('tanggal', $tanggal)
                        ->where('siswa_id', $s_id)
                        ->update(['keterangan' => $ket]);
                } else {
                    $this->db->table('absen_mapel')->insert([
                        'set_mapel_id' => $sett_mapel_id,
                        'tanggal'      => $tanggal,
                        'siswa_id'     => $s_id,
                        'keterangan'   => $ket
                    ]);
                }
            }
            session()->setFlashdata('message', 'Data absen mapel berhasil disimpan');
        }

        return redirect()->to('/absen_mapel/create?m=' . $sett_mapel_id . '&tanggal=' . $tanggal);
    }

    public function delete($set_mapel_id_enc, $tanggal_enc)
    {
        $this->checkAuth();
        $set_mapel_id = decrypt_url($set_mapel_id_enc);
        $tanggal      = decrypt_url($tanggal_enc);

        $this->db->table('absen_mapel')
            ->where('set_mapel_id', $set_mapel_id)
            ->where('tanggal', $tanggal)
            ->delete();

        session()->setFlashdata('message', 'Data absen mapel berhasil dihapus');
        return redirect()->to('/absen_mapel');
    }

    public function laporan()
    {
        $this->checkAuth();
        $tahun = cek_tahun();

        $absen_mapel = $this->db->query("SELECT guru.nama_guru, mapel.nama_mapel, kelas.nama_kelas, absen_mapel.absen_mapel_id, absen_mapel.tanggal, absen_mapel.set_mapel_id, sett_mapel.tahun_ajaran_id
            FROM absen_mapel
            JOIN sett_mapel ON sett_mapel.sett_mapel_id = absen_mapel.set_mapel_id
            JOIN guru ON guru.guru_id = sett_mapel.guru_id
            JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
            JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
            WHERE tahun_ajaran_id = ?
            GROUP BY absen_mapel.set_mapel_id", [$tahun])->getResult();

        $data = [
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'absen_mapel_data' => $absen_mapel,
        ];

        return view('absen_mapel/laporan', $data);
    }

    public function view_laporan()
    {
        $this->checkAuth();
        $set_mapel_id = $this->request->getPost('set_mapel_id');

        $siswa = $this->db->query("SELECT absen_mapel.*, siswa.nama_siswa FROM absen_mapel
            JOIN siswa ON siswa.siswa_id = absen_mapel.siswa_id
            WHERE set_mapel_id = ? GROUP BY absen_mapel.siswa_id", [$set_mapel_id])->getResult();

        $data = [
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'set_mapel_id' => $set_mapel_id,
            'siswa'        => $siswa,
        ];

        return view('absen_mapel/view', $data);
    }

    public function laporan_siswa()
    {
        $this->checkAuth();
        $userid   = session()->get('userid');
        $siswa_id = siswa_id($userid);

        $absen_mapel = $this->db->query("SELECT absen_mapel.keterangan, guru.nama_guru, mapel.nama_mapel, kelas.nama_kelas, absen_mapel.absen_mapel_id, absen_mapel.tanggal, absen_mapel.set_mapel_id, sett_mapel.tahun_ajaran_id
            FROM absen_mapel
            JOIN sett_mapel ON sett_mapel.sett_mapel_id = absen_mapel.set_mapel_id
            JOIN guru ON guru.guru_id = sett_mapel.guru_id
            JOIN kelas ON kelas.kelas_id = sett_mapel.kelas_id
            JOIN mapel ON mapel.mapel_id = sett_mapel.mapel_id
            WHERE absen_mapel.siswa_id = ?", [$siswa_id])->getResult();

        $data = [
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'absen_mapel_data' => $absen_mapel,
        ];

        return view('absen_mapel/laporan_siswa', $data);
    }

    public function laporan_mapel($set_mapel_id)
    {
        $this->checkAuth();
        $siswa = $this->db->query("SELECT absen_mapel.*, siswa.nama_siswa FROM absen_mapel
            JOIN siswa ON siswa.siswa_id = absen_mapel.siswa_id
            WHERE set_mapel_id = ? GROUP BY absen_mapel.siswa_id", [$set_mapel_id])->getResult();

        $data = [
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'set_mapel_id' => $set_mapel_id,
            'siswa'        => $siswa,
        ];

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $html = view('absen_mapel/cetak', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Laporan_Absen_Mapel.pdf', 'I');
        exit;
    }
}
