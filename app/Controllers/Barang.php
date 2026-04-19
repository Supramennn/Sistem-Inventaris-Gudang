<?php

namespace App\Controllers;

use App\Models\BarangModel;
use CodeIgniter\Controller;

class Barang extends Controller
{
    protected BarangModel $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        helper(['form', 'url']);
    }

    // READ - tampilkan semua barang
    public function index(): string
    {
        $data = [
            'title'  => 'Data Barang',
            'barang' => $this->barangModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('barang/index', $data);
    }

    // CREATE - form tambah
    public function create(): string
    {
        return view('barang/create', ['title' => 'Tambah Barang']);
    }

    // CREATE - proses simpan
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $kode = $this->request->getPost('kode_barang');

        if ($this->barangModel->isKodeTaken($kode)) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode barang sudah digunakan!');
        }

        $this->barangModel->insert([
            'kode_barang' => $kode,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'stok'        => $this->request->getPost('stok') ?? 0,
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan!');
    }

    // UPDATE - form edit
    public function edit(int $id): string
    {
        $data = [
            'title'  => 'Edit Barang',
            'barang' => $this->barangModel->find($id),
        ];
        return view('barang/edit', $data);
    }

    // UPDATE - proses simpan
    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $kode = $this->request->getPost('kode_barang');

        if ($this->barangModel->isKodeTaken($kode, $id)) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode barang sudah digunakan barang lain!');
        }

        $this->barangModel->update($id, [
            'kode_barang' => $kode,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'stok'        => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil diupdate!');
    }

    // DELETE
    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->barangModel->delete($id);
        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus!');
    }
}