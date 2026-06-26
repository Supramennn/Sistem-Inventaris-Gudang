<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\GudangModel;
use App\Models\KategoriModel;
use App\Models\SatuanModel;
use App\Models\SupplierModel;
use App\Models\TransaksiDetailModel;

class Barang extends BaseController
{
    protected BarangModel $barangModel;
    protected TransaksiDetailModel $detailModel;
    protected KategoriModel $kategoriModel;
    protected SatuanModel $satuanModel;
    protected SupplierModel $supplierModel;
    protected GudangModel $gudangModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->detailModel   = new TransaksiDetailModel();
        $this->kategoriModel = new KategoriModel();
        $this->satuanModel   = new SatuanModel();
        $this->supplierModel = new SupplierModel();
        $this->gudangModel   = new GudangModel();
    }

    public function index(): string
    {
        return view('barang/index', [
            'title'  => 'Data Barang',
            'barang' => $this->barangModel->getAllWithRelations(),
        ]);
    }

    public function create(): string
    {
        return view('barang/create', array_merge(
            [
                'title' => 'Tambah Barang',
                'kode'  => $this->barangModel->generateKode(),
            ],
            $this->referenceData()
        ));
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->insert([
            'kode_barang' => $this->barangModel->generateKode(),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'stok'        => (int) ($this->request->getPost('stok') ?? 0),
            'stok_minimum'=> (int) ($this->request->getPost('stok_minimum') ?? 10),
            'harga'       => (float) ($this->request->getPost('harga') ?? 0),
            'kategori_id' => (int) $this->request->getPost('kategori_id'),
            'satuan_id'   => (int) $this->request->getPost('satuan_id'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'gudang_id'   => (int) $this->request->getPost('gudang_id'),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $barang = $this->barangModel->find($id);

        if (! $barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }

        return view('barang/edit', array_merge(
            [
                'title'  => 'Edit Barang',
                'barang' => $barang,
            ],
            $this->referenceData()
        ));
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
            'nama_barang' => $this->request->getPost('nama_barang'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'stok'        => (int) $this->request->getPost('stok'),
            'stok_minimum'=> (int) $this->request->getPost('stok_minimum'),
            'harga'       => (float) $this->request->getPost('harga'),
            'kategori_id' => (int) $this->request->getPost('kategori_id'),
            'satuan_id'   => (int) $this->request->getPost('satuan_id'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'gudang_id'   => (int) $this->request->getPost('gudang_id'),
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
            ? 'permit_empty'
            : 'is_unique[barang.kode_barang,id,' . $id . ']';

        return [
            'kode_barang' => 'permit_empty|min_length[3]|max_length[20]|' . $uniqueRule,
            'nama_barang' => 'required|min_length[2]|max_length[100]',
            'deskripsi'   => 'permit_empty',
            'stok'        => 'permit_empty|is_natural',
            'stok_minimum'=> 'permit_empty|is_natural',
            'harga'       => 'permit_empty|decimal',
            'kategori_id' => 'required|is_natural_no_zero',
            'satuan_id'   => 'required|is_natural_no_zero',
            'supplier_id' => 'required|is_natural_no_zero',
            'gudang_id'   => 'required|is_natural_no_zero',
        ];
    }

    private function referenceData(): array
    {
        return [
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'satuan'   => $this->satuanModel->orderBy('nama_satuan', 'ASC')->findAll(),
            'supplier' => $this->supplierModel->orderBy('nama_supplier', 'ASC')->findAll(),
            'gudang'   => $this->gudangModel->orderBy('nama_gudang', 'ASC')->findAll(),
        ];
    }
}
