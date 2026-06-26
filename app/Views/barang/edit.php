<?= $this->include('layout/header') ?>

<div class="card form-wide">
    <form action="<?= site_url('barang/update/' . $barang['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input id="kode_barang" type="text" name="kode_barang" value="<?= esc(old('kode_barang', $barang['kode_barang'])) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input id="nama_barang" type="text" name="nama_barang" value="<?= esc(old('nama_barang', $barang['nama_barang'])) ?>" required>
            </div>

            <div class="form-group">
                <label for="harga">Harga</label>
                <input id="harga" type="number" name="harga" min="0" step="0.01" value="<?= esc(old('harga', $barang['harga'])) ?>">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="stok">Stok</label>
                <input id="stok" type="number" name="stok" min="0" value="<?= esc(old('stok', $barang['stok'])) ?>">
            </div>

            <div class="form-group">
                <label for="stok_minimum">Stok Minimum</label>
                <input id="stok_minimum" type="number" name="stok_minimum" min="0" value="<?= esc(old('stok_minimum', $barang['stok_minimum'])) ?>">
            </div>

            <div class="form-group">
                <label for="satuan_id">Satuan</label>
                <select id="satuan_id" name="satuan_id" required>
                    <?php foreach ($satuan as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= old('satuan_id', $barang['satuan_id']) == $item['id'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_satuan']) ?><?= $item['singkatan'] ? ' (' . esc($item['singkatan']) . ')' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="stock-note"><a href="<?= site_url('master-data/satuan') ?>">Kelola satuan</a></span>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="kategori_id">Kategori</label>
                <select id="kategori_id" name="kategori_id" required>
                    <?php foreach ($kategori as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= old('kategori_id', $barang['kategori_id']) == $item['id'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="stock-note"><a href="<?= site_url('master-data/kategori') ?>">Kelola kategori</a></span>
            </div>

            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" required>
                    <?php foreach ($supplier as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= old('supplier_id', $barang['supplier_id']) == $item['id'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_supplier']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="stock-note"><a href="<?= site_url('master-data/supplier') ?>">Kelola supplier</a></span>
            </div>

            <div class="form-group">
                <label for="gudang_id">Gudang</label>
                <select id="gudang_id" name="gudang_id" required>
                    <?php foreach ($gudang as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= old('gudang_id', $barang['gudang_id']) == $item['id'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_gudang']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="stock-note"><a href="<?= site_url('master-data/gudang') ?>">Kelola gudang</a></span>
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"><?= esc(old('deskripsi', $barang['deskripsi'])) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('layout/footer') ?>
