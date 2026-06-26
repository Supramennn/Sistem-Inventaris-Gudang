<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiDetailModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    protected BarangModel $barangModel;
    protected TransaksiModel $transaksiModel;
    protected TransaksiDetailModel $detailModel;

    public function __construct()
    {
        $this->barangModel    = new BarangModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel    = new TransaksiDetailModel();
    }

    public function index(): string
    {
        $db                = \Config\Database::connect();
        $transaksiTerbaru = $this->transaksiModel->getLatestWithTotals(8);

        return view('dashboard/index', [
            'title'            => 'Dashboard',
            'totalBarang'      => $this->barangModel->countAll(),
            'totalStok'        => $db->table('barang')->selectSum('stok')->get()->getRow()->stok ?? 0,
            'totalMasuk'       => $this->transaksiModel->countByJenis('masuk'),
            'totalKeluar'      => $this->transaksiModel->countByJenis('keluar'),
            'barangKritis'     => $this->barangModel->getCriticalStock(),
            'transaksiTerbaru' => $transaksiTerbaru,
            'detailTerbaru'    => $this->detailModel->getGroupedByTransaksiIds(array_column($transaksiTerbaru, 'id')),
        ]);
    }
}
