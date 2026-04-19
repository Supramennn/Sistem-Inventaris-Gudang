<?= $this->include('layout/header') ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#64748b; font-size:0.9rem;">Total: <strong><?= count($barang) ?></strong> barang</p>
    <a href="/barang/create" class="btn btn-primary">+ Tambah Barang</a>
</div>

<div class="card">
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
            <?php if (empty($barang)): ?>
                <tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:2rem;">Belum ada data barang.</td></tr>
            <?php else: ?>
                <?php foreach ($barang as $i => $b): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><code style="background:#f1f5f9; padding:2px 6px; border-radius:4px;"><?= esc($b['kode_barang']) ?></code></td>
                    <td><?= esc($b['nama_barang']) ?></td>
                    <td><?= esc($b['satuan']) ?></td>
                    <td>
                        <?php
                            $stok = $b['stok'];
                            $class = $stok > 10 ? 'badge-green' : ($stok > 0 ? 'badge-yellow' : 'badge-red');
                        ?>
                        <span class="badge <?= $class ?>"><?= $stok ?></span>
                    </td>
                    <td style="display:flex; gap:0.4rem;">
                        <a href="/barang/edit/<?= $b['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                        <a href="/barang/delete/<?= $b['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('Hapus barang ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('layout/footer') ?>