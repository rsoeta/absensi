<?php

namespace App\Controllers;

class Absen_mapel_peminatan extends BaseController
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
            $absen = $this->db->query("SELECT guru.nama_guru, mapel_peminatan.nama_mapel_peminatan, absen_mapel_peminatan.id, absen_mapel_peminatan.tanggal, absen_mapel_peminatan.mapel_peminatan_id
                FROM absen_mapel_peminatan
                JOIN mapel_peminatan ON mapel_peminatan.id = absen_mapel_peminatan.mapel_peminatan_id
                JOIN guru ON guru.guru_id = mapel_peminatan.guru_id
                GROUP BY absen_mapel_peminatan.mapel_peminatan_id, absen_mapel_peminatan.tanggal")->getResult();
        } else {
            $guru_id = guru_id($userid);
            $absen = $this->db->query("SELECT mapel_peminatan.guru_id, guru.nama_guru, mapel_peminatan.nama_mapel_peminatan, absen_mapel_peminatan.id, absen_mapel_peminatan.tanggal, absen_mapel_peminatan.mapel_peminatan_id
                FROM absen_mapel_peminatan
                JOIN mapel_peminatan ON mapel_peminatan.id = absen_mapel_peminatan.mapel_peminatan_id
                JOIN guru ON guru.guru_id = mapel_peminatan.guru_id
                WHERE mapel_peminatan.guru_id = ?
                GROUP BY absen_mapel_peminatan.mapel_peminatan_id, absen_mapel_peminatan.tanggal", [$guru_id])->getResult();
        }

        $data = [
            'absen_mapel_peminatan_data' => $absen,
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('absen_mapel_peminatan/absen_mapel_peminatan_list', $data);
    }

    public function create()
    {
        $this->checkAuth();

        $mapel_id = $this->request->getGet('mapel_peminatan_id');
        $tanggal  = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // Ambil data siswa yang mengambil mapel peminatan ini (berdasarkan relasi tabel)
        // Catatan: Pastikan logika get_siswa() CI3 disesuaikan dengan struktur join tabel Anda
        $siswa_list = [];
        if ($mapel_id) {
            $siswa_list = $this->db->query("SELECT siswa.* FROM siswa 
                                            JOIN mapel_peminatan_siswa ON mapel_peminatan_siswa.siswa_id = siswa.siswa_id 
                                            WHERE mapel_peminatan_siswa.mapel_peminatan_id = ?", [$mapel_id])->getResult();
        }

        $data = [
            'sett_apps'          => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'mapel_peminatan_id' => $mapel_id,
            'mapel_peminatan'    => $this->db->table('mapel_peminatan')->get()->getResult(),
            'siswa_list'         => $siswa_list,
            'tanggal'            => $tanggal,
        ];

        return view('absen_mapel_peminatan/absen_mapel_peminatan_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        $mapel_peminatan_id = $this->request->getPost('mapel_peminatan_id');
        $tanggal            = $this->request->getPost('tanggal');
        $siswa_id           = $this->request->getPost('siswa_id');
        $keterangan         = $this->request->getPost('keterangan');

        if ($siswa_id && is_array($siswa_id)) {
            $jumlah_data = count($siswa_id);
            $cek_dlu     = $this->db->table('app_setting')->where('id', 1)->get()->getRow();

            for ($i = 0; $i < $jumlah_data; $i++) {
                $s_id = $siswa_id[$i];
                $ket  = $keterangan[$i] ?? 'A'; // Fallback jika tidak dicentang

                // Cek apakah data absen sudah ada
                $query = $this->db->query("SELECT * FROM absen_mapel_peminatan WHERE mapel_peminatan_id = ? AND tanggal = ? AND siswa_id = ?", [$mapel_peminatan_id, $tanggal, $s_id])->getRow();

                if ($query) {
                    $this->db->table('absen_mapel_peminatan')
                        ->where('mapel_peminatan_id', $mapel_peminatan_id)
                        ->where('tanggal', $tanggal)
                        ->where('siswa_id', $s_id)
                        ->update(['keterangan' => $ket]);
                } else {
                    $this->db->table('absen_mapel_peminatan')->insert([
                        'mapel_peminatan_id' => $mapel_peminatan_id,
                        'tanggal'            => $tanggal,
                        'siswa_id'           => $s_id,
                        'keterangan'         => $ket
                    ]);
                }

                // Logika Notifikasi WhatsApp Blast
                if (in_array($ket, ['A', 'B', 'I', 'S'])) {
                    $mapel_data = $this->db->table('mapel_peminatan')->where('id', $mapel_peminatan_id)->get()->getRow();
                    $siswa_data = $this->db->table('siswa')->where('siswa_id', $s_id)->get()->getRow();

                    if ($siswa_data && $mapel_data) {
                        $kelas_data = $this->db->table('kelas')
                            ->join('guru', 'guru.guru_id = kelas.walikelas')
                            ->where('kelas_id', $siswa_data->kelas_id)
                            ->get()->getRow();

                        if ($kelas_data && !empty($kelas_data->no_hp)) {
                            $status_text = '';
                            if ($ket == 'B') $status_text = '( BOLOS )';
                            elseif ($ket == 'A') $status_text = '( TIDAK HADIR / ALPHA )';
                            elseif ($ket == 'I') $status_text = '( IZIN )';
                            elseif ($ket == 'S') $status_text = '( SAKIT )';

                            if ($cek_dlu->wa_blast == 'Aktif') {
                                $this->db->table('notif')->insert([
                                    'no_hp' => $kelas_data->no_hp,
                                    'pesan' => 'Assalamualaikum Wr Wb Bapak/Ibu wali kelas, Ananda ' . $siswa_data->nama_siswa . ', Absen tanggal ' . $tanggal . ' dengan status ' . $status_text . ' pada mata pelajaran peminatan ' . $mapel_data->nama_mapel_peminatan . '. Terimakasih !!!',
                                ]);
                            }
                        }
                    }
                }
            }
            session()->setFlashdata('message', 'Data absen mapel peminatan berhasil disimpan');
        }

        return redirect()->to('/absen_mapel_peminatan/create?mapel_peminatan_id=' . $mapel_peminatan_id . '&tanggal=' . $tanggal);
    }

    public function delete($mapel_peminatan_id_enc, $tanggal_enc)
    {
        $this->checkAuth();
        $mapel_peminatan_id = decrypt_url($mapel_peminatan_id_enc);
        $tanggal            = decrypt_url($tanggal_enc);

        $this->db->table('absen_mapel_peminatan')
            ->where('mapel_peminatan_id', $mapel_peminatan_id)
            ->where('tanggal', $tanggal)
            ->delete();

        session()->setFlashdata('message', 'Data absen berhasil dihapus');
        return redirect()->to('/absen_mapel_peminatan');
    }

    public function laporan()
    {
        $this->checkAuth();
        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'mapel_peminatan_data' => $this->db->query("SELECT mapel_peminatan.id, guru.nama_guru, mapel_peminatan.nama_mapel_peminatan
                FROM mapel_peminatan
                JOIN guru ON guru.guru_id = mapel_peminatan.guru_id")->getResult(),
        ];
        return view('absen_mapel_peminatan/laporan', $data);
    }

    public function view_laporan()
    {
        $this->checkAuth();
        $mapel_peminatan_id = $this->request->getPost('mapel_peminatan_id');

        $siswa = $this->db->query("SELECT absen_mapel_peminatan.*, siswa.nama_siswa 
            FROM absen_mapel_peminatan
            JOIN siswa ON siswa.siswa_id = absen_mapel_peminatan.siswa_id
            WHERE mapel_peminatan_id = ? GROUP BY absen_mapel_peminatan.siswa_id", [$mapel_peminatan_id])->getResult();

        $data = [
            'sett_apps'          => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'mapel_peminatan_id' => $mapel_peminatan_id,
            'siswa'              => $siswa,
        ];
        return view('absen_mapel_peminatan/view', $data);
    }

    public function laporan_mapel($mapel_peminatan_id)
    {
        $this->checkAuth();
        $siswa = $this->db->query("SELECT absen_mapel_peminatan.*, siswa.nama_siswa 
            FROM absen_mapel_peminatan
            JOIN siswa ON siswa.siswa_id = absen_mapel_peminatan.siswa_id
            WHERE mapel_peminatan_id = ? GROUP BY absen_mapel_peminatan.siswa_id", [$mapel_peminatan_id])->getResult();

        $data = [
            'sett_apps'          => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'mapel_peminatan_id' => $mapel_peminatan_id,
            'siswa'              => $siswa,
        ];

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $html = view('absen_mapel_peminatan/cetak', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Laporan_Absen_Peminatan.pdf', 'I');
        exit;
    }
}
