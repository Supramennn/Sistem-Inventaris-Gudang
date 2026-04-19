<?= $this->include('layout/header') ?>

<div class="card" style="max-width: 560px;">
    <form action="/barang/store" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" placeholder="Contoh: BRG-001"
                   value="<?= old('kode_barang') ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Contoh: Kardus Besar"
                   value="<?= old('nama_barang') ?>" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <select name="satuan" required>
                <option value="">-- Pilih Satuan --</option>
                <?php foreach (['pcs','kg','liter','dus','karton','roll','meter'] as $s): ?>
                    <option value="<?= $s ?>" <?= old('satuan') == $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Stok Awal</label>
            <input type="number" name="stok" placeholder="0" min="0"
                   value="<?= old('stok', 0) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="/barang" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('layout/footer') ?>