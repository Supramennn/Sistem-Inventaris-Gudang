<?= $this->include('layout/header') ?>

<?= view('transaksi/_form', [
    'action'    => site_url('transaksi/update/' . $transaksi['id']),
    'barang'    => $barang,
    'transaksi' => $transaksi,
    'detail'    => $detail,
]) ?>

<?= $this->include('layout/footer') ?>
