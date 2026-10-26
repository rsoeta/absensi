<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pegawai extends BaseController
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
            'pegawai_data' => $this->db->table('pegawai')->get()->getResult(),
            'sett_apps'    => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
        ];
        return view('pegawai/pegawai_list', $data);
    }

    public function create()
    {
        $this->checkAuth();
        $data = [
            'button'            => 'Create',
            'status_pegawai'    => $this->db->table('status_pegawai')->get()->getResult(),
            'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
            'action'            => base_url('pegawai/create_action'),
            'pegawai_id'        => '',
            'nip' => '',
            'nama_pegawai' => '',
            'jk_kelamin' => '',
            'status_pegawai_id' => '',
            'alamat' => '',
            'no_hp' => '',
            'tempat_lahir' => '',
            'tanggal_lahir'     => '',
            'photo' => '',
            'password' => '',
        ];
        return view('pegawai/pegawai_form', $data);
    }

    public function create_action()
    {
        $this->checkAuth();

        if (!$this->validate(['nip' => 'required|is_unique[pegawai.nip]|is_unique[user.username]', 'nama_pegawai' => 'required', 'password' => 'required'])) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NIP unik.');
            return redirect()->to('/pegawai/create');
        }

        $nip = $this->request->getPost('nip');
        $nama_pegawai = $this->request->getPost('nama_pegawai');

        $this->db->table('user')->insert([
            'password' => sha1($this->request->getPost('password')),
            'username' => $nip,
            'level_id' => 3
        ]);

        $image_name = $nip . '_' . str_replace(' ', '_', $nama_pegawai) . '.png';
        $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
        file_put_contents(FCPATH . 'assets/img/qr/pegawai/' . $image_name, $qrData);

        $photoName = 'default.png';
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/pegawai', $photoName);
        }

        $this->db->table('pegawai')->insert([
            'nip'               => $nip,
            'nama_pegawai'      => $nama_pegawai,
            'jk_kelamin'        => $this->request->getPost('jk_kelamin'),
            'status_pegawai_id' => $this->request->getPost('status_pegawai_id'),
            'alamat'            => $this->request->getPost('alamat'),
            'no_hp'             => $this->request->getPost('no_hp'),
            'tempat_lahir'      => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir'),
            'photo'             => $photoName,
            'qr_code'           => $image_name,
        ]);

        session()->setFlashdata('message', 'Create Record Success');
        return redirect()->to('/pegawai');
    }

    public function update($id)
    {
        $this->checkAuth();
        $row = $this->db->table('pegawai')->where('pegawai_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            $data = [
                'button'            => 'Update',
                'status_pegawai'    => $this->db->table('status_pegawai')->get()->getResult(),
                'sett_apps'         => $this->db->table('app_setting')->where('id', 1)->get()->getRow(),
                'action'            => base_url('pegawai/update_action'),
                'pegawai_id'        => $row->pegawai_id,
                'nip'               => $row->nip,
                'nip_lama'          => $row->nip,
                'nama_pegawai'      => $row->nama_pegawai,
                'jk_kelamin'        => $row->jk_kelamin,
                'status_pegawai_id' => $row->status_pegawai_id,
                'alamat'            => $row->alamat,
                'no_hp'             => $row->no_hp,
                'tempat_lahir'      => $row->tempat_lahir,
                'tanggal_lahir'     => $row->tanggal_lahir,
                'photo'             => $row->photo ?: 'default.png',
            ];
            return view('pegawai/pegawai_form', $data);
        }
        return redirect()->to('/pegawai');
    }

    public function update_action()
    {
        $this->checkAuth();
        $pegawai_id = $this->request->getPost('pegawai_id');
        $nip_lama = $this->request->getPost('nip_lama');
        $nip = $this->request->getPost('nip');
        $nama_pegawai = $this->request->getPost('nama_pegawai');

        $rules = ['nama_pegawai' => 'required'];
        if ($nip != $nip_lama) {
            $rules['nip'] = 'required|is_unique[pegawai.nip]|is_unique[user.username]';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal. Pastikan NIP unik.');
            return redirect()->to('/pegawai/update/' . encrypt_url($pegawai_id));
        }

        $userData = ['username' => $nip, 'level_id' => 3];
        if (!empty($this->request->getPost('password'))) {
            $userData['password'] = sha1($this->request->getPost('password'));
        }
        $this->db->table('user')->where('username', $nip_lama)->update($userData);

        $row = $this->db->table('pegawai')->where('pegawai_id', $pegawai_id)->get()->getRow();
        $image_name = $row->qr_code;

        if ($nip != $nip_lama || $nama_pegawai != $row->nama_pegawai) {
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code);
            }
            $image_name = $nip . '_' . str_replace(' ', '_', $nama_pegawai) . '.png';
            $qrData = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($nip));
            file_put_contents(FCPATH . 'assets/img/qr/pegawai/' . $image_name, $qrData);
        }

        $photoName = $this->request->getPost('photo_lama');
        $file = $this->request->getFile('photo');

        // Siapkan array data update untuk tabel pegawai
        $updateData = [
            'nip'               => $nip,
            'nama_pegawai'      => $nama_pegawai,
            'jk_kelamin'        => $this->request->getPost('jk_kelamin'),
            'status_pegawai_id' => $this->request->getPost('status_pegawai_id'),
            'alamat'            => $this->request->getPost('alamat'),
            'no_hp'             => $this->request->getPost('no_hp'),
            'tempat_lahir'      => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir'),
            'qr_code'           => $image_name,
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($photoName && $photoName != 'default.png' && file_exists(FCPATH . 'assets/img/pegawai/' . $photoName)) {
                unlink(FCPATH . 'assets/img/pegawai/' . $photoName);
            }
            $photoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/pegawai', $photoName);

            // --- KUNCI UTAMA: Reset Face Descriptor karena Admin mengunggah foto baru ---
            $updateData['face_descriptor'] = null;
        }

        // Masukkan nama foto ke dalam array data update
        $updateData['photo'] = $photoName;

        // Eksekusi update database pegawai
        $this->db->table('pegawai')->where('pegawai_id', $pegawai_id)->update($updateData);

        session()->setFlashdata('message', 'Update Record Success');
        return redirect()->to('/pegawai');
    }

    public function delete($id)
    {
        $this->checkAuth();
        $row = $this->db->table('pegawai')->where('pegawai_id', decrypt_url($id))->get()->getRow();

        if ($row) {
            if ($row->photo && $row->photo != 'default.png' && file_exists(FCPATH . 'assets/img/pegawai/' . $row->photo)) {
                unlink(FCPATH . 'assets/img/pegawai/' . $row->photo);
            }
            if ($row->qr_code && file_exists(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code)) {
                unlink(FCPATH . 'assets/img/qr/pegawai/' . $row->qr_code);
            }

            $this->db->table('pegawai')->where('pegawai_id', $row->pegawai_id)->delete();
            $this->db->table('user')->where('username', $row->nip)->delete();

            session()->setFlashdata('message', 'Delete Record Success');
        } else {
            session()->setFlashdata('error', 'Record Not Found');
        }
        return redirect()->to('/pegawai');
    }

    public function excel()
    {
        $this->checkAuth();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Setup Header Tabel
        $headers = ['No', 'NIP', 'Nama Pegawai', 'Jenis Kelamin', 'Status Pegawai', 'Alamat', 'No HP', 'Tempat Lahir', 'Tanggal Lahir'];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        $pegawai_data = $this->db->table('pegawai')
            ->select('pegawai.*, status_pegawai.nama_status_pegawai')
            ->join('status_pegawai', 'status_pegawai.status_pegawai_id = pegawai.status_pegawai_id', 'left')
            ->orderBy('pegawai.nama_pegawai', 'ASC')
            ->get()->getResult();

        $no = 1;
        $numrow = 2;

        foreach ($pegawai_data as $data) {
            $sheet->setCellValue('A' . $numrow, $no++);
            $sheet->setCellValueExplicit('B' . $numrow, (string)($data->nip ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $numrow, $data->nama_pegawai ?? '');
            $sheet->setCellValue('D' . $numrow, $data->jk_kelamin ?? '');
            $sheet->setCellValue('E' . $numrow, $data->nama_status_pegawai ?? '');
            $sheet->setCellValue('F' . $numrow, $data->alamat ?? '');
            $sheet->setCellValueExplicit('G' . $numrow, (string)($data->no_hp ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $numrow, $data->tempat_lahir ?? '');
            $sheet->setCellValue('I' . $numrow, $data->tanggal_lahir ?? '');
            $numrow++;
        }

        // Header Styling: Bold, Background Abu Terang
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFE0E0E0');

        // Border styling untuk seluruh tabel jika ada data
        if ($numrow > 2) {
            $sheet->getStyle('A1:I' . ($numrow - 1))->getBorders()->getAllBorders()
                  ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Auto size kolom A sampai I
        foreach (range('A', 'I') as $colLetter) {
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->setTitle("Data Pegawai");

        $filename = 'Data Pegawai ' . date('Y-m-d') . '.xlsx';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
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
            return $this->response->setJSON([
                'status' => 'no',
                'message' => 'Error loading file: ' . $e->getMessage()
            ]);
        }

        $sheet = $objExcel->getActiveSheet()->toArray(null, true, true, true);

        for ($i = 3; $i <= count($sheet); $i++) {
            $identity_number = $sheet[$i]['A'];
            $nama_pegawai    = $sheet[$i]['B'];
            $jenis_kelamin   = $sheet[$i]['C'];
            $status_pegawai  = $sheet[$i]['D'];
            $alamat          = $sheet[$i]['E'];
            $no_hp           = $sheet[$i]['F'];
            $tempat_lahir    = $sheet[$i]['G'];
            $tanggal_lahir   = $sheet[$i]['H'];
            $password        = $sheet[$i]['I'];

            if (empty($identity_number) && empty($nama_pegawai)) continue;

            $cekdatadidb = $this->db->table('user')->where('username', $identity_number)->get()->getRow();

            if ($cekdatadidb) {
                $str .= '<tr class="bg-danger not-allowed-to-insert-excel text-white">
                            <td>' . $identity_number . '</td>
                            <td>' . $nama_pegawai . '</td>
                            <td>' . $jenis_kelamin . '</td>
                            <td>' . $status_pegawai . '</td>
                            <td>' . $alamat . '</td>
                            <td>' . $no_hp . '</td>
                            <td>' . $tempat_lahir . '</td>
                            <td>' . $tanggal_lahir . '</td>
                            <td>' . $password . '</td>
                        </tr>';
            } else {
                $str .= '<tr>
                            <td>' . $identity_number . '<input type="hidden" value="' . $identity_number . '" class="nip" name="nip[]"/></td>
                            <td>' . $nama_pegawai . '<input type="hidden" value="' . $nama_pegawai . '" class="nama_pegawai" name="nama_pegawai[]"/></td>
                            <td>' . $jenis_kelamin . '<input type="hidden" value="' . $jenis_kelamin . '" class="jenis_kelamin" name="jenis_kelamin[]"/></td>
                            <td>' . $status_pegawai . '<input type="hidden" value="' . $status_pegawai . '" class="status_pegawai" name="status_pegawai[]"/></td>
                            <td>' . $alamat . '<input type="hidden" value="' . $alamat . '" class="alamat" name="alamat[]"/></td>
                            <td>' . $no_hp . '<input type="hidden" value="' . $no_hp . '" class="no_hp" name="no_hp[]"/></td>
                            <td>' . $tempat_lahir . '<input type="hidden" value="' . $tempat_lahir . '" class="tempat_lahir" name="tempat_lahir[]"/></td>
                            <td>' . $tanggal_lahir . '<input type="hidden" value="' . $tanggal_lahir . '" class="tanggal_lahir" name="tanggal_lahir[]"/></td>
                            <td>' . $password . '<input type="hidden" value="' . $password . '" class="password" name="password[]"/></td>
                        </tr>';
            }
        }

        if (file_exists($inputFileName)) {
            @unlink($inputFileName);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'data'   => $str
        ]);
    }

    public function insert_all_from_excel()
    {
        $this->checkAuth();

        $nip           = $this->request->getPost('nip');
        $nama_pegawai  = $this->request->getPost('nama_pegawai');
        $jenis_kelamin = $this->request->getPost('jenis_kelamin');
        $status_pegawai= $this->request->getPost('status_pegawai');
        $alamat        = $this->request->getPost('alamat');
        $no_hp         = $this->request->getPost('no_hp');
        $tempat_lahir  = $this->request->getPost('tempat_lahir');
        $tanggal_lahir = $this->request->getPost('tanggal_lahir');
        $password      = $this->request->getPost('password');

        if (!$nip) {
            session()->setFlashdata('error', 'Tidak ada data valid yang diimpor.');
            return redirect()->to('/pegawai');
        }

        foreach ($nip as $key => $value) {
            // Insert User Login (level 3 = Pegawai)
            $this->db->table('user')->insert([
                'password' => sha1($password[$key]),
                'username' => $value,
                'level_id' => 3
            ]);

            // Generate QR Code
            $image_name = $value . '_' . str_replace(' ', '_', $nama_pegawai[$key]) . '.png';
            $qrPath = FCPATH . 'assets/img/qr/pegawai/';
            if (!is_dir($qrPath)) mkdir($qrPath, 0777, true);

            $qrData = @file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($value));
            if ($qrData) {
                file_put_contents($qrPath . $image_name, $qrData);
            }

            // Insert Data Pegawai
            $this->db->table('pegawai')->insert([
                'nip'               => $value,
                'nama_pegawai'      => $nama_pegawai[$key],
                'jk_kelamin'        => ($jenis_kelamin[$key] == 'Laki-laki' || $jenis_kelamin[$key] == 'L') ? 'L' : 'P',
                'status_pegawai_id' => $status_pegawai[$key],
                'alamat'            => $alamat[$key],
                'no_hp'             => $no_hp[$key],
                'tempat_lahir'      => $tempat_lahir[$key],
                'tanggal_lahir'     => !empty($tanggal_lahir[$key]) ? date('Y-m-d', strtotime($tanggal_lahir[$key])) : null,
                'photo'             => 'default.png',
                'qr_code'           => $image_name,
            ]);
        }

        session()->setFlashdata('message', 'Import Data Excel Berhasil!');
        return redirect()->to('/pegawai');
    }
}