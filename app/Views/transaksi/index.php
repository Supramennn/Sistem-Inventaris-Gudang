<?= $this->include('layout/header') ?>

<div class="page-actions">
    <p class="muted">Total: <strong><?= count($transaksi) ?></strong> transaksi</p>
    <a href="<?= site_url('transaksi/create') ?>" class="btn btn-primary">Tambah Transaksi</a>
</div>

<div class="card table-responsive">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Jenis</th>
                <th>Barang</th>
                <th>Total Jumlah</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($transaksi === []): ?>
                <tr>
                    <td colspan="8" class="empty-state">Belum ada transaksi.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($transaksi as $index => $item): ?>
                <?php $details = $detailsByTransaksi[(int) $item['id']] ?? []; ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><code><?= esc($item['kode_transaksi']) ?></code></td>
                    <td>
                        <span class="badge <?= $item['jenis'] === 'masuk' ? 'badge-green' : 'badge-red' ?>">
                            <?= $item['jenis'] === 'masuk' ? 'Masuk' : 'Keluar' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($details === []): ?>
                            <span class="muted">Tidak ada detail</span>
                        <?php else: ?>
                            <ul class="item-list">
                                <?php foreach ($details as $detail): ?>
                                    <li>
                                        <span><?= esc($detail['kode_barang']) ?> - <?= esc($detail['nama_barang']) ?></span>
                                        <strong><?= number_format((int) $detail['jumlah']) ?> <?= esc($detail['satuan']) ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </td>
                    <td><?= number_format((int) $item['total_jumlah']) ?></td>
                    <td><?= esc(date('d M Y', strtotime($item['tanggal']))) ?></td>
                    <td><?= esc($item['keterangan'] ?: '-') ?></td>
                    <td>
                        <div class="actions">
                            <a href="<?= site_url('transaksi/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?= site_url('transaksi/delete/' . $item['id']) ?>" method="post" class="inline-form" onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('layout/footer') ?>
