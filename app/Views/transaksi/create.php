<?= $this->include('layout/header') ?>

<?= view('transaksi/_form', [
    'action' => site_url('transaksi/store'),
    'barang' => $barang,
    'kode'   => $kode,
]) ?>

<?= $this->include('layout/footer') ?>
