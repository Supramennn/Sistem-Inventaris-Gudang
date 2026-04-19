<?= $this->include('layout/header') ?>

<div class="card" style="max-width: 580px;">
    <div style="background:#fef9c3; border:1px solid #fde68a; border-radius:8px; padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.85rem; color:#854d0e;">
        ⚠️ Mengubah transaksi akan <strong>otomatis menyesuaikan stok barang</strong>.
    </div>

    <form action="/transaksi/update/<?= $transaksi['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode Transaksi</label>
            <input type="text" value="<?= esc($transaksi['kode_transaksi']) ?>"
                   readonly style="background:#f8fafc; color:#64748b; cursor:not-allowed;">
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang as $b): ?>
                    <option value="<?= $b['id'] ?>"
                        <?= (old('barang_id', $transaksi['barang_id']) == $b['id']) ? 'selected' : '' ?>>
                        <?= esc($b['kode_barang']) ?> — <?= esc($b['nama_barang']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Transaksi</label>
            <select name="jenis" required>
                <option value="masuk"  <?= (old('jenis', $transaksi['jenis']) == 'masuk')  ? 'selected' : '' ?>>⬆ Barang Masuk</option>
                <option value="keluar" <?= (old('jenis', $transaksi['jenis']) == 'keluar') ? 'selected' : '' ?>>⬇ Barang Keluar</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1"
                   value="<?= old('jumlah', $transaksi['jumlah']) ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal"
                   value="<?= old('tanggal', date('Y-m-d', strtotime($transaksi['tanggal']))) ?>" required>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3"><?= old('keterangan', $transaksi['keterangan']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="/transaksi" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('layout/footer') ?>