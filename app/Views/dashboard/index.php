<?= $this->include('layout/header') ?>

<!-- ═══ KARTU STATISTIK ═══ -->
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">

    <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.08); border-left:4px solid #3b82f6;">
        <div style="font-size:0.78rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Total Jenis Barang</div>
        <div style="font-size:2rem; font-weight:800; color:#0f172a; margin-top:0.25rem;"><?= $totalBarang ?></div>
        <div style="font-size:0.8rem; color:#3b82f6; margin-top:0.2rem;">📦 jenis barang terdaftar</div>
    </div>

    <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.08); border-left:4px solid #10b981;">
        <div style="font-size:0.78rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Total Stok Gudang</div>
        <div style="font-size:2rem; font-weight:800; color:#0f172a; margin-top:0.25rem;"><?= number_format($totalStok) ?></div>
        <div style="font-size:0.8rem; color:#10b981; margin-top:0.2rem;">🏭 unit di semua barang</div>
    </div>

    <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.08); border-left:4px solid #f59e0b;">
        <div style="font-size:0.78rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Transaksi Masuk</div>
        <div style="font-size:2rem; font-weight:800; color:#0f172a; margin-top:0.25rem;"><?= $totalMasuk ?></div>
        <div style="font-size:0.8rem; color:#f59e0b; margin-top:0.2rem;">⬆ total transaksi masuk</div>
    </div>

    <div style="background:white; border-radius:12px; padding:1.25rem 1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.08); border-left:4px solid #ef4444;">
        <div style="font-size:0.78rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Transaksi Keluar</div>
        <div style="font-size:2rem; font-weight:800; color:#0f172a; margin-top:0.25rem;"><?= $totalKeluar ?></div>
        <div style="font-size:0.8rem; color:#ef4444; margin-top:0.2rem;">⬇ total transaksi keluar</div>
    </div>

</div>

<!-- ═══ BARIS BAWAH: Stok Kritis + Transaksi Terbaru ═══ -->
<div style="display:grid; grid-template-columns:1fr 2fr; gap:1rem;">

    <!-- Stok Kritis -->
    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a;">⚠️ Stok Kritis</h2>
            <span style="font-size:0.75rem; background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:9999px;">
                ≤ 10 unit
            </span>
        </div>

        <?php if (empty($barangKritis)): ?>
            <div style="text-align:center; padding:2rem 0; color:#94a3b8;">
                <div style="font-size:2rem;">✅</div>
                <div style="font-size:0.875rem; margin-top:0.5rem;">Semua stok aman</div>
            </div>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                <?php foreach ($barangKritis as $b): ?>
                    <?php
                        $pct   = min(100, ($b['stok'] / 10) * 100);
                        $color = $b['stok'] == 0 ? '#ef4444' : ($b['stok'] <= 5 ? '#f59e0b' : '#3b82f6');
                    ?>
                    <div style="background:#f8fafc; border-radius:8px; padding:0.65rem 0.9rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem;">
                            <span style="font-size:0.85rem; font-weight:600; color:#0f172a;">
                                <?= esc($b['nama_barang']) ?>
                            </span>
                            <span style="font-size:0.8rem; font-weight:700; color:<?= $color ?>;">
                                <?= $b['stok'] ?> <?= esc($b['satuan']) ?>
                            </span>
                        </div>
                        <!-- Progress bar stok -->
                        <div style="background:#e2e8f0; border-radius:9999px; height:5px;">
                            <div style="background:<?= $color ?>; width:<?= $pct ?>%; height:5px; border-radius:9999px; transition:width 0.3s;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="/barang" style="display:block; text-align:center; margin-top:1rem; font-size:0.8rem; color:#3b82f6; text-decoration:none;">
                Lihat semua barang →
            </a>
        <?php endif; ?>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; color:#0f172a;">🕐 Transaksi Terbaru</h2>
            <a href="/transaksi" style="font-size:0.8rem; color:#3b82f6; text-decoration:none;">Lihat semua →</a>
        </div>

        <?php if (empty($transaksiTerbaru)): ?>
            <div style="text-align:center; padding:2rem 0; color:#94a3b8; font-size:0.875rem;">
                Belum ada transaksi.
            </div>
        <?php else: ?>
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:0.5rem 0.75rem; color:#475569; font-weight:600; border-bottom:2px solid #f1f5f9; background:#f8fafc;">Barang</th>
                        <th style="text-align:left; padding:0.5rem 0.75rem; color:#475569; font-weight:600; border-bottom:2px solid #f1f5f9; background:#f8fafc;">Jenis</th>
                        <th style="text-align:left; padding:0.5rem 0.75rem; color:#475569; font-weight:600; border-bottom:2px solid #f1f5f9; background:#f8fafc;">Jumlah</th>
                        <th style="text-align:left; padding:0.5rem 0.75rem; color:#475569; font-weight:600; border-bottom:2px solid #f1f5f9; background:#f8fafc;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksiTerbaru as $t): ?>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:0.6rem 0.75rem; color:#334155; font-weight:500;">
                            <?= esc($t['nama_barang']) ?>
                        </td>
                        <td style="padding:0.6rem 0.75rem;">
                            <?php if ($t['jenis'] === 'masuk'): ?>
                                <span class="badge badge-green">⬆ Masuk</span>
                            <?php else: ?>
                                <span class="badge badge-red">⬇ Keluar</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:0.6rem 0.75rem; color:#475569; font-weight:600;">
                            <?= $t['jumlah'] ?> <span style="font-weight:400; color:#94a3b8;"><?= esc($t['satuan']) ?></span>
                        </td>
                        <td style="padding:0.6rem 0.75rem; color:#64748b; font-size:0.8rem;">
                            <?= date('d M Y', strtotime($t['tanggal'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

<?= $this->include('layout/footer') ?>