<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table         = 'supplier';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['kode_supplier', 'nama_supplier', 'alamat', 'telepon', 'email'];

    public function generateKode(): string
    {
        $prefix = 'SUP-';
        $last   = $this->like('kode_supplier', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $num = $last ? (int) substr($last['kode_supplier'], strlen($prefix)) + 1 : 1;

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
