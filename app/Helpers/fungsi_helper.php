<?php

date_default_timezone_set('Asia/Jakarta');

if (!function_exists('encrypt_url')) {
    function encrypt_url($string)
    {
        // Menyandikan data dan mengganti karakter yang tidak ramah URL
        return strtr(base64_encode($string), '+/=', '-_~');
    }
}

if (!function_exists('decrypt_url')) {
    function decrypt_url($string)
    {
        // Mengembalikan karakter asli lalu men-decode
        return base64_decode(strtr($string, '-_~', '+/='));
    }
}

function check_already_login()
{
    if (session()->get('userid')) {
        header('Location: ' . base_url('dashboard'));
        exit;
    }
}

// untuk semua ctrl cek seesion login dan session unit
function is_login()
{
    if (!session()->get('userid')) {
        header('Location: ' . base_url('auth'));
        exit;
    }
}

function check_admin()
{
    $db = \Config\Database::connect();
    $userid = session()->get('userid');

    if (!$userid) {
        header('Location: ' . base_url('auth'));
        exit;
    }

    $user = $db->table('user')->where('user_id', $userid)->get()->getRow();
    if ($user && $user->level_id != 1) {
        header('Location: ' . base_url('dashboard_user'));
        exit;
    }
}

// untuk bagian dashboard saja
function cek_login_aja()
{
    if (!session()->get('userid')) {
        header('Location: ' . base_url('auth'));
        exit;
    }
}

function hari_ini()
{
    $hari = date("D");
    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;
        case 'Mon':
            $hari_ini = "Senin";
            break;
        case 'Tue':
            $hari_ini = "Selasa";
            break;
        case 'Wed':
            $hari_ini = "Rabu";
            break;
        case 'Thu':
            $hari_ini = "Kamis";
            break;
        case 'Fri':
            $hari_ini = "Jumat";
            break;
        case 'Sat':
            $hari_ini = "Sabtu";
            break;
        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }
    return $hari_ini;
}

function nama_bulan($bulan)
{
    switch ($bulan) {
        case '1':
            $bulan = "Januari";
            break;
        case '2':
            $bulan = "Februari";
            break;
        case '3':
            $bulan = "Maret";
            break; // Diperbaiki dari "Marert"
        case '4':
            $bulan = "April";
            break;
        case '5':
            $bulan = "Mei";
            break;
        case '6':
            $bulan = "Juni";
            break;
        case '7':
            $bulan = "Juli";
            break;
        case '8':
            $bulan = "Agustus";
            break;
        case '9':
            $bulan = "September";
            break;
        case '10':
            $bulan = "Oktober";
            break;
        case '11':
            $bulan = "November";
            break;
        case '12':
            $bulan = "Desember";
            break;
        default:
            $bulan = "Tidak di ketahui";
            break;
    }
    return $bulan;
}

// return nama photo
function photo_guru($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join guru on guru.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return (empty($data->photo)) ? 'default.png' : $data->photo;
}

function photo_pegawai($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join pegawai on pegawai.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return (empty($data->photo)) ? 'default.png' : $data->photo;
}

function photo_siswa($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join siswa on siswa.nisn = user.username where user.user_id='" . $user_id . "'")->getRow();
    return (empty($data->photo)) ? 'default.png' : $data->photo;
}

// return nama
function nama_guru($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join guru on guru.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->nama_guru : 'Tidak di ketahui';
}

function nama_pegawai($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join pegawai on pegawai.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->nama_pegawai : 'Tidak di ketahui';
}

function data_guru($guru_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM guru where guru_id='" . $guru_id . "'")->getRow();
    return $data ? $data : 'Tidak di ketahui';
}

function data_kelas($kelas_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM kelas where kelas_id='" . $kelas_id . "'")->getRow();
    return $data ? $data : 'Tidak di ketahui';
}

function ketAbsenMapel($set_mapel_id, $tanggal)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT tanggal,set_mapel_id,keterangan,COUNT(keterangan) as jumlah FROM absen_mapel WHERE set_mapel_id='$set_mapel_id' AND tanggal='$tanggal' GROUP BY keterangan;")->getResult();
    $output = '';
    foreach ($data as $row) {
        $output .= '<li>Jumlah ' . $row->keterangan . ' : ' . $row->jumlah . ' Siswa </li>';
    }
    return $output;
}

function ketAbsenMapelPeminatan($mapel_peminatan_id, $tanggal)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT tanggal,mapel_peminatan_id,keterangan,COUNT(keterangan) as jumlah FROM absen_mapel_peminatan WHERE mapel_peminatan_id='$mapel_peminatan_id' AND tanggal='$tanggal' GROUP BY keterangan;")->getResult();
    $output = '';
    foreach ($data as $row) {
        $output .= '<li>Jumlah ' . $row->keterangan . ' : ' . $row->jumlah . ' Siswa </li>';
    }
    return $output;
}

function cekH($set_mapel_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel where set_mapel_id ='$set_mapel_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='H'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekA($set_mapel_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel where set_mapel_id ='$set_mapel_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='A'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekI($set_mapel_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel where set_mapel_id ='$set_mapel_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='I'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekS($set_mapel_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel where set_mapel_id ='$set_mapel_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='S'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekB($set_mapel_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel where set_mapel_id ='$set_mapel_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='B'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekH_mapel_peminatan($mapel_peminatan_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id ='$mapel_peminatan_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='H'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekA_mapel_peminatan($mapel_peminatan_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id ='$mapel_peminatan_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='A'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekI_mapel_peminatan($mapel_peminatan_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id ='$mapel_peminatan_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='I'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekS_mapel_peminatan($mapel_peminatan_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id ='$mapel_peminatan_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='S'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function cekB_mapel_peminatan($mapel_peminatan_id, $tanggal, $siswa_id)
{
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id ='$mapel_peminatan_id' and tanggal ='$tanggal' and siswa_id ='$siswa_id' and keterangan='B'")->getResult();
    return count($query) > 0 ? "checked" : "";
}

function nama_siswa($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join siswa on siswa.nisn = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->nama_siswa : "Error please delete user";
}

function no_hp_wali($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join siswa on siswa.nisn = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->no_hp_wali_siswa : null;
}

function no_hp_walas($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT guru.no_hp FROM user
    join siswa on siswa.nisn = user.username
    join kelas on kelas.kelas_id = siswa.kelas_id
    join guru on guru.guru_id = kelas.walikelas
    where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->no_hp : null;
}

// return id
function guru_id($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join guru on guru.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->guru_id : null;
}

function pegawai_id($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join pegawai on pegawai.nip = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->pegawai_id : null;
}

function siswa_id($user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM user join siswa on siswa.nisn = user.username where user.user_id='" . $user_id . "'")->getRow();
    return $data ? $data->siswa_id : null;
}

function cek_absen_mapel($set_mapel_id, $siswa_id, $tanggal)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa_id and tanggal='$tanggal'")->getRow();
    return $data ? "<td>" . $data->keterangan . "</td>" : "<td></td>";
}

function cek_absen_mapel_peminatan($mapel_peminatan_id, $siswa_id, $tanggal)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa_id and tanggal='$tanggal'")->getRow();
    return $data ? "<td>" . $data->keterangan . "</td>" : "<td></td>";
}

function cek_absen($user_id, $tgl, $result_holidaydate)
{
    $tglparsed = date('Y-m-d', strtotime($tgl));
    $is_minggu = date('l', strtotime($tgl));

    // 1. Cek Hari Minggu
    if ($is_minggu == 'Sunday') {
        return "<td style='background-color: red'></td>";
    }

    // 2. Cek Hari Libur dari Database Lokal
    $cek_hari_libur_db = hari_libur($tglparsed);
    if ($cek_hari_libur_db) {
        return "<td class='plsholder' data-detail='" . $cek_hari_libur_db->keterangan . "' style='background-color: yellow'></td>";
    }

    // 3. Cek Hari Libur Nasional dari API (Reset variabel penanda per tanggal)
    $is_api_holiday = false;
    $holidayname = '';

    if (!empty($result_holidaydate) && is_array($result_holidaydate)) {
        foreach ($result_holidaydate as $value) {
            if ($value->holiday_date == $tglparsed && isset($value->is_national_holiday) && $value->is_national_holiday == true) {
                $is_api_holiday = true;
                $holidayname = $value->holiday_name ?? 'Hari Libur Nasional';
                break;
            }
        }
    }

    if ($is_api_holiday) {
        return "<td class='plsholder' data-detail='" . $holidayname . "' style='background-color: yellow'></td>";
    }

    // 4. Cek Status Kehadiran Siswa
    $cek_status = absensi_user($user_id, $tglparsed);

    if ($cek_status == 'Tepat')      return "<td>✓</td>";
    if ($cek_status == 'Alpha')      return "<td>A</td>";
    if ($cek_status == 'Kosong')     return "<td>-</td>";
    if ($cek_status == 'Sakit')      return "<td>S</td>";
    if ($cek_status == 'Izin')       return "<td>I</td>";
    if ($cek_status == 'Terlambat')  return "<td style='background-color: grey'>✓</td>";
    if ($cek_status == 'Bolos')      return "<td style='background-color: #5353ec'>B</td>";

    return "<td>-</td>"; // Fallback aman jika status tidak dikenali
}
function hari_libur($tgl)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM hari_libur where date(tanggal)='" . $tgl . "'")->getRow();
    return $data ? $data : false;
}

function absensi_user($user_id, $tgl)
{
    $date_now = date('Y-m-d');
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen WHERE user_id='" . $user_id . "' AND DATE(tanggal)='" . $tgl . "'")->getRow();

    if ($data) {
        $keterangan = $data->keterangan;
        if ($keterangan == 'Masuk') {
            if (empty($data->jam_pulang)) {
                return "Bolos";
            }
            if ($data->status_masuk == 'Tepat Waktu') {
                return "Tepat";
            } else {
                return "Terlambat";
            }
        } else {
            return $data->keterangan;
        }
    } else {
        if ($date_now < $tgl) {
            return "Kosong";
        } else {
            return "Alpha";
        }
    }
}

function cek_alpha($user_id, $param1, $param2, $param3 = [])
{
    $db = \Config\Database::connect();

    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
        $result_holidaydate = $param3;
    } else {
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
        $result_holidaydate = $param4 ?? [];
    }

    $date_now = date('Y-m-d');
    if ($tgl_akhir > $date_now) {
        $tgl_akhir = $date_now;
    }
    if ($tgl_awal > $date_now) {
        return 0;
    }

    // Ambil hari libur database sekaligus untuk efisiensi query (mencegah query berulang di dalam loop)
    $queryLibur = $db->query("SELECT tanggal FROM hari_libur WHERE tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'")->getResult();
    $tanggalLiburDb = array_column($queryLibur, 'tanggal');

    $begin = new DateTime($tgl_awal);
    $end = new DateTime($tgl_akhir);
    $end->modify('+1 day');
    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

    $valid_working_days = 0;

    foreach ($daterange as $date) {
        $d = $date->format("Y-m-d");
        $dayOfWeek = $date->format("w"); // 0 = Minggu

        if ($dayOfWeek == 0) continue;
        if (in_array($d, $tanggalLiburDb)) continue;

        $is_api_holiday = false;
        if (!empty($result_holidaydate) && is_array($result_holidaydate)) {
            foreach ($result_holidaydate as $value) {
                if (isset($value->holiday_date) && $value->holiday_date == $d && !empty($value->is_national_holiday)) {
                    $is_api_holiday = true;
                    break;
                }
            }
        }
        if ($is_api_holiday) continue;

        $valid_working_days++;
    }

    $total_recorded = $db->table('absen')
        ->where('user_id', $user_id)
        ->where('tanggal >=', $tgl_awal)
        ->where('tanggal <=', $tgl_akhir)
        ->countAllResults();

    $alpha = $valid_working_days - $total_recorded;
    return $alpha < 0 ? 0 : $alpha;
}

function cek_sakit($user_id, $param1, $param2, $param3 = null)
{
    $db = \Config\Database::connect();

    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
    } else {
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
    }

    return $db->table('absen')
        ->where('user_id', $user_id)
        ->where('keterangan', 'Sakit')
        ->where('tanggal >=', $tgl_awal)
        ->where('tanggal <=', $tgl_akhir)
        ->countAllResults();
}

function cek_izin($user_id, $param1, $param2, $param3 = null)
{
    $db = \Config\Database::connect();

    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
    } else {
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
    }

    return $db->table('absen')
        ->where('user_id', $user_id)
        ->where('keterangan', 'Izin')
        ->where('tanggal >=', $tgl_awal)
        ->where('tanggal <=', $tgl_akhir)
        ->countAllResults();
}

// Catatan: Terapkan format "if (strpos($param1, '-') !== false)" yang sama persis 
// ke fungsi cek_hadir_tepat, cek_terlambat, dan cek_bolos di file helper Anda.
function cek_hadir_tepat($user_id, $param1, $param2, $param3 = null)
{
    $db = \Config\Database::connect();

    // Deteksi Cerdas: Jika param1 mengandung '-' berarti format rentang tanggal
    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
    } else {
        // Format Lama (hari, bulan, tahun)
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
    }

    return $db->table('absen')
        ->where('user_id', $user_id)
        ->where('keterangan', 'Masuk')
        ->where('status_masuk', 'Tepat Waktu')
        ->where('jam_pulang IS NOT NULL', null, false)
        ->where('DATE(tanggal) >=', $tgl_awal)
        ->where('DATE(tanggal) <=', $tgl_akhir)
        ->countAllResults();
}

function cek_terlambat($user_id, $param1, $param2, $param3 = null)
{
    $db = \Config\Database::connect();

    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
    } else {
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
    }

    return $db->table('absen')
        ->where('user_id', $user_id)
        ->where('keterangan', 'Masuk')
        ->where('status_masuk', 'Terlambat')
        ->where('jam_pulang IS NOT NULL', null, false)
        ->where('DATE(tanggal) >=', $tgl_awal)
        ->where('DATE(tanggal) <=', $tgl_akhir)
        ->countAllResults();
}

function cek_bolos($user_id, $param1, $param2, $param3 = null)
{
    $db = \Config\Database::connect();

    if (strpos($param1, '-') !== false) {
        $tgl_awal = $param1;
        $tgl_akhir = $param2;
    } else {
        $tgl_awal = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-01';
        $tgl_akhir = $param3 . '-' . str_pad($param2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($param1, 2, '0', STR_PAD_LEFT);
    }

    return $db->table('absen')
        ->where('user_id', $user_id)
        ->where('keterangan', 'Masuk')
        ->where('jam_pulang IS NULL', null, false)
        ->where('DATE(tanggal) >=', $tgl_awal)
        ->where('DATE(tanggal) <=', $tgl_akhir)
        ->countAllResults();
}

function cek_terlambat_panggilan($user_id, $tgl_awal, $tgl_akhir)
{
    $db = \Config\Database::connect();
    if (empty($tgl_awal)) {
        $data = $db->query("SELECT * FROM absen where user_id='44' and keterangan='Masuk' and status_masuk='Terlambat' and tanggal <= '" . $tgl_akhir . "' ")->getResult();
    } else {
        $data = $db->query("SELECT * FROM absen where user_id='" . $user_id . "' and keterangan='Masuk' and status_masuk='Terlambat' and tanggal >= '" . $tgl_awal . "' and tanggal <= '" . $tgl_akhir . "' ")->getResult();
    }
    return count($data);
}

function hitung_alpha($tanggal_awal, $tanggal_sekarang, $user_id)
{
    $db = \Config\Database::connect();
    $tgl_awal = $tanggal_awal;
    $tgl_akhir = $tanggal_sekarang;

    $date1 = date('d-m-Y', strtotime($tgl_awal));
    $date2 = date('d-m-Y', strtotime($tgl_akhir));

    $pecahTgl1 = explode("-", $date1);
    $tgl1 = $pecahTgl1[0];
    $bln1 = $pecahTgl1[1];
    $thn1 = $pecahTgl1[2];

    $i = 0;
    $minggu = 0;

    do {
        $tanggal = date("d-m-Y", mktime(0, 0, 0, (int)$bln1, (int)$tgl1 + $i, (int)$thn1));
        if (date("w", mktime(0, 0, 0, (int)$bln1, (int)$tgl1 + $i, (int)$thn1)) == 0) {
            $minggu++;
        }
        $i++;
    } while ($tanggal != $date2);

    $libur_query = $db->query("SELECT * FROM hari_libur where tanggal >= '" . $tgl_awal . "' and tanggal <= '" . $tgl_akhir . "' ")->getResult();
    $libur = count($libur_query);

    $masuk_query = $db->query("SELECT * FROM absen where user_id='" . $user_id . "' and keterangan='Masuk'")->getResult();
    $masuk = count($masuk_query);

    return $i - $minggu - $libur - $masuk;
}

function hitung_terlambat($tanggal_awal, $tanggal_sekarang, $user_id)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen where user_id='" . $user_id . "' and keterangan='Masuk' and status_masuk='Terlambat' and date(tanggal) >= '" . $tanggal_awal . "' and date(tanggal) <= '" . $tanggal_sekarang . "' ")->getResult();
    return count($data);
}

function cek_tahun_pelajaran_berjalan()
{
    $db = \Config\Database::connect();
    $datenow = date('Y-m-d');
    $data = $db->query("SELECT * FROM tahun_ajaran where tgl_awal <= '" . $datenow . "' and tgl_akhir >= '" . $datenow . "' ")->getRow();
    return $data ? $data : 0;
}

function cek_tahun()
{
    $db = \Config\Database::connect();
    $datenow = date('Y-m-d');
    $data = $db->query("SELECT * FROM tahun_ajaran where tgl_awal <= '" . $datenow . "' and tgl_akhir >= '" . $datenow . "' ")->getRow();
    return $data ? $data->tahun_ajaran_id : 0;
}

function cek_hadir_mapel($set_mapel_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa and keterangan='H'")->getResult();
    return count($data);
}

function cek_alpha_mapel($set_mapel_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa and keterangan='A'")->getResult();
    return count($data);
}

function cek_ijin_mapel($set_mapel_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa and keterangan='I'")->getResult();
    return count($data);
}

function cek_sakit_mapel($set_mapel_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa and keterangan='S'")->getResult();
    return count($data);
}

function cek_bolos_mapel($set_mapel_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel where set_mapel_id=$set_mapel_id and siswa_id=$siswa and keterangan='B'")->getResult();
    return count($data);
}

function cek_hadir_mapel_peminatan($mapel_peminatan_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa and keterangan='H'")->getResult();
    return count($data);
}

function cek_alpha_mapel_peminatan($mapel_peminatan_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa and keterangan='A'")->getResult();
    return count($data);
}

function cek_ijin_mapel_peminatan($mapel_peminatan_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa and keterangan='I'")->getResult();
    return count($data);
}

function cek_sakit_mapel_peminatan($mapel_peminatan_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa and keterangan='S'")->getResult();
    return count($data);
}

function cek_bolos_mapel_peminatan($mapel_peminatan_id, $siswa)
{
    $db = \Config\Database::connect();
    $data = $db->query("SELECT * FROM absen_mapel_peminatan where mapel_peminatan_id=$mapel_peminatan_id and siswa_id=$siswa and keterangan='B'")->getResult();
    return count($data);
}

function hitung_umur($tanggal_lahir)
{
    $birthDate = new DateTime($tanggal_lahir);
    $today = new DateTime("today");
    if ($birthDate > $today) {
        exit("0 tahun 0 bulan 0 hari");
    }
    $y = $today->diff($birthDate)->y;
    $m = $today->diff($birthDate)->m;
    $d = $today->diff($birthDate)->d;
    return $y . " tahun " . $m . " bulan " . $d . " hari";
}
