<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table         = 'barang';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'satuan', 'stok'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Cek apakah kode_barang sudah ada (untuk validasi unik saat edit)
     * @param string $kode
     * @param int|null $excludeId
     */
    public function isKodeTaken(string $kode, ?int $excludeId = null): bool
    {
        $builder = $this->where('kode_barang', $kode);
        if ($excludeId) {
            $builder = $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }
}