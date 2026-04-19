<?= $this->include('layout/header') ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#64748b; font-size:0.9rem;">Total: <strong><?= count($transaksi) ?></strong> transaksi</p>
    <a href="/transaksi/create" class="btn btn-primary">+ Tambah Transaksi</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transaksi)): ?>
                <tr><td colspan="8" style="text-align:center; color:#94a3b8; padding:2rem;">Belum ada transaksi.</td></tr>
            <?php else: ?>
                <?php foreach ($transaksi as $i => $t): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><code style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-size:0.8rem;"><?= esc($t['kode_transaksi']) ?></code></td>
                    <td>
                        <div style="font-weight:600; color:#0f172a;"><?= esc($t['nama_barang']) ?></div>
                        <div style="font-size:0.75rem; color:#94a3b8;"><?= esc($t['kode_barang']) ?></div>
                    </td>
                    <td>
                        <?php if ($t['jenis'] === 'masuk'): ?>
                            <span class="badge badge-green">⬆ Masuk</span>
                        <?php else: ?>
                            <span class="badge badge-red">⬇ Keluar</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= $t['jumlah'] ?></strong> <?= esc($t['satuan']) ?></td>
                    <td style="font-size:0.85rem; color:#475569;"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                    <td style="font-size:0.85rem; color:#64748b;"><?= esc($t['keterangan'] ?? '-') ?></td>
                    <td style="display:flex; gap:0.4rem;">
                        <a href="/transaksi/edit/<?= $t['id'] ?>" class="btn btn-warning">✏️</a>
                        <a href="/transaksi/delete/<?= $t['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('layout/footer') ?>