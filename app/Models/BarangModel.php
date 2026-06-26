<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table         = 'barang';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kode_barang',
        'nama_barang',
        'deskripsi',
        'stok',
        'stok_minimum',
        'harga',
        'kategori_id',
        'satuan_id',
        'supplier_id',
        'gudang_id',
    ];
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

    public function generateKode(): string
    {
        $prefix = 'BRG-';
        $last   = $this->like('kode_barang', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $num = $last ? (int) substr($last['kode_barang'], strlen($prefix)) + 1 : 1;

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function getAllWithRelations(): array
    {
        return $this->selectWithRelations()
            ->orderBy('b.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getCriticalStock(): array
    {
        return $this->selectWithRelations()
            ->where('b.stok <= b.stok_minimum', null, false)
            ->orderBy('b.stok', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function selectWithRelations(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*')
            ->select('k.nama_kategori')
            ->select('s.nama_satuan, s.singkatan')
            ->select('sp.nama_supplier')
            ->select('g.nama_gudang')
            ->select("COALESCE(NULLIF(s.singkatan, ''), s.nama_satuan) AS satuan", false)
            ->join('kategori k', 'k.id = b.kategori_id', 'left')
            ->join('satuan s', 's.id = b.satuan_id', 'left')
            ->join('supplier sp', 'sp.id = b.supplier_id', 'left')
            ->join('gudang g', 'g.id = b.gudang_id', 'left');
    }
}
