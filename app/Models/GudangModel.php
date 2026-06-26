<?php

namespace App\Models;

use CodeIgniter\Model;

class GudangModel extends Model
{
    protected $table         = 'gudang';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['kode_gudang', 'nama_gudang', 'lokasi'];

    public function generateKode(): string
    {
        $prefix = 'GDG-';
        $last   = $this->like('kode_gudang', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $num = $last ? (int) substr($last['kode_gudang'], strlen($prefix)) + 1 : 1;

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
