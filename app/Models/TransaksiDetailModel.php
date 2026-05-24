<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiDetailModel extends Model
{
    protected $table         = 'transaksi_detail';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['transaksi_id', 'barang_id', 'jumlah'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'transaksi_id' => 'required|is_natural_no_zero',
        'barang_id'    => 'required|is_natural_no_zero',
        'jumlah'       => 'required|is_natural_no_zero',
    ];

    public function getByTransaksiId(int $transaksiId): array
    {
        return $this->db->table($this->table . ' td')
            ->select('td.*, b.kode_barang, b.nama_barang, b.satuan')
            ->join('barang b', 'b.id = td.barang_id')
            ->where('td.transaksi_id', $transaksiId)
            ->orderBy('td.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getGroupedByTransaksiIds(array $transaksiIds): array
    {
        $transaksiIds = array_values(array_filter(array_map('intval', $transaksiIds)));

        if ($transaksiIds === []) {
            return [];
        }

        $rows = $this->db->table($this->table . ' td')
            ->select('td.*, b.kode_barang, b.nama_barang, b.satuan')
            ->join('barang b', 'b.id = td.barang_id')
            ->whereIn('td.transaksi_id', $transaksiIds)
            ->orderBy('td.id', 'ASC')
            ->get()
            ->getResultArray();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[(int) $row['transaksi_id']][] = $row;
        }

        return $grouped;
    }
}
