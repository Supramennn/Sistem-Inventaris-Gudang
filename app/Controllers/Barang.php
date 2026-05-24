<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiDetailModel;

class Barang extends BaseController
{
    protected BarangModel $barangModel;
    protected TransaksiDetailModel $detailModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->detailModel = new TransaksiDetailModel();
    }

    public function index(): string
    {
        return view('barang/index', [
            'title'  => 'Data Barang',
            'barang' => $this->barangModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('barang/create', ['title' => 'Tambah Barang']);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->insert([
            'kode_barang' => trim((string) $this->request->getPost('kode_barang')),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'stok'        => (int) ($this->request->getPost('stok') ?? 0),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $barang = $this->barangModel->find($id);

        if (! $barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }

        return view('barang/edit', [
            'title'  => 'Edit Barang',
            'barang' => $barang,
        ]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->barangModel->find($id)) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }

        if (! $this->validate($this->rules($id))) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->update($id, [
            'kode_barang' => trim((string) $this->request->getPost('kode_barang')),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'stok'        => (int) $this->request->getPost('stok'),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($this->detailModel->where('barang_id', $id)->countAllResults() > 0) {
            return redirect()->to('/barang')
                ->with('error', 'Barang tidak bisa dihapus karena sudah dipakai pada transaksi.');
        }

        $this->barangModel->delete($id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus.');
    }

    private function rules(?int $id = null): array
    {
        $uniqueRule = $id === null
            ? 'is_unique[barang.kode_barang]'
            : 'is_unique[barang.kode_barang,id,' . $id . ']';

        return [
            'kode_barang' => 'required|min_length[3]|max_length[20]|' . $uniqueRule,
            'nama_barang' => 'required|min_length[2]|max_length[100]',
            'satuan'      => 'required|max_length[20]',
            'stok'        => 'permit_empty|is_natural',
        ];
    }
}
