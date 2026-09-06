<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenModel extends Model
{
    protected $table            = 'absen';
    protected $primaryKey       = 'absen_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // Agar balikan datanya berupa object seperti CI3 ($row->nama)
    protected $allowedFields    = [
        'user_id',
        'tanggal',
        'keterangan',
        'jam_masuk',
        'jam_pulang',
        'status',
        'status_masuk',
        'status_pulang',
        'point',
        'point_pulang',
        'is_geolocation',
        'tanggal_data_masuk'
    ];

    // Menerjemahkan fungsi get_all_absen_today dari CI3 ke Query Builder CI4
    public function getAllAbsenToday($date)
    {
        return $this->select('absen.*, user.username, user.level_id')
            ->join('user', 'user.user_id = absen.user_id', 'left')
            ->where('absen.tanggal', $date)
            ->orderBy('absen.tanggal_data_masuk', 'DESC')
            ->limit(10)
            ->find();
    }

    // Menerjemahkan fungsi cek_data_absen_masuk
    public function cekDataAbsenMasuk($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('tanggal', date('Y-m-d'))
            ->first();
    }

    // Menerjemahkan fungsi cek_data_absen_keluar
    public function cekDataAbsenKeluar($user_id)
    {
        return $this->where('user_id', $user_id)
            ->where('jam_pulang', null)
            ->where('tanggal', date('Y-m-d'))
            ->first();
    }
}
