<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Guru extends BaseController
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
            'guru_data' => $this->db->table('guru')->get()->getResult(),
            'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('guru/guru_list', $data);
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'         => 'Create',
            'status_guru'    => $this->db->table('status_guru')->get()->getResult(),
            'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'         => base_url('guru/create_action'),
            'guru_id'        => '',
            'nip' => '',
            'nama_guru' => '',
            'jk_kelamin' => '',
            'status_guru_id' => '',
            'alamat' => '',
            'no_hp' => '',
            'tempat_lahir' => '',
            'tanggal_lahir'  => '',
            'photo' => '',
            'password' => '',
        ];
        return view('guru/guru_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        $rules = [
            'nip' => 'required|is_unique[guru.nip]|is_unique[user.username]',
            'nama_guru' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal, pastikan NIP unik dan form terisi.');
            return redirect()->to('/guru/create');
        }

        $nip = $this->request->getPost('nip');
        $nama_guru = $this->request->getPost('nama_guru');

        // Insert ke tabel User
        $this->db->table('user')->insert([
            'password' => sha1($this->request->getPost('password')),
            'username' => $nip,
            'level_id' => 2
        ]);

        // Generate QR Code via API & Save Local
        $image_name = $nip . '_' . str_replace(' ', '_', $nama_guru) . '.png';
        $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
        file_put_contents(FCPATH . 'assets/img/qr/guru/' . $image_name, $qrData);

        // Upload Photo
        $photoName = 'default.png';
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/guru', $photoName);
        }

        $this->db->table('guru')->insert([
            'nip'            => $nip,
            'nama_guru'      => $nama_guru,
            'jk_kelamin'     => $this->request->getPost('jk_kelamin'),
            'status_guru_id' => $this->request->getPost('status_guru_id'),
            'alamat'         => $this->request->getPost('alamat'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
            'photo'          => $photoName,
            'qr_code'        => $image_name,
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/guru');
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('guru')->where('guru_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'         => 'Update',
                'status_guru'    => $this->db->table('status_guru')->get()->getResult(),
                'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'         => base_url('guru/update_action'),
                'guru_id'        => $row->guru_id,
                'nip'            => $row->nip,
                'nip_lama'       => $row->nip,
                'nama_guru'      => $row->nama_guru,
                'jk_kelamin'     => $row->jk_kelamin,
                'status_guru_id' => $row->status_guru_id,
                'alamat'         => $row->alamat,
                'no_hp'          => $row->no_hp,
                'tempat_lahir'   => $row->tempat_lahir,
                'tanggal_lahir'  => $row->tanggal_lahir,
                'photo'          => $row->photo ?: 'default.png',
            ];
            return view('guru/guru_form', $data);
        }
        return redirect()->to('/guru');
    }

    public function update_action()
    {
        $this->checkAuth();
        $guru_id = $this->request->getPost('guru_id');
        $nip_lama = $this->request->getPost('nip_lama');
        $nip = $this->request->getPost('nip');
        $nama_guru = $this->request->getPost('nama_guru');

        $rules = ['nama_guru' => 'required'];
        if ($nip != $nip_lama) {
            $rules['nip'] = 'required|is_unique[guru.nip]|is_unique[user.username]';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NIP unik.');
            return redirect()->to('/guru/update/' . encrypt_url($guru_id));
        }

        // Update User Table
        $userData = ['username' => $nip, 'level_id' => 2];
        if (!empty($this->request->getPost('password'))) {
            $userData['password'] = sha1($this->request->getPost('password'));
        }
        $this->db->table('user')->where('username', $nip_lama)->update($userData);

        $row = $this->db->table('guru')->where('guru_id', $guru_id)->get()->getRow();
        $image_name = $row->qr_code;

        // Generate QR Baru jika NIP atau Nama berubah
        if ($nip != $nip_lama || $nama_guru != $row->nama_guru) {
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/guru/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/guru/' . $row->qr_code);
            }
            $image_name = $nip . '_' . str_replace(' ', '_', $nama_guru) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
            file_put_contents(FCPATH . 'assets/img/qr/guru/' . $image_name, $qrData);
        }

        // Update Photo
        $photoName = $this->request->getPost('photo_lama');
        $file = $this->request->getFile('photo');

        // Siapkan array data update untuk tabel guru
        $updateData = [
            'nip'            => $nip,
            'nama_guru'      => $nama_guru,
            'jk_kelamin'     => $this->request->getPost('jk_kelamin'),
            'status_guru_id' => $this->request->getPost('status_guru_id'),
            'alamat'         => $this->request->getPost('alamat'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
            'qr_code'        => $image_name,
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($photoName && $photoName != 'default.png' && file_exists(FCPATH . 'assets/img/guru/' . $photoName)) {
                unlink(FCPATH . 'assets/img/guru/' . $photoName);
            }
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/guru', $photoName);

            // --- KUNCI UTAMA: Reset Face Descriptor karena Admin mengunggah foto baru ---
            $updateData['face_descriptor'] = null;
        }

        // Masukkan nama foto ke dalam array data update
        $updateData['photo'] = $photoName;

        // Eksekusi update database guru
        $this->db->table('guru')->where('guru_id', $guru_id)->update($updateData);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/guru');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $row = $this->db->table('guru')->where('guru_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            if ($row->photo && $row->photo != 'default.png' && file_exists(FCPATH . 'assets/img/guru/' . $row->photo)) {
                unlink(FCPATH . 'assets/img/guru/' . $row->photo);
            }
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/guru/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/guru/' . $row->qr_code);
            }

            $this->db->table('guru')->where('guru_id', $row->guru_id)->delete();
            $this->db->table('user')->where('username', $row->nip)->delete();

            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/guru');
    }

    public function preview_excel()
    {
        $this->checkAuth();
        $str = '';
        $file = $this->request->getFile('file_excel');

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
            $inputFileType = IOFactory::identify($inputFileName);
            $objReader = IOFactory::createReader($inputFileType);
            $objExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        $sheet = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;

        for ($i = 3; $i <= count($sheet); $i++) {
            $identity_number = $sheet[$i]['A'];
            $nama_guru       = $sheet[$i]['B'];
            $jenis_kelamin   = $sheet[$i]['C'];
            $status_guru     = $sheet[$i]['D'];
            $alamat          = $sheet[$i]['E'];
            $no_hp           = $sheet[$i]['F'];
            $tempat_lahir    = $sheet[$i]['G'];
            $tanggal_lahir   = $sheet[$i]['H'];
            $password        = $sheet[$i]['I'];

            // Jika baris kosong, lewati
            if (empty($identity_number) && empty($nama_guru)) continue;

            $cekdatadidb = $this->db->table('user')->where('username', $identity_number)->get()->getRow();

            if ($cekdatadidb) {
                // Jika data sudah ada (duplikat), baris ditandai warna merah
                $str .= '<tr class="bg-danger not-allowed-to-insert-excel text-white">
                            <td>' . $identity_number . '</td>
                            <td>' . $nama_guru . '</td>
                            <td>' . $jenis_kelamin . '</td>
                            <td>' . $status_guru . '</td>
                            <td>' . $alamat . '</td>
                            <td>' . $no_hp . '</td>
                            <td>' . $tempat_lahir . '</td>
                            <td>' . $tanggal_lahir . '</td>
                            <td>' . $password . '</td>
                        </tr>';
            } else {
                // Jika data aman untuk di-insert
                $str .= '<tr>
                            <td>' . $identity_number . '<input type="hidden" value="' . $identity_number . '" class="nip" name="nip[]"/></td>
                            <td>' . $nama_guru . '<input type="hidden" value="' . $nama_guru . '" class="nama_guru" name="nama_guru[]"/></td>
                            <td>' . $jenis_kelamin . '<input type="hidden" value="' . $jenis_kelamin . '" class="jenis_kelamin" name="jenis_kelamin[]"/></td>
                            <td>' . $status_guru . '<input type="hidden" value="' . $status_guru . '" class="status_guru" name="status_guru[]"/></td>
                            <td>' . $alamat . '<input type="hidden" value="' . $alamat . '" class="alamat" name="alamat[]"/></td>
                            <td>' . $no_hp . '<input type="hidden" value="' . $no_hp . '" class="no_hp" name="no_hp[]"/></td>
                            <td>' . $tempat_lahir . '<input type="hidden" value="' . $tempat_lahir . '" class="tempat_lahir" name="tempat_lahir[]"/></td>
                            <td>' . $tanggal_lahir . '<input type="hidden" value="' . $tanggal_lahir . '" class="tanggal_lahir" name="tanggal_lahir[]"/></td>
                            <td>' . $password . '<input type="hidden" value="' . $password . '" class="password" name="password[]"/></td>
                        </tr>';
            }
            $numrow++;
        }

        // Hapus file sementara
        if (file_exists($inputFileName)) {
            unlink($inputFileName);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'success',
            'data' => $str
        ]);
    }

    public function insert_all_from_excel()
    {
        $this->checkAuth();

        $nip           = $this->request->getPost('nip');
        $nama_guru     = $this->request->getPost('nama_guru');
        $jenis_kelamin = $this->request->getPost('jenis_kelamin');
        $status_guru   = $this->request->getPost('status_guru');
        $alamat        = $this->request->getPost('alamat');
        $no_hp         = $this->request->getPost('no_hp');
        $tempat_lahir  = $this->request->getPost('tempat_lahir');
        $tanggal_lahir = $this->request->getPost('tanggal_lahir');
        $password      = $this->request->getPost('password');

        if (!$nip) {
            session()->setFlashdata('error', 'Tidak ada data valid yang diimpor.');
            return redirect()->to('/guru');
        }

        foreach ($nip as $key => $value) {
            // Insert User Login
            $this->db->table('user')->insert([
                'password' => sha1($password[$key]),
                'username' => $value,
                'level_id' => 2
            ]);

            // Generate QR Code via API
            $image_name = $value . '_' . str_replace(' ', '_', $nama_guru[$key]) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($value));

            $qrPath = FCPATH . 'assets/img/qr/guru/';
            if (!is_dir($qrPath)) mkdir($qrPath, 0777, true);
            file_put_contents($qrPath . $image_name, $qrData);

            // Insert Data Guru
            $this->db->table('guru')->insert([
                'nip'            => $value,
                'nama_guru'      => $nama_guru[$key],
                'jk_kelamin'     => $jenis_kelamin[$key],
                'status_guru_id' => $status_guru[$key],
                'alamat'         => $alamat[$key],
                'no_hp'          => $no_hp[$key],
                'tempat_lahir'   => $tempat_lahir[$key],
                'tanggal_lahir'  => date('Y-m-d', strtotime($tanggal_lahir[$key])),
                'photo'          => 'default.png',
                'qr_code'        => $image_name,
            ]);
        }

        session()->setFlashdata('message', 'Import Data Excel Berhasil!');
        return redirect()->to('/guru');
    }

    public function cetak($encrypted_id = null)
    {
        $this->checkAuth();

        $real_id = decrypt_url($encrypted_id);
        $row = $this->db->table('guru')->where('guru_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'guru'           => $row,
                'guru_id'        => $row->guru_id ?? '',
                'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nip'            => $row->nip ?? '',
                'qr_code'        => $row->qr_code ?? '',
                'nama_guru'      => $row->nama_guru ?? '',
                'jk_kelamin'     => $row->jk_kelamin ?? '',
                'status_guru_id' => $row->status_guru_id ?? '',
                'alamat'         => $row->alamat ?? '',
                'no_hp'          => $row->no_hp ?? '',
                'tempat_lahir'   => $row->tempat_lahir ?? '',
                'tanggal_lahir'  => $row->tanggal_lahir ?? '',
                'photo'          => $row->photo ?? '',
                'download_mode'  => false, // Format cetak PDF/Browser
            ];

            // Kita arahkan ke view cetak kartu guru yang sama dengan user panel
            return view('guru/cetak', $data);
        } else {
            session()->setFlashdata('error', 'Data Guru Tidak Ditemukan');
            return redirect()->to(site_url('guru'));
        }
    }

    public function download_kartu_img($encrypted_id = null)
    {
        $this->checkAuth();

        $real_id = decrypt_url($encrypted_id);
        $row = $this->db->table('guru')->where('guru_id', $real_id)->get()->getRow();

        if ($row) {
            $data = [
                'guru'           => $row, // Alias objek utama agar view tidak error "Undefined variable"
                'guru_id'        => $row->guru_id ?? '',
                'sett_apps'      => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'nip'            => $row->nip ?? '',
                'qr_code'        => $row->qr_code ?? '',
                'nama_guru'      => $row->nama_guru ?? '',
                'jk_kelamin'     => $row->jk_kelamin ?? '',
                'status_guru_id' => $row->status_guru_id ?? '',
                'alamat'         => $row->alamat ?? '',
                'no_hp'          => $row->no_hp ?? '',
                'tempat_lahir'   => $row->tempat_lahir ?? '',
                'tanggal_lahir'  => $row->tanggal_lahir ?? '',
                'photo'          => $row->photo ?? '',
                'download_mode'  => true, // Penanda agar skrip html2canvas berjalan otomatis
            ];

            // Kita arahkan ke view download kartu yang sudah ada skrip html2canvas-nya
            return view('guru/download_kartu_view', $data);
        } else {
            session()->setFlashdata('error', 'Data Guru Tidak Ditemukan');
            return redirect()->to(site_url('guru'));
        }
    }

    public function update_guru()
    {
        $this->checkAuth();

        // Ambil array guru_id yang dicentang dari checkbox
        $id_guru_array = $this->request->getPost('update');

        if (empty($id_guru_array)) {
            session()->setFlashdata('error', 'Tidak ada data guru yang dipilih.');
            return redirect()->back();
        }

        // =======================================================
        // 1. LOGIKA UNTUK DOWNLOAD MASSAL KARTU (GAMBAR)
        // =======================================================
        if ($this->request->getPost('download') == 'Y') {
            $guru_data = $this->db->table('guru')->whereIn('guru_id', $id_guru_array)->get()->getResult();

            $data = [
                'guru_data' => $guru_data,
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            ];

            // Pastikan Anda membuat/menyesuaikan view ini untuk merender html2canvas massal
            return view('guru/download_kartu_bulk_view', $data);
        }

        // =======================================================
        // 2. LOGIKA UNTUK CETAK MASSAL (PDF / PRINT)
        // =======================================================
        if ($this->request->getPost('cetak') == 'Y') {
            $guru_data = $this->db->table('guru')->whereIn('guru_id', $id_guru_array)->get()->getResult();

            $data = [
                'sett_apps' => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'guru_data' => $guru_data
            ];

            return view('guru/cetak_semua', $data);
        }

        // =======================================================
        // 3. LOGIKA UNTUK HAPUS MASSAL
        // =======================================================
        if ($this->request->getPost('hapus') == 'Y') {
            foreach ($id_guru_array as $id) {
                $guru = $this->db->table('guru')->where('guru_id', $id)->get()->getRow();
                if ($guru) {
                    // Hapus file fisik gambar dan QR Code
                    if ($guru->photo && $guru->photo != 'default.png' && file_exists(FCPATH . 'assets/img/guru/' . $guru->photo)) {
                        unlink(FCPATH . 'assets/img/guru/' . $guru->photo);
                    }
                    if ($guru->qr_code && file_exists(FCPATH . 'assets/img/qr/guru/' . $guru->qr_code)) {
                        unlink(FCPATH . 'assets/img/qr/guru/' . $guru->qr_code);
                    }

                    // Eksekusi Hapus Database User dan Guru
                    $this->db->table('user')->where('username', $guru->nip)->delete();
                    $this->db->table('guru')->where('guru_id', $id)->delete();
                }
            }
            session()->setFlashdata('message', 'Data guru terpilih berhasil dihapus.');
            return redirect()->back();
        }
    }
}
