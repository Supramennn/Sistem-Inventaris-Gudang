<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    protected BarangModel    $barangModel;
    protected TransaksiModel $transaksiModel;

    public function __construct()
    {
        $this->barangModel    = new BarangModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();

        $totalBarang      = $this->barangModel->countAll();
        $totalStok        = $db->table('barang')->selectSum('stok')->get()->getRow()->stok ?? 0;
        $totalMasuk       = $db->table('transaksi')->where('jenis', 'masuk')->countAllResults();
        $totalKeluar      = $db->table('transaksi')->where('jenis', 'keluar')->countAllResults();

        $barangKritis     = $this->barangModel
                               ->where('stok <=', 10)
                               ->orderBy('stok', 'ASC')
                               ->findAll();

        $transaksiTerbaru = $db->table('transaksi t')
            ->select('t.*, b.nama_barang, b.satuan')
            ->join('barang b', 'b.id = t.barang_id')
            ->orderBy('t.id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        $data = [
            'title'            => 'Dashboard',
            'totalBarang'      => $totalBarang,
            'totalStok'        => $totalStok,
            'totalMasuk'       => $totalMasuk,
            'totalKeluar'      => $totalKeluar,
            'barangKritis'     => $barangKritis,
            'transaksiTerbaru' => $transaksiTerbaru,
        ];

        return view('dashboard/index', $data);
    }
}