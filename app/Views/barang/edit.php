<?= $this->include('layout/header') ?>

<div class="card" style="max-width: 560px;">
    <form action="/barang/update/<?= $barang['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang"
                   value="<?= old('kode_barang', $barang['kode_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang"
                   value="<?= old('nama_barang', $barang['nama_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <select name="satuan" required>
                <?php foreach (['pcs','kg','liter','dus','karton','roll','meter'] as $s): ?>
                    <option value="<?= $s ?>"
                        <?= (old('satuan', $barang['satuan']) == $s) ? 'selected' : '' ?>>
                        <?= $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" min="0"
                   value="<?= old('stok', $barang['stok']) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="/barang" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('layout/footer') ?>