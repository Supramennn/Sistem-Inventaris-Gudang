<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'user';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'username', 'password', 'role', 'is_active'];

    public function findByUsername(string $username): array|null
    {
        return $this->where('username', $username)
            ->where('is_active', 1)
            ->first();
    }
}
