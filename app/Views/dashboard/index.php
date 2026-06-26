<?= $this->include('layout/header') ?>

<div class="stats-grid">
    <div class="card stat-card">
        <div class="stat-label">Total Jenis Barang</div>
        <div class="stat-value"><?= number_format((int) $totalBarang) ?></div>
    </div>

    <div class="card stat-card">
        <div class="stat-label">Total Stok Gudang</div>
        <div class="stat-value"><?= number_format((int) $totalStok) ?></div>
    </div>

    <div class="card stat-card">
        <div class="stat-label">Transaksi Masuk</div>
        <div class="stat-value"><?= number_format((int) $totalMasuk) ?></div>
    </div>

    <div class="card stat-card">
        <div class="stat-label">Transaksi Keluar</div>
        <div class="stat-value"><?= number_format((int) $totalKeluar) ?></div>
    </div>
</div>

<div class="dashboard-grid">
    <section class="card">
        <div class="section-title">
            <h2>Stok Kritis</h2>
            <span class="badge badge-red">Di bawah minimum</span>
        </div>

        <?php if ($barangKritis === []): ?>
            <div class="empty-state">Semua stok aman.</div>
        <?php else: ?>
            <div class="critical-list">
                <?php foreach ($barangKritis as $barang): ?>
                    <?php
                        $stok = (int) $barang['stok'];
                        $minimum = max(1, (int) ($barang['stok_minimum'] ?? 10));
                        $pct  = min(100, max(0, ($stok / $minimum) * 100));
                    ?>
                    <div class="critical-item">
                        <div class="critical-item-row">
                            <strong><?= esc($barang['nama_barang']) ?></strong>
                            <span><?= number_format($stok) ?> <?= esc($barang['satuan']) ?></span>
                        </div>
                        <div class="progress"><span style="width: <?= $pct ?>%;"></span></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="card table-responsive">
        <div class="section-title">
            <h2>Transaksi Terbaru</h2>
            <a href="<?= site_url('transaksi') ?>" class="muted">Lihat semua</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jenis</th>
                    <th>Barang</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($transaksiTerbaru === []): ?>
                    <tr>
                        <td colspan="4" class="empty-state">Belum ada transaksi.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($transaksiTerbaru as $transaksi): ?>
                    <?php $details = $detailTerbaru[(int) $transaksi['id']] ?? []; ?>
                    <tr>
                        <td><code><?= esc($transaksi['kode_transaksi']) ?></code></td>
                        <td>
                            <span class="badge <?= $transaksi['jenis'] === 'masuk' ? 'badge-green' : 'badge-red' ?>">
                                <?= $transaksi['jenis'] === 'masuk' ? 'Masuk' : 'Keluar' ?>
                            </span>
                        </td>
                        <td>
                            <ul class="item-list">
                                <?php foreach ($details as $detail): ?>
                                    <li>
                                        <span><?= esc($detail['nama_barang']) ?></span>
                                        <strong><?= number_format((int) $detail['jumlah']) ?> <?= esc($detail['satuan']) ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?= esc(date('d M Y', strtotime($transaksi['tanggal']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<?= $this->include('layout/footer') ?>
