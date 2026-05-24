<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table         = 'barang';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'satuan', 'stok'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function isKodeTaken(string $kode, ?int $excludeId = null): bool
    {
        $builder = $this->where('kode_barang', $kode);
        if ($excludeId) {
            $builder = $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }
}
