<?= $this->include('layout/header') ?>

<div class="card" style="max-width: 580px;">
    <form action="/transaksi/store" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode Transaksi</label>
            <input type="text" name="kode_transaksi"
                   value="<?= esc($kode) ?>" readonly
                   style="background:#f8fafc; color:#64748b; cursor:not-allowed;">
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" required id="selectBarang" onchange="updateInfoBarang(this)">
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang as $b): ?>
                    <option value="<?= $b['id'] ?>"
                            data-stok="<?= $b['stok'] ?>"
                            data-satuan="<?= esc($b['satuan']) ?>"
                            <?= old('barang_id') == $b['id'] ? 'selected' : '' ?>>
                        <?= esc($b['kode_barang']) ?> — <?= esc($b['nama_barang']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="infoBarang" style="font-size:0.8rem; color:#64748b; margin-top:0.4rem;"></div>
        </div>

        <div class="form-group">
            <label>Jenis Transaksi</label>
            <select name="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="masuk"  <?= old('jenis') == 'masuk'  ? 'selected' : '' ?>>⬆ Barang Masuk</option>
                <option value="keluar" <?= old('jenis') == 'keluar' ? 'selected' : '' ?>>⬇ Barang Keluar</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1"
                   value="<?= old('jumlah') ?>" placeholder="Masukkan jumlah" required>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal"
                   value="<?= old('tanggal', date('Y-m-d')) ?>" required>
        </div>

        <div class="form-group">
            <label>Keterangan <span style="color:#94a3b8;">(opsional)</span></label>
            <textarea name="keterangan" rows="3"
                      placeholder="Contoh: Pembelian dari supplier A"><?= old('keterangan') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="/transaksi" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function updateInfoBarang(select) {
    const opt   = select.options[select.selectedIndex];
    const info  = document.getElementById('infoBarang');
    if (opt.value) {
        info.innerHTML = `📦 Stok saat ini: <strong>${opt.dataset.stok} ${opt.dataset.satuan}</strong>`;
    } else {
        info.innerHTML = '';
    }
}
</script>

<?= $this->include('layout/footer') ?>