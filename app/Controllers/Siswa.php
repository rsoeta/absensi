<?php

namespace App\Controllers;

class Siswa extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        date_default_timezone_set('Asia/Jakarta');
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
            'kelas_data' => $this->db->table('kelas')->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('siswa/siswa_list', $data);
    }

    // public function daftar_siswa()
    // {
    //     $this->checkAuth();
    //     $kelas_id = $this->request->getGet('kelas_id');
    //     $data = [
    //         'siswa_data' => $this->db->table('siswa')->where('kelas_id', $kelas_id)->get()->getResult(),
    //         'kelas_id'   => $kelas_id,
    //         'kelas'      => $this->db->table('kelas')->get()->getResult(),
    //         'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
    //     ];
    //     return view('siswa/daftar_siswa', $data);
    // }
    public function daftar_siswa()
    {
        $this->checkAuth();
        $kelas_id = $this->request->getGet('kelas_id');

        $data = [
            // Tambahkan perintah select() dan join() ke tabel kelas
            'siswa_data' => $this->db->table('siswa')
                ->select('siswa.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.kelas_id = siswa.kelas_id', 'left')
                ->where('siswa.kelas_id', $kelas_id)
                ->get()->getResult(),
            'kelas_id'   => $kelas_id,
            'kelas'      => $this->db->table('kelas')->get()->getResult(),
            'sett_apps'  => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];

        return view('siswa/daftar_siswa', $data);
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'           => 'Create',
            'kelas'            => $this->db->table('kelas')->get()->getResult(),
            'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'           => base_url('siswa/create_action'),
            'siswa_id'         => '',
            'nisn' => '',
            'nama_siswa' => '',
            'jk_kelamin' => '',
            'kelas_id'         => '',
            'alamat' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'nama_wali_siswa'  => '',
            'no_hp_wali_siswa' => '',
            'photo' => '',
            'password' => '',
            'agama' => '',
            'nik' => '',
            'penerima_pip' => '',
            'asal_sekolah' => '',
            'no_wa_siswa' => '',
            'hobi' => '',
            'email' => '',
            'nama_ayah' => '',
            'pekerjaan_ayah' => '',
            'nama_ibu' => '',
            'pekerjaan_ibu' => '',
            'penerima_kps' => '',
            'nisn_lama' => ''
        ];
        return view('siswa/siswa_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        $rules = [
            'nisn' => 'required|is_unique[siswa.nisn]|is_unique[user.username]',
            'nama_siswa' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal, pastikan NISN unik.');
            return redirect()->to('/siswa/create');
        }

        $nisn = $this->request->getPost('nisn');
        $nama_siswa = $this->request->getPost('nama_siswa');
        $kelas_id = $this->request->getPost('kelas_id');

        $this->db->table('user')->insert([
            'password' => sha1($this->request->getPost('password')),
            'username' => $nisn,
            'level_id' => 4
        ]);

        $image_name = $nisn . '_' . str_replace(' ', '_', $nama_siswa) . '.png';
        $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nisn));
        file_put_contents(FCPATH . 'assets/img/qr/siswa/' . $image_name, $qrData);

        $photoName = 'default.png';
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/siswa', $photoName);
        }

        $this->db->table('siswa')->insert([
            'nisn'             => $nisn,
            'nama_siswa'       => $nama_siswa,
            'jk_kelamin'       => $this->request->getPost('jk_kelamin'),
            'kelas_id'         => $kelas_id,
            'alamat'           => $this->request->getPost('alamat'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'nama_wali_siswa'  => $this->request->getPost('nama_wali_siswa'),
            'no_hp_wali_siswa' => $this->request->getPost('no_hp_wali_siswa'),
            'agama'            => $this->request->getPost('agama'),
            'nik'              => $this->request->getPost('nik'),
            'penerima_pip'     => $this->request->getPost('penerima_pip'),
            'asal_sekolah'     => $this->request->getPost('asal_sekolah'),
            'no_wa_siswa'      => $this->request->getPost('no_wa_siswa'),
            'hobi'             => $this->request->getPost('hobi'),
            'email'            => $this->request->getPost('email'),
            'nama_ayah'        => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah'   => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'         => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'    => $this->request->getPost('pekerjaan_ibu'),
            'penerima_kps'     => $this->request->getPost('penerima_kps'),
            'photo'            => $photoName,
            'qr_code'          => $image_name,
        ]);

        if ($this->request->getPost('notif') == 'Ya') {
            $this->db->table('notif_siswa')->insert([
                'nama_siswa'  => $nama_siswa,
                'kelas'       => $kelas_id,
                'deksripsi'   => 'ditambahkan',
                'status_baca' => 'Belum Terbaca',
                'tanggal'     => date('Y-m-d H:i:s'),
            ]);
        }

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id);
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('siswa')->where('siswa_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'           => 'Update',
                'kelas'            => $this->db->table('kelas')->get()->getResult(),
                'sett_apps'        => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'           => base_url('siswa/update_action'),
                'siswa_id'         => $row->siswa_id,
                'nisn'             => $row->nisn,
                'nisn_lama'        => $row->nisn,
                'nama_siswa'       => $row->nama_siswa,
                'jk_kelamin'       => $row->jk_kelamin,
                'kelas_id'         => $row->kelas_id,
                'alamat'           => $row->alamat,
                'tempat_lahir'     => $row->tempat_lahir,
                'tanggal_lahir'    => $row->tanggal_lahir,
                'nama_wali_siswa'  => $row->nama_wali_siswa,
                'no_hp_wali_siswa' => $row->no_hp_wali_siswa,
                'agama'            => $row->agama,
                'nik'              => $row->nik,
                'penerima_pip'     => $row->penerima_pip,
                'asal_sekolah'     => $row->asal_sekolah,
                'no_wa_siswa'      => $row->no_wa_siswa,
                'hobi'             => $row->hobi,
                'email'            => $row->email,
                'nama_ayah'        => $row->nama_ayah,
                'pekerjaan_ayah'   => $row->pekerjaan_ayah,
                'nama_ibu'         => $row->nama_ibu,
                'pekerjaan_ibu'    => $row->pekerjaan_ibu,
                'penerima_kps'     => $row->penerima_kps,
                'photo'            => $row->photo ?: 'default.png',
            ];
            return view('siswa/siswa_form', $data);
        }
        return redirect()->to('/siswa');
    }

    public function update_action()
    {
        $this->checkAuth();
        $siswa_id = $this->request->getPost('siswa_id');
        $nisn_lama = $this->request->getPost('nisn_lama');
        $nisn = $this->request->getPost('nisn');
        $nama_siswa = $this->request->getPost('nama_siswa');
        $kelas_id = $this->request->getPost('kelas_id');

        $rules = ['nama_siswa' => 'required'];
        if ($nisn != $nisn_lama) {
            $rules['nisn'] = 'required|is_unique[siswa.nisn]|is_unique[user.username]';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NISN unik.');
            return redirect()->to('/siswa/update/' . encrypt_url($siswa_id));
        }

        $userData = ['username' => $nisn, 'level_id' => 4];
        if (!empty($this->request->getPost('password'))) {
            $userData['password'] = sha1($this->request->getPost('password'));
        }
        $this->db->table('user')->where('username', $nisn_lama)->update($userData);

        $row = $this->db->table('siswa')->where('siswa_id', $siswa_id)->get()->getRow();
        $image_name = $row->qr_code;

        if ($nisn != $nisn_lama || $nama_siswa != $row->nama_siswa) {
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/siswa/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/siswa/' . $row->qr_code);
            }
            $image_name = $nisn . '_' . str_replace(' ', '_', $nama_siswa) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nisn));
            file_put_contents(FCPATH . 'assets/img/qr/siswa/' . $image_name, $qrData);
        }

        $photoName = $this->request->getPost('photo_lama');
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($photoName && $photoName != 'default.png' && file_exists(FCPATH . 'assets/img/siswa/' . $photoName)) {
                unlink(FCPATH . 'assets/img/siswa/' . $photoName);
            }
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/siswa', $photoName);
        }

        $this->db->table('siswa')->where('siswa_id', $siswa_id)->update([
            'nisn'             => $nisn,
            'nama_siswa'       => $nama_siswa,
            'jk_kelamin'       => $this->request->getPost('jk_kelamin'),
            'kelas_id'         => $kelas_id,
            'alamat'           => $this->request->getPost('alamat'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'nama_wali_siswa'  => $this->request->getPost('nama_wali_siswa'),
            'no_hp_wali_siswa' => $this->request->getPost('no_hp_wali_siswa'),
            'agama'            => $this->request->getPost('agama'),
            'nik'              => $this->request->getPost('nik'),
            'penerima_pip'     => $this->request->getPost('penerima_pip'),
            'asal_sekolah'     => $this->request->getPost('asal_sekolah'),
            'no_wa_siswa'      => $this->request->getPost('no_wa_siswa'),
            'hobi'             => $this->request->getPost('hobi'),
            'email'            => $this->request->getPost('email'),
            'nama_ayah'        => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah'   => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'         => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'    => $this->request->getPost('pekerjaan_ibu'),
            'penerima_kps'     => $this->request->getPost('penerima_kps'),
            'photo'            => $photoName,
            'qr_code'          => $image_name,
        ]);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id);
    }

    public function delete($id)
    {
        $this->checkAuth();
        $row = $this->db->table('siswa')->where('siswa_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            if ($row->photo && $row->photo != 'default.png' && file_exists(FCPATH . 'assets/img/siswa/' . $row->photo)) {
                unlink(FCPATH . 'assets/img/siswa/' . $row->photo);
            }
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/siswa/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/siswa/' . $row->qr_code);
            }

            $kelas_id = $row->kelas_id;
            $this->db->table('siswa')->where('siswa_id', $row->siswa_id)->delete();
            $this->db->table('user')->where('username', $row->nisn)->delete();

            session()->setFlashdata('message', 'Delete Record Success');
            return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id);
        }

        session()->setFlashdata('error', 'Record Not Found');
        return redirect()->to('/siswa');
    }

    public function preview_excel()
    {
        $this->checkAuth();
        $str = '';
        $file = $this->request->getFile('file_excel');
        $kelas_id = $this->request->getPost('kelas');
        $is_notified = $this->request->getPost('notified');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'no',
                'message' => 'Gagal mengunggah file. Pastikan file valid.'
            ]);
        }

        // Tentukan path upload sementara
        $uploadPath = FCPATH . 'temp_doc/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = $file->getRandomName();
        $file->move($uploadPath, $fileName);
        $inputFileName = $uploadPath . $fileName;

        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $objExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        $sheet = $objExcel->getActiveSheet()->toArray(null, true, true, true);

        // Ambil nama kelas untuk ditampilkan di tabel preview
        $kelas_row = $this->db->table('kelas')->where('kelas_id', $kelas_id)->get()->getRow();
        $nama_kelas = $kelas_row ? $kelas_row->nama_kelas : 'Unknown';

        for ($i = 3; $i <= count($sheet); $i++) {
            $nisn             = $sheet[$i]['A'];
            $nama_siswa       = $sheet[$i]['B'];
            $jenis_kelamin    = $sheet[$i]['C'];
            $alamat           = $sheet[$i]['D'];
            $tempat_lahir     = $sheet[$i]['E'];
            $tanggal_lahir    = $sheet[$i]['F'];
            $nama_wali_siswa  = $sheet[$i]['G'];
            $no_hp_wali_siswa = $sheet[$i]['H'];
            $password         = $sheet[$i]['I'];
            $agama            = $sheet[$i]['J'];
            $nik              = $sheet[$i]['K'];
            $penerima_pip     = $sheet[$i]['L'];
            $asal_sekolah     = $sheet[$i]['M'];
            $no_wa_siswa      = $sheet[$i]['N'];
            $hobi             = $sheet[$i]['O'];
            $email            = $sheet[$i]['P'];
            $nama_ayah        = $sheet[$i]['Q'];
            $pekerjaan_ayah   = $sheet[$i]['R'];
            $nama_ibu         = $sheet[$i]['S'];
            $pekerjaan_ibu    = $sheet[$i]['T'];
            $penerima_kps     = $sheet[$i]['U'];

            // Lewati baris kosong
            if (empty($nisn) && empty($nama_siswa)) continue;

            $cekdatadidb = $this->db->table('user')->where('username', $nisn)->get()->getRow();

            if ($cekdatadidb) {
                // Jika data sudah ada (duplikat), baris ditandai warna merah
                $str .= '<tr class="bg-danger not-allowed-to-insert-excel text-white">
                            <td>' . $nisn . '</td>
                            <td>' . $nama_siswa . '</td>
                            <td>' . $jenis_kelamin . '</td>
                            <td>' . $nama_kelas . '</td>
                            <td>' . $alamat . '</td>
                            <td>' . $tempat_lahir . '</td>
                            <td>' . $tanggal_lahir . '</td>
                            <td>' . $nama_wali_siswa . '</td>
                            <td>' . $no_hp_wali_siswa . '</td>
                            <td>' . $password . '</td>
                            <td>' . $agama . '</td>
                            <td>' . $nik . '</td>
                            <td>' . $penerima_pip . '</td>
                            <td>' . $asal_sekolah . '</td>
                            <td>' . $no_wa_siswa . '</td>
                            <td>' . $hobi . '</td>
                            <td>' . $email . '</td>
                            <td>' . $nama_ayah . '</td>
                            <td>' . $pekerjaan_ayah . '</td>
                            <td>' . $nama_ibu . '</td>
                            <td>' . $pekerjaan_ibu . '</td>
                            <td>' . $penerima_kps . '</td>
                        </tr>';
            } else {
                // Jika data aman untuk di-insert
                $str .= '<tr>
                            <td>' . $nisn . '<input type="hidden" value="' . $nisn . '" name="nisn[]"/></td>
                            <td>' . $nama_siswa . '<input type="hidden" value="' . $nama_siswa . '" name="nama_siswa[]"/></td>
                            <td>' . $jenis_kelamin . '<input type="hidden" value="' . $jenis_kelamin . '" name="jenis_kelamin[]"/></td>
                            <td>' . $nama_kelas . '<input type="hidden" value="' . $kelas_id . '" name="kelas[]"/></td>
                            <td>' . $alamat . '<input type="hidden" value="' . $alamat . '" name="alamat[]"/></td>
                            <td>' . $tempat_lahir . '<input type="hidden" value="' . $tempat_lahir . '" name="tempat_lahir[]"/></td>
                            <td>' . $tanggal_lahir . '<input type="hidden" value="' . $tanggal_lahir . '" name="tanggal_lahir[]"/></td>
                            <td>' . $nama_wali_siswa . '<input type="hidden" value="' . $nama_wali_siswa . '" name="nama_wali_siswa[]"/></td>
                            <td>' . $no_hp_wali_siswa . '<input type="hidden" value="' . $no_hp_wali_siswa . '" name="no_hp_wali_siswa[]"/></td>
                            <td>' . $password . '<input type="hidden" value="' . $password . '" name="password[]"/></td>
                            <td>' . $agama . '<input type="hidden" value="' . $agama . '" name="agama[]"/></td>
                            <td>' . $nik . '<input type="hidden" value="' . $nik . '" name="nik[]"/></td>
                            <td>' . $penerima_pip . '<input type="hidden" value="' . $penerima_pip . '" name="penerima_pip[]"/></td>
                            <td>' . $asal_sekolah . '<input type="hidden" value="' . $asal_sekolah . '" name="asal_sekolah[]"/></td>
                            <td>' . $no_wa_siswa . '<input type="hidden" value="' . $no_wa_siswa . '" name="no_wa_siswa[]"/></td>
                            <td>' . $hobi . '<input type="hidden" value="' . $hobi . '" name="hobi[]"/></td>
                            <td>' . $email . '<input type="hidden" value="' . $email . '" name="email[]"/></td>
                            <td>' . $nama_ayah . '<input type="hidden" value="' . $nama_ayah . '" name="nama_ayah[]"/></td>
                            <td>' . $pekerjaan_ayah . '<input type="hidden" value="' . $pekerjaan_ayah . '" name="pekerjaan_ayah[]"/></td>
                            <td>' . $nama_ibu . '<input type="hidden" value="' . $nama_ibu . '" name="nama_ibu[]"/></td>
                            <td>' . $pekerjaan_ibu . '<input type="hidden" value="' . $pekerjaan_ibu . '" name="pekerjaan_ibu[]"/></td>
                            <td>' . $penerima_kps . '<input type="hidden" value="' . $penerima_kps . '" name="penerima_kps[]"/></td>
                        </tr>';
            }
        }

        // Hapus file sementara
        if (file_exists($inputFileName)) {
            unlink($inputFileName);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'success',
            'data' => $str,
            'notify' => $is_notified
        ]);
    }

    public function insert_all_from_excel()
    {
        $this->checkAuth();

        $nisn             = $this->request->getPost('nisn');
        $nama_siswa       = $this->request->getPost('nama_siswa');
        $jenis_kelamin    = $this->request->getPost('jenis_kelamin');
        $kelas_id         = $this->request->getPost('kelas');
        $alamat           = $this->request->getPost('alamat');
        $tempat_lahir     = $this->request->getPost('tempat_lahir');
        $tanggal_lahir    = $this->request->getPost('tanggal_lahir');
        $nama_wali_siswa  = $this->request->getPost('nama_wali_siswa');
        $no_hp_wali_siswa = $this->request->getPost('no_hp_wali_siswa');
        $password         = $this->request->getPost('password');

        $agama            = $this->request->getPost('agama');
        $nik              = $this->request->getPost('nik');
        $penerima_pip     = $this->request->getPost('penerima_pip');
        $asal_sekolah     = $this->request->getPost('asal_sekolah');
        $no_wa_siswa      = $this->request->getPost('no_wa_siswa');
        $hobi             = $this->request->getPost('hobi');
        $email            = $this->request->getPost('email');
        $nama_ayah        = $this->request->getPost('nama_ayah');
        $pekerjaan_ayah   = $this->request->getPost('pekerjaan_ayah');
        $nama_ibu         = $this->request->getPost('nama_ibu');
        $pekerjaan_ibu    = $this->request->getPost('pekerjaan_ibu');
        $penerima_kps     = $this->request->getPost('penerima_kps');

        $is_notified      = $this->request->getPost('is_notified');

        if (!$nisn) {
            session()->setFlashdata('error', 'Tidak ada data valid yang diimpor.');
            return redirect()->to('/siswa');
        }

        foreach ($nisn as $key => $value) {
            // Insert User Login
            $this->db->table('user')->insert([
                'password' => sha1($password[$key]),
                'username' => $value,
                'level_id' => 4
            ]);

            // Generate QR Code via API
            $image_name = $value . '_' . str_replace(' ', '_', $nama_siswa[$key]) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($value));

            $qrPath = FCPATH . 'assets/img/qr/siswa/';
            if (!is_dir($qrPath)) mkdir($qrPath, 0777, true);
            file_put_contents($qrPath . $image_name, $qrData);

            // Insert Data Siswa
            $this->db->table('siswa')->insert([
                'nisn'             => $value,
                'nama_siswa'       => $nama_siswa[$key],
                'jk_kelamin'       => $jenis_kelamin[$key],
                'kelas_id'         => $kelas_id[$key],
                'alamat'           => $alamat[$key],
                'tempat_lahir'     => $tempat_lahir[$key],
                'tanggal_lahir'    => date('Y-m-d', strtotime($tanggal_lahir[$key])),
                'nama_wali_siswa'  => $nama_wali_siswa[$key],
                'no_hp_wali_siswa' => $no_hp_wali_siswa[$key],
                'agama'            => $agama[$key],
                'nik'              => $nik[$key],
                'penerima_pip'     => $penerima_pip[$key],
                'asal_sekolah'     => $asal_sekolah[$key],
                'no_wa_siswa'      => $no_wa_siswa[$key],
                'hobi'             => $hobi[$key],
                'email'            => $email[$key],
                'nama_ayah'        => $nama_ayah[$key],
                'pekerjaan_ayah'   => $pekerjaan_ayah[$key],
                'nama_ibu'         => $nama_ibu[$key],
                'pekerjaan_ibu'    => $pekerjaan_ibu[$key],
                'penerima_kps'     => $penerima_kps[$key],
                'photo'            => 'default.png',
                'qr_code'          => $image_name,
            ]);

            // Insert Notifikasi Jika Dipilih
            if ($is_notified == 'true' || $is_notified == '1') {
                $this->db->table('notif_siswa')->insert([
                    'nama_siswa'  => $nama_siswa[$key],
                    'kelas'       => $kelas_id[$key],
                    'deksripsi'   => 'ditambahkan',
                    'status_baca' => 'Belum Terbaca',
                    'tanggal'     => date('Y-m-d H:i:s'),
                ]);
            }
        }

        session()->setFlashdata('message', 'Import Data Excel Berhasil!');
        return redirect()->to('/siswa');
    }

    public function export_excel()
    {
        $this->checkAuth();
        $kelas_id = $this->request->getGet('id');
        $kelas_row = $this->db->table('kelas')->where('kelas_id', $kelas_id)->get()->getRow();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Setup Header Tabel
        $headers = ['No', 'NISN', 'Nama Siswa', 'jk_kelamin', 'Kelas', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'nama_wali_siswa', 'no_hp_wali_siswa', 'agama', 'nik', 'penerima_pip', 'asal_sekolah', 'no_wa_siswa', 'hobi', 'email', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu', 'penerima_kps'];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $siswa = $this->db->table('siswa')->where('kelas_id', $kelas_id)->get()->getResult();
        $no = 1;
        $numrow = 2;

        foreach ($siswa as $data) {
            $sheet->setCellValue('A' . $numrow, $no++);
            $sheet->setCellValue('B' . $numrow, $data->nisn);
            $sheet->setCellValue('C' . $numrow, $data->nama_siswa);
            $sheet->setCellValue('D' . $numrow, $data->jk_kelamin);
            $sheet->setCellValue('E' . $numrow, $kelas_row ? $kelas_row->nama_kelas : '');
            $sheet->setCellValue('F' . $numrow, $data->alamat);
            $sheet->setCellValue('G' . $numrow, $data->tempat_lahir);
            $sheet->setCellValue('H' . $numrow, $data->tanggal_lahir);
            $sheet->setCellValue('I' . $numrow, $data->nama_wali_siswa);
            $sheet->setCellValue('J' . $numrow, $data->no_hp_wali_siswa);
            $sheet->setCellValue('K' . $numrow, $data->agama);
            $sheet->setCellValue('L' . $numrow, $data->nik);
            $sheet->setCellValue('M' . $numrow, $data->penerima_pip);
            $sheet->setCellValue('N' . $numrow, $data->asal_sekolah);
            $sheet->setCellValue('O' . $numrow, $data->no_wa_siswa);
            $sheet->setCellValue('P' . $numrow, $data->hobi);
            $sheet->setCellValue('Q' . $numrow, $data->email);
            $sheet->setCellValue('R' . $numrow, $data->nama_ayah);
            $sheet->setCellValue('S' . $numrow, $data->pekerjaan_ayah);
            $sheet->setCellValue('T' . $numrow, $data->nama_ibu);
            $sheet->setCellValue('U' . $numrow, $data->pekerjaan_ibu);
            $sheet->setCellValue('V' . $numrow, $data->penerima_kps);
            $numrow++;
        }

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->setTitle("Data Siswa");

        $filename = 'Data Siswa Kelas ' . ($kelas_row ? $kelas_row->nama_kelas : '') . ' ' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function update_kelas($kelas_id_asal)
    {
        $this->checkAuth();

        // Ambil array ID Siswa yang dicentang
        $id_siswa = $this->request->getPost('update');

        if (empty($id_siswa)) {
            session()->setFlashdata('error', 'Pilih minimal satu siswa terlebih dahulu!');
            return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id_asal);
        }

        // AKSI 1: HAPUS DATA TERPILIH
        if ($this->request->getPost('hapus') == 'Y') {
            $notify = $this->request->getPost('notify');

            foreach ($id_siswa as $id) {
                $siswa = $this->db->table('siswa')->where('siswa_id', $id)->get()->getRow();
                if ($siswa) {
                    // Hapus file fisik menggunakan struktur folder /assets/ yang baru
                    if ($siswa->photo && $siswa->photo != 'default.png' && file_exists(FCPATH . 'assets/img/siswa/' . $siswa->photo)) {
                        unlink(FCPATH . 'assets/img/siswa/' . $siswa->photo);
                    }
                    if ($siswa->qr_code && file_exists(FCPATH . 'assets/img/qr/siswa/' . $siswa->qr_code)) {
                        unlink(FCPATH . 'assets/img/qr/siswa/' . $siswa->qr_code);
                    }

                    // Masukkan Notifikasi jika dicentang
                    if ($notify == 'on') {
                        $this->db->table('notif_siswa')->insert([
                            'nama_siswa'  => $siswa->nama_siswa,
                            'kelas'       => $siswa->kelas_id,
                            'deksripsi'   => 'dihapus',
                            'status_baca' => 'Belum Terbaca',
                            'tanggal'     => date('Y-m-d H:i:s')
                        ]);
                    }

                    // Eksekusi Hapus Database
                    $this->db->table('user')->where('username', $siswa->nisn)->delete();
                    $this->db->table('siswa')->where('siswa_id', $id)->delete();
                }
            }
            session()->setFlashdata('message', 'Data siswa terpilih berhasil dihapus.');
        }

        // AKSI 2: PINDAH KELAS TERPILIH
        elseif ($this->request->getPost('pindah') == 'Y') {
            $tujuan_kelas = $this->request->getPost('kelas_id');

            if (empty($tujuan_kelas)) {
                session()->setFlashdata('error', 'Pilih kelas tujuan terlebih dahulu!');
                return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id_asal);
            }

            foreach ($id_siswa as $id) {
                $this->db->table('siswa')->where('siswa_id', $id)->update(['kelas_id' => $tujuan_kelas]);
            }
            session()->setFlashdata('message', 'Data siswa berhasil dipindahkan.');
        }

        // AKSI 3: CETAK KARTU MASSAL
        elseif ($this->request->getPost('cetak') == 'Y') {
            $ids = implode(',', $id_siswa);
            return redirect()->to('/siswa/cetak_bulk?ids=' . $ids);
        }

        return redirect()->to('/siswa/daftar_siswa?kelas_id=' . $kelas_id_asal);
    }

    public function cetak($id)
    {
        $this->checkAuth();
        $real_id = decrypt_url($id);
        $row = $this->db->table('siswa')->where('siswa_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'sett_apps'     => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nisn'          => $row->nisn,
                'nama_siswa'    => $row->nama_siswa,
                'tempat_lahir'  => $row->tempat_lahir,
                'tanggal_lahir' => $row->tanggal_lahir,
                'alamat'        => $row->alamat,
                'photo'         => $row->photo,
                'qr_code'       => $row->qr_code,
            ];
            return view('siswa/cetak', $data);
        } else {
            session()->setFlashdata('error', 'Data siswa tidak ditemukan.');
            return redirect()->to('/siswa');
        }
    }

    public function cetak_semua()
    {
        $this->checkAuth();
        $ids = $this->request->getPost('update'); // Menangkap array ID dari checkbox

        if (!$ids) {
            session()->setFlashdata('error', 'Pilih minimal satu siswa untuk dicetak kartunya.');
            return redirect()->to('/siswa');
        }

        $siswa_data = $this->db->table('siswa')->whereIn('siswa_id', $ids)->get()->getResult();

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'siswa'     => $siswa_data
        ];

        return view('siswa/cetak_semua', $data);
    }

    public function cetak_bulk()
    {
        $this->checkAuth();

        // Tangkap parameter 'ids' dari URL
        $ids_string = $this->request->getGet('ids');

        if (empty($ids_string)) {
            session()->setFlashdata('error', 'Pilih minimal satu siswa untuk dicetak kartunya.');
            return redirect()->to('/siswa');
        }

        // Pecah string "179,180,181" menjadi array
        $ids = explode(',', $ids_string);

        // Ambil data siswa berdasarkan array ID yang dicentang
        $siswa_data = $this->db->table('siswa')->whereIn('siswa_id', $ids)->get()->getResult();

        $data = [
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'siswa'     => $siswa_data
        ];

        // Kita tetap menggunakan View 'cetak_semua' yang sudah dibuat di langkah sebelumnya
        return view('siswa/cetak_semua', $data);
    }
}
