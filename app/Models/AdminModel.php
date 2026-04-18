<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_admin', 'username', 'password'];

    /**
     * Cari admin berdasarkan username
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): array|null
    {
        return $this->where('username', $username)->first();
    }
}