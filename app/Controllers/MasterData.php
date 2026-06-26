<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\GudangModel;
use App\Models\KategoriModel;
use App\Models\SatuanModel;
use App\Models\SupplierModel;
use CodeIgniter\Model;

class MasterData extends BaseController
{
    protected BarangModel $barangModel;
    protected KategoriModel $kategoriModel;
    protected SatuanModel $satuanModel;
    protected SupplierModel $supplierModel;
    protected GudangModel $gudangModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->kategoriModel = new KategoriModel();
        $this->satuanModel   = new SatuanModel();
        $this->supplierModel = new SupplierModel();
        $this->gudangModel   = new GudangModel();
    }

    public function index(string $type = 'kategori'): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isValidType($type)) {
            return redirect()->to('/master-data/kategori')->with('error', 'Jenis master data tidak valid.');
        }

        return view('master_data/index', $this->viewData($type));
    }

    public function edit(string $type, int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isValidType($type)) {
            return redirect()->to('/master-data/kategori')->with('error', 'Jenis master data tidak valid.');
        }

        $model = $this->modelFor($type);
        $data  = $model->find($id);

        if (! $data) {
            return redirect()->to('/master-data/' . $type)->with('error', 'Data tidak ditemukan.');
        }

        return view('master_data/index', array_merge($this->viewData($type), [
            'editData' => $data,
        ]));
    }

    public function store(string $type): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isValidType($type)) {
            return redirect()->to('/master-data/kategori')->with('error', 'Jenis master data tidak valid.');
        }

        if (! $this->validate($this->rules($type))) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->modelFor($type)->insert($this->payload($type));

        return redirect()->to('/master-data/' . $type)->with('success', $this->labelFor($type) . ' berhasil ditambahkan.');
    }

    public function update(string $type, int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isValidType($type)) {
            return redirect()->to('/master-data/kategori')->with('error', 'Jenis master data tidak valid.');
        }

        if (! $this->modelFor($type)->find($id)) {
            return redirect()->to('/master-data/' . $type)->with('error', 'Data tidak ditemukan.');
        }

        if (! $this->validate($this->rules($type, $id))) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->modelFor($type)->update($id, $this->payload($type, $id));

        return redirect()->to('/master-data/' . $type)->with('success', $this->labelFor($type) . ' berhasil diperbarui.');
    }

    public function delete(string $type, int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isValidType($type)) {
            return redirect()->to('/master-data/kategori')->with('error', 'Jenis master data tidak valid.');
        }

        if ($this->isUsedByBarang($type, $id)) {
            return redirect()->to('/master-data/' . $type)
                ->with('error', $this->labelFor($type) . ' tidak bisa dihapus karena sudah dipakai oleh data barang.');
        }

        $this->modelFor($type)->delete($id);

        return redirect()->to('/master-data/' . $type)->with('success', $this->labelFor($type) . ' berhasil dihapus.');
    }

    private function viewData(string $type): array
    {
        return [
            'title'      => 'Master Data',
            'type'       => $type,
            'label'      => $this->labelFor($type),
            'items'      => $this->modelFor($type)->orderBy('id', 'DESC')->findAll(),
            'tabs'       => $this->tabs(),
            'autoKode'   => $this->autoKode($type),
            'editData'   => null,
        ];
    }

    private function isValidType(string $type): bool
    {
        return array_key_exists($type, $this->tabs());
    }

    private function tabs(): array
    {
        return [
            'kategori' => 'Kategori',
            'satuan'   => 'Satuan',
            'supplier' => 'Supplier',
            'gudang'   => 'Gudang',
        ];
    }

    private function modelFor(string $type): Model
    {
        return match ($type) {
            'kategori' => $this->kategoriModel,
            'satuan'   => $this->satuanModel,
            'supplier' => $this->supplierModel,
            'gudang'   => $this->gudangModel,
        };
    }

    private function labelFor(string $type): string
    {
        return $this->tabs()[$type];
    }

    private function rules(string $type, ?int $id = null): array
    {
        return match ($type) {
            'kategori' => [
                'nama_kategori' => 'required|max_length[100]',
                'deskripsi'     => 'permit_empty',
            ],
            'satuan' => [
                'nama_satuan' => 'required|max_length[50]',
                'singkatan'   => 'permit_empty|max_length[10]',
            ],
            'supplier' => [
                'nama_supplier' => 'required|max_length[100]',
                'alamat'        => 'permit_empty',
                'telepon'       => 'permit_empty|max_length[20]',
                'email'         => 'permit_empty|valid_email|max_length[100]',
            ],
            'gudang' => [
                'nama_gudang' => 'required|max_length[100]',
                'lokasi'      => 'permit_empty',
            ],
        };
    }

    private function payload(string $type, ?int $id = null): array
    {
        return match ($type) {
            'kategori' => [
                'nama_kategori' => $this->request->getPost('nama_kategori'),
                'deskripsi'     => $this->request->getPost('deskripsi'),
            ],
            'satuan' => [
                'nama_satuan' => $this->request->getPost('nama_satuan'),
                'singkatan'   => $this->request->getPost('singkatan'),
            ],
            'supplier' => [
                'kode_supplier' => $id ? (string) $this->modelFor($type)->find($id)['kode_supplier'] : $this->supplierModel->generateKode(),
                'nama_supplier' => $this->request->getPost('nama_supplier'),
                'alamat'        => $this->request->getPost('alamat'),
                'telepon'       => $this->request->getPost('telepon'),
                'email'         => $this->request->getPost('email'),
            ],
            'gudang' => [
                'kode_gudang' => $id ? (string) $this->modelFor($type)->find($id)['kode_gudang'] : $this->gudangModel->generateKode(),
                'nama_gudang' => $this->request->getPost('nama_gudang'),
                'lokasi'      => $this->request->getPost('lokasi'),
            ],
        };
    }

    private function autoKode(string $type): ?string
    {
        return match ($type) {
            'supplier' => $this->supplierModel->generateKode(),
            'gudang'   => $this->gudangModel->generateKode(),
            default    => null,
        };
    }

    private function isUsedByBarang(string $type, int $id): bool
    {
        $field = match ($type) {
            'kategori' => 'kategori_id',
            'satuan'   => 'satuan_id',
            'supplier' => 'supplier_id',
            'gudang'   => 'gudang_id',
        };

        return $this->barangModel->where($field, $id)->countAllResults() > 0;
    }
}
