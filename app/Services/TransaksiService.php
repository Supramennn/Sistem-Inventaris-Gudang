<?php

namespace App\Services;

use App\Models\BarangModel;
use App\Models\TransaksiDetailModel;
use App\Models\TransaksiModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Model;
use Config\Database;
use RuntimeException;
use Throwable;

class TransaksiService
{
    private BaseConnection $db;
    private BarangModel $barangModel;
    private TransaksiModel $transaksiModel;
    private TransaksiDetailModel $detailModel;

    public function __construct()
    {
        $this->db             = Database::connect();
        $this->barangModel    = new BarangModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel    = new TransaksiDetailModel();
    }

    public function create(array $header, array $items): int
    {
        $items = $this->normalizeItems($items);

        $this->db->transBegin();

        try {
            $this->assertStockAvailable($header['jenis'], $items);

            $transaksiId = $this->transaksiModel->insert(
                $this->withLegacyColumns($header, $items),
                true
            );

            if (! $transaksiId) {
                throw new RuntimeException($this->firstModelError($this->transaksiModel, 'Transaksi gagal disimpan.'));
            }

            $this->insertDetails((int) $transaksiId, $items);
            $this->applyStock($header['jenis'], $items);

            $this->finishTransaction();

            return (int) $transaksiId;
        } catch (Throwable $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    public function update(int $transaksiId, array $header, array $items): void
    {
        $items     = $this->normalizeItems($items);
        $transaksi = $this->transaksiModel->find($transaksiId);

        if (! $transaksi) {
            throw new RuntimeException('Transaksi tidak ditemukan.');
        }

        $oldItems = $this->getItems($transaksiId, $transaksi);

        $this->db->transBegin();

        try {
            $this->rollbackStock($transaksi['jenis'], $oldItems);
            $this->assertStockAvailable($header['jenis'], $items);

            if (! $this->transaksiModel->update($transaksiId, $this->withLegacyColumns($header, $items))) {
                throw new RuntimeException($this->firstModelError($this->transaksiModel, 'Transaksi gagal diperbarui.'));
            }

            $this->detailModel->where('transaksi_id', $transaksiId)->delete();
            $this->insertDetails($transaksiId, $items);
            $this->applyStock($header['jenis'], $items);

            $this->finishTransaction();
        } catch (Throwable $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    public function delete(int $transaksiId): void
    {
        $transaksi = $this->transaksiModel->find($transaksiId);

        if (! $transaksi) {
            throw new RuntimeException('Transaksi tidak ditemukan.');
        }

        $items = $this->getItems($transaksiId, $transaksi);

        $this->db->transBegin();

        try {
            $this->rollbackStock($transaksi['jenis'], $items);
            $this->detailModel->where('transaksi_id', $transaksiId)->delete();
            $this->transaksiModel->delete($transaksiId);

            $this->finishTransaction();
        } catch (Throwable $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    public function getItems(int $transaksiId, ?array $legacyHeader = null): array
    {
        $items = $this->detailModel->getByTransaksiId($transaksiId);

        if ($items !== [] || ! $legacyHeader) {
            return $items;
        }

        if (! isset($legacyHeader['barang_id'], $legacyHeader['jumlah'])) {
            return [];
        }

        return [[
            'transaksi_id' => $transaksiId,
            'barang_id'    => (int) $legacyHeader['barang_id'],
            'jumlah'       => (int) $legacyHeader['jumlah'],
        ]];
    }

    private function normalizeItems(array $items): array
    {
        $grouped = [];

        foreach ($items as $item) {
            $barangId = (int) ($item['barang_id'] ?? 0);
            $jumlah   = (int) ($item['jumlah'] ?? 0);

            if ($barangId <= 0 || $jumlah <= 0) {
                continue;
            }

            $grouped[$barangId] = ($grouped[$barangId] ?? 0) + $jumlah;
        }

        if ($grouped === []) {
            throw new RuntimeException('Minimal satu barang harus diisi.');
        }

        $normalized = [];
        foreach ($grouped as $barangId => $jumlah) {
            $normalized[] = [
                'barang_id' => (int) $barangId,
                'jumlah'    => (int) $jumlah,
            ];
        }

        return $normalized;
    }

    private function assertStockAvailable(string $jenis, array $items): void
    {
        foreach ($items as $item) {
            $barang = $this->barangModel->find($item['barang_id']);

            if (! $barang) {
                throw new RuntimeException('Barang tidak ditemukan.');
            }

            if ($jenis === 'keluar' && (int) $barang['stok'] < $item['jumlah']) {
                throw new RuntimeException(sprintf(
                    'Stok %s tidak cukup. Tersedia: %s %s.',
                    $barang['nama_barang'],
                    $barang['stok'],
                    $barang['satuan']
                ));
            }
        }
    }

    private function insertDetails(int $transaksiId, array $items): void
    {
        foreach ($items as $item) {
            $saved = $this->detailModel->insert([
                'transaksi_id' => $transaksiId,
                'barang_id'    => $item['barang_id'],
                'jumlah'       => $item['jumlah'],
            ]);

            if (! $saved) {
                throw new RuntimeException($this->firstModelError($this->detailModel, 'Detail transaksi gagal disimpan.'));
            }
        }
    }

    private function applyStock(string $jenis, array $items): void
    {
        $direction = $jenis === 'masuk' ? 1 : -1;

        foreach ($items as $item) {
            $this->changeStock($item['barang_id'], $direction * $item['jumlah']);
        }
    }

    private function rollbackStock(string $jenis, array $items): void
    {
        $direction = $jenis === 'masuk' ? -1 : 1;

        foreach ($items as $item) {
            $this->changeStock((int) $item['barang_id'], $direction * (int) $item['jumlah']);
        }
    }

    private function changeStock(int $barangId, int $delta): void
    {
        $barang = $this->barangModel->find($barangId);

        if (! $barang) {
            throw new RuntimeException('Barang tidak ditemukan.');
        }

        $stokBaru = (int) $barang['stok'] + $delta;

        if ($stokBaru < 0) {
            throw new RuntimeException('Stok tidak boleh kurang dari nol.');
        }

        if (! $this->barangModel->update($barangId, ['stok' => $stokBaru])) {
            throw new RuntimeException($this->firstModelError($this->barangModel, 'Stok barang gagal diperbarui.'));
        }
    }

    private function withLegacyColumns(array $header, array $items): array
    {
        $firstItem = $items[0];

        if ($this->db->fieldExists('barang_id', 'transaksi')) {
            $header['barang_id'] = $firstItem['barang_id'];
        }

        if ($this->db->fieldExists('jumlah', 'transaksi')) {
            $header['jumlah'] = array_sum(array_column($items, 'jumlah'));
        }

        return $header;
    }

    private function finishTransaction(): void
    {
        if ($this->db->transStatus() === false) {
            throw new RuntimeException('Proses database gagal.');
        }

        $this->db->transCommit();
    }

    private function firstModelError(Model $model, string $fallback): string
    {
        $errors = $model->errors();

        return $errors === [] ? $fallback : reset($errors);
    }
}
