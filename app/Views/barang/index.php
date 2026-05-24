<?= $this->include('layout/header') ?>

<div class="page-actions">
    <p class="muted">Total: <strong><?= count($barang) ?></strong> barang</p>
    <a href="<?= site_url('barang/create') ?>" class="btn btn-primary">Tambah Barang</a>
</div>

<div class="card table-responsive">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($barang === []): ?>
                <tr>
                    <td colspan="6" class="empty-state">Belum ada data barang.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($barang as $index => $item): ?>
                <?php
                    $stok  = (int) $item['stok'];
                    $class = $stok > 10 ? 'badge-green' : ($stok > 0 ? 'badge-yellow' : 'badge-red');
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><code><?= esc($item['kode_barang']) ?></code></td>
                    <td><?= esc($item['nama_barang']) ?></td>
                    <td><?= esc($item['satuan']) ?></td>
                    <td><span class="badge <?= $class ?>"><?= number_format($stok) ?></span></td>
                    <td>
                        <div class="actions">
                            <a href="<?= site_url('barang/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?= site_url('barang/delete/' . $item['id']) ?>" method="post" class="inline-form" onsubmit="return confirm('Hapus barang ini?')">
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
