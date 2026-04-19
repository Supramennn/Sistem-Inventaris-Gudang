<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\BarangModel;
use CodeIgniter\Controller;

class Transaksi extends Controller
{
    protected TransaksiModel $transaksiModel;
    protected BarangModel    $barangModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel    = new BarangModel();
        helper(['form', 'url']);
    }

    // READ
    public function index(): string
    {
        $data = [
            'title'      => 'Data Transaksi',
            'transaksi'  => $this->transaksiModel->getAllWithBarang(),
        ];
        return view('transaksi/index', $data);
    }

    // CREATE - form
    public function create(): string
    {
        $data = [
            'title'  => 'Tambah Transaksi',
            'barang' => $this->barangModel->findAll(),
            'kode'   => $this->transaksiModel->generateKode(),
        ];
        return view('transaksi/create', $data);
    }

    // CREATE - proses simpan
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $barangId = (int) $this->request->getPost('barang_id');
        $jenis    = $this->request->getPost('jenis');
        $jumlah   = (int) $this->request->getPost('jumlah');

        // Validasi stok cukup saat keluar
        if ($jenis === 'keluar') {
            $barang = $this->barangModel->find($barangId);
            if ($barang['stok'] < $jumlah) {
                return redirect()->back()->withInput()
                    ->with('error', "Stok tidak cukup! Stok tersedia: {$barang['stok']} {$barang['satuan']}");
            }
        }

        // Simpan transaksi
        $this->transaksiModel->insert([
            'kode_transaksi' => $this->request->getPost('kode_transaksi'),
            'barang_id'      => $barangId,
            'jenis'          => $jenis,
            'jumlah'         => $jumlah,
            'keterangan'     => $this->request->getPost('keterangan'),
            'tanggal'        => $this->request->getPost('tanggal'),
        ]);

        // Update stok otomatis
        $this->updateStok($barangId, $jenis, $jumlah);

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil disimpan!');
    }

    // UPDATE - form
    public function edit(int $id): string
    {
        $data = [
            'title'      => 'Edit Transaksi',
            'transaksi'  => $this->transaksiModel->find($id),
            'barang'     => $this->barangModel->findAll(),
        ];
        return view('transaksi/edit', $data);
    }

    // UPDATE - proses
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $lama     = $this->transaksiModel->find($id);
        $barangId = (int) $this->request->getPost('barang_id');
        $jenis    = $this->request->getPost('jenis');
        $jumlah   = (int) $this->request->getPost('jumlah');

        // Balikkan efek transaksi lama ke stok
        $this->rollbackStok($lama['barang_id'], $lama['jenis'], $lama['jumlah']);

        // Validasi stok cukup untuk transaksi baru
        if ($jenis === 'keluar') {
            $barang = $this->barangModel->find($barangId);
            if ($barang['stok'] < $jumlah) {
                // Kembalikan stok lama karena gagal
                $this->updateStok($lama['barang_id'], $lama['jenis'], $lama['jumlah']);
                return redirect()->back()->withInput()
                    ->with('error', "Stok tidak cukup! Stok tersedia: {$barang['stok']} {$barang['satuan']}");
            }
        }

        // Update transaksi
        $this->transaksiModel->update($id, [
            'barang_id'  => $barangId,
            'jenis'      => $jenis,
            'jumlah'     => $jumlah,
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal'    => $this->request->getPost('tanggal'),
        ]);

        // Terapkan efek stok baru
        $this->updateStok($barangId, $jenis, $jumlah);

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil diupdate!');
    }

    // DELETE
    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $transaksi = $this->transaksiModel->find($id);

        // Balikkan efek stok sebelum hapus
        $this->rollbackStok($transaksi['barang_id'], $transaksi['jenis'], $transaksi['jumlah']);

        $this->transaksiModel->delete($id);
        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil dihapus!');
    }

    // ─── Helper Methods ───────────────────────────────────────

    /**
     * Terapkan perubahan stok sesuai jenis transaksi
     */
    private function updateStok(int $barangId, string $jenis, int $jumlah): void
    {
        $barang = $this->barangModel->find($barangId);
        $stokBaru = $jenis === 'masuk'
            ? $barang['stok'] + $jumlah
            : $barang['stok'] - $jumlah;

        $this->barangModel->update($barangId, ['stok' => max(0, $stokBaru)]);
    }

    /**
     * Balikkan efek transaksi ke stok (kebalikan dari updateStok)
     */
    private function rollbackStok(int $barangId, string $jenis, int $jumlah): void
    {
        // Kebalikan: masuk → kurangi, keluar → tambah
        $kebalikan = $jenis === 'masuk' ? 'keluar' : 'masuk';
        $this->updateStok($barangId, $kebalikan, $jumlah);
    }
}