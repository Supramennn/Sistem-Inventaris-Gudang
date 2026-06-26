<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiDetailModel;
use App\Models\TransaksiModel;
use App\Services\TransaksiService;
use Throwable;

class Transaksi extends BaseController
{
    protected TransaksiModel $transaksiModel;
    protected TransaksiDetailModel $detailModel;
    protected BarangModel $barangModel;
    protected TransaksiService $transaksiService;

    public function __construct()
    {
        $this->transaksiModel   = new TransaksiModel();
        $this->detailModel      = new TransaksiDetailModel();
        $this->barangModel      = new BarangModel();
        $this->transaksiService = new TransaksiService();
    }

    public function index(): string
    {
        $transaksi = $this->transaksiModel->getAllWithTotals();

        return view('transaksi/index', [
            'title'              => 'Data Transaksi',
            'transaksi'          => $transaksi,
            'detailsByTransaksi' => $this->detailModel->getGroupedByTransaksiIds(array_column($transaksi, 'id')),
        ]);
    }

    public function create(): string
    {
        return view('transaksi/create', [
            'title'  => 'Tambah Transaksi',
            'barang' => $this->barangModel->getAllWithRelations(),
            'kode'   => $this->transaksiModel->generateKode(),
        ]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $header = $this->headerPayload();
            $header['kode_transaksi'] = $this->transaksiModel->generateKode();

            $this->transaksiService->create($header, $this->detailPayload());
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $transaksi = $this->transaksiModel->find($id);

        if (! $transaksi) {
            return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('transaksi/edit', [
            'title'     => 'Edit Transaksi',
            'transaksi' => $transaksi,
            'detail'    => $this->transaksiService->getItems($id, $transaksi),
            'barang'    => $this->barangModel->getAllWithRelations(),
        ]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $this->transaksiService->update($id, $this->headerPayload(), $this->detailPayload());
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        try {
            $this->transaksiService->delete($id);
        } catch (Throwable $e) {
            return redirect()->to('/transaksi')->with('error', $e->getMessage());
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'kode_transaksi' => 'required|max_length[30]',
            'jenis'          => 'required|in_list[masuk,keluar]',
            'tanggal'        => 'required|valid_date[Y-m-d]',
            'barang_id'      => 'required',
            'jumlah'         => 'required',
        ];
    }

    private function headerPayload(): array
    {
        return [
            'kode_transaksi' => trim((string) $this->request->getPost('kode_transaksi')),
            'user_id'        => (int) session()->get('user_id'),
            'jenis'          => (string) $this->request->getPost('jenis'),
            'tanggal'        => (string) $this->request->getPost('tanggal'),
            'keterangan'     => $this->request->getPost('keterangan'),
        ];
    }

    private function detailPayload(): array
    {
        $barangIds = (array) $this->request->getPost('barang_id');
        $jumlahs   = (array) $this->request->getPost('jumlah');
        $items     = [];

        foreach ($barangIds as $index => $barangId) {
            $items[] = [
                'barang_id' => $barangId,
                'jumlah'    => $jumlahs[$index] ?? 0,
            ];
        }

        return $items;
    }
}
