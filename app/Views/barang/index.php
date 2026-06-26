<?= $this->include('layout/header') ?>

<?php
    $role = (string) session()->get('role');
    $canManageBarang = in_array($role, ['admin', 'operator'], true);
    $canManageMaster = $role === 'admin';
?>

<div class="page-actions">
    <p class="muted">Total: <strong><?= count($barang) ?></strong> barang</p>
    <?php if ($canManageBarang || $canManageMaster): ?>
        <div class="actions">
            <?php if ($canManageMaster): ?>
                <a href="<?= site_url('master-data') ?>" class="btn btn-secondary">Master Data</a>
            <?php endif; ?>
            <?php if ($canManageBarang): ?>
                <a href="<?= site_url('barang/create') ?>" class="btn btn-primary">Tambah Barang</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card table-responsive">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Gudang</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Harga</th>
                <?php if ($canManageBarang): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($barang === []): ?>
                <tr>
                    <td colspan="<?= $canManageBarang ? 9 : 8 ?>" class="empty-state">Belum ada data barang.</td>
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
                    <td><?= esc($item['nama_kategori'] ?? '-') ?></td>
                    <td><?= esc($item['nama_gudang'] ?? '-') ?></td>
                    <td><?= esc($item['satuan']) ?></td>
                    <td><span class="badge <?= $class ?>"><?= number_format($stok) ?></span></td>
                    <td>Rp <?= number_format((float) ($item['harga'] ?? 0), 0, ',', '.') ?></td>
                    <?php if ($canManageBarang): ?>
                        <td>
                            <div class="actions">
                                <a href="<?= site_url('barang/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?= site_url('barang/delete/' . $item['id']) ?>" method="post" class="inline-form" onsubmit="return confirm('Hapus barang ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('layout/footer') ?>
