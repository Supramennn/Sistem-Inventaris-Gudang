<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table         = 'transaksi';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kode_transaksi',
        'jenis',
        'keterangan',
        'tanggal',
        'barang_id',
        'jumlah',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'kode_transaksi' => 'required|max_length[30]',
        'jenis'          => 'required|in_list[masuk,keluar]',
        'tanggal'        => 'required|valid_date[Y-m-d]',
    ];

    public function getAllWithTotals(): array
    {
        return $this->selectWithTotals()
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getLatestWithTotals(int $limit = 8): array
    {
        return $this->selectWithTotals()
            ->orderBy('t.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function countByJenis(string $jenis): int
    {
        return $this->where('jenis', $jenis)->countAllResults();
    }

    public function generateKode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = $this->like('kode_transaksi', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $num = $last ? (int) substr($last['kode_transaksi'], -3) + 1 : 1;

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    private function selectWithTotals(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table($this->table . ' t')
            ->select('t.id, t.kode_transaksi, t.jenis, t.keterangan, t.tanggal, t.created_at, t.updated_at')
            ->select('COUNT(td.id) AS total_item', false)
            ->select('COALESCE(SUM(td.jumlah), 0) AS total_jumlah', false)
            ->join('transaksi_detail td', 'td.transaksi_id = t.id', 'left')
            ->groupBy([
                't.id',
                't.kode_transaksi',
                't.jenis',
                't.keterangan',
                't.tanggal',
                't.created_at',
                't.updated_at',
            ]);
    }
}
