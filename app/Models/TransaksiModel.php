<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table         = 'transaksi';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['kode_transaksi', 'barang_id', 'jenis', 'jumlah', 'keterangan', 'tanggal'];
    protected $useTimestamps = false;

    /**
     * Ambil semua transaksi beserta nama barang (JOIN)
     */
    public function getAllWithBarang(): array
    {
        return $this->db->table('transaksi t')
            ->select('t.*, b.nama_barang, b.kode_barang, b.satuan')
            ->join('barang b', 'b.id = t.barang_id')
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Generate kode transaksi otomatis: TRX-YYYYMMDD-XXX
     */
    public function generateKode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = $this->like('kode_transaksi', $prefix, 'after')
                       ->orderBy('id', 'DESC')
                       ->first();

        $num = $last ? (int) substr($last['kode_transaksi'], -3) + 1 : 1;
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}