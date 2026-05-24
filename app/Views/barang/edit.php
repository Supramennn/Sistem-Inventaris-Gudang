<?= $this->include('layout/header') ?>

<?php $satuanOptions = ['pcs', 'kg', 'liter', 'dus', 'karton', 'roll', 'meter']; ?>

<div class="card form-card">
    <form action="<?= site_url('barang/update/' . $barang['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="kode_barang">Kode Barang</label>
            <input id="kode_barang" type="text" name="kode_barang" value="<?= esc(old('kode_barang', $barang['kode_barang'])) ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input id="nama_barang" type="text" name="nama_barang" value="<?= esc(old('nama_barang', $barang['nama_barang'])) ?>" required>
        </div>

        <div class="form-group">
            <label for="satuan">Satuan</label>
            <select id="satuan" name="satuan" required>
                <?php foreach ($satuanOptions as $satuan): ?>
                    <option value="<?= esc($satuan) ?>" <?= old('satuan', $barang['satuan']) === $satuan ? 'selected' : '' ?>>
                        <?= esc($satuan) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input id="stok" type="number" name="stok" min="0" value="<?= esc(old('stok', $barang['stok'])) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('layout/footer') ?>
