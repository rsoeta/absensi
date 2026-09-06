<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['username', 'password', 'level_id', 'is_aktif'];

    // Menerjemahkan fungsi un_lock dari CI3
    public function unLock($password)
    {
        // Menggunakan Query Builder standar CI4
        return $this->db->table('halaman_absensi')
            ->where('password_lock_screen', sha1($password))
            ->get()
            ->getRow();
    }

    // Menerjemahkan helper get_data_user
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
