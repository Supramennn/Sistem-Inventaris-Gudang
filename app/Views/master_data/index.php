<?= $this->include('layout/header') ?>

<div class="tabs">
    <?php foreach ($tabs as $tabType => $tabLabel): ?>
        <a href="<?= site_url('master-data/' . $tabType) ?>" class="<?= $type === $tabType ? 'active' : '' ?>">
            <?= esc($tabLabel) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php
    $isEdit = ! empty($editData);
    $action = $isEdit
        ? site_url('master-data/' . $type . '/update/' . $editData['id'])
        : site_url('master-data/' . $type . '/store');
?>

<div class="master-layout">
    <div class="card">
        <div class="section-title">
            <h2><?= $isEdit ? 'Edit ' . esc($label) : 'Tambah ' . esc($label) ?></h2>
            <?php if ($isEdit): ?>
                <a href="<?= site_url('master-data/' . $type) ?>" class="btn btn-secondary btn-sm">Batal Edit</a>
            <?php endif; ?>
        </div>

        <form action="<?= esc($action) ?>" method="post">
            <?= csrf_field() ?>

            <?php if ($type === 'kategori'): ?>
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input id="nama_kategori" type="text" name="nama_kategori" value="<?= esc(old('nama_kategori', $editData['nama_kategori'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi"><?= esc(old('deskripsi', $editData['deskripsi'] ?? '')) ?></textarea>
                </div>
            <?php endif; ?>

            <?php if ($type === 'satuan'): ?>
                <div class="form-group">
                    <label for="nama_satuan">Nama Satuan</label>
                    <input id="nama_satuan" type="text" name="nama_satuan" value="<?= esc(old('nama_satuan', $editData['nama_satuan'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="singkatan">Singkatan</label>
                    <input id="singkatan" type="text" name="singkatan" value="<?= esc(old('singkatan', $editData['singkatan'] ?? '')) ?>" placeholder="pcs">
                </div>
            <?php endif; ?>

            <?php if ($type === 'supplier'): ?>
                <div class="form-group">
                    <label for="kode_supplier">Kode Supplier</label>
                    <input id="kode_supplier" type="text" value="<?= esc($editData['kode_supplier'] ?? $autoKode) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="nama_supplier">Nama Supplier</label>
                    <input id="nama_supplier" type="text" name="nama_supplier" value="<?= esc(old('nama_supplier', $editData['nama_supplier'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input id="telepon" type="text" name="telepon" value="<?= esc(old('telepon', $editData['telepon'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="<?= esc(old('email', $editData['email'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat"><?= esc(old('alamat', $editData['alamat'] ?? '')) ?></textarea>
                </div>
            <?php endif; ?>

            <?php if ($type === 'gudang'): ?>
                <div class="form-group">
                    <label for="kode_gudang">Kode Gudang</label>
                    <input id="kode_gudang" type="text" value="<?= esc($editData['kode_gudang'] ?? $autoKode) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="nama_gudang">Nama Gudang</label>
                    <input id="nama_gudang" type="text" name="nama_gudang" value="<?= esc(old('nama_gudang', $editData['nama_gudang'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <textarea id="lokasi" name="lokasi"><?= esc(old('lokasi', $editData['lokasi'] ?? '')) ?></textarea>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Simpan' ?></button>
            </div>
        </form>
    </div>

    <div class="card table-responsive">
        <div class="section-title">
            <h2>Daftar <?= esc($label) ?></h2>
            <span class="muted"><?= count($items) ?> data</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <?php if ($type === 'supplier'): ?>
                        <th>Kode</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Email</th>
                    <?php elseif ($type === 'gudang'): ?>
                        <th>Kode</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                    <?php elseif ($type === 'satuan'): ?>
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                    <?php else: ?>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                    <?php endif; ?>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr>
                        <td colspan="6" class="empty-state">Belum ada data.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>

                        <?php if ($type === 'supplier'): ?>
                            <td><code><?= esc($item['kode_supplier']) ?></code></td>
                            <td><?= esc($item['nama_supplier']) ?></td>
                            <td><?= esc($item['telepon'] ?: '-') ?></td>
                            <td><?= esc($item['email'] ?: '-') ?></td>
                        <?php elseif ($type === 'gudang'): ?>
                            <td><code><?= esc($item['kode_gudang']) ?></code></td>
                            <td><?= esc($item['nama_gudang']) ?></td>
                            <td><?= esc($item['lokasi'] ?: '-') ?></td>
                        <?php elseif ($type === 'satuan'): ?>
                            <td><?= esc($item['nama_satuan']) ?></td>
                            <td><?= esc($item['singkatan'] ?: '-') ?></td>
                        <?php else: ?>
                            <td><?= esc($item['nama_kategori']) ?></td>
                            <td><?= esc($item['deskripsi'] ?: '-') ?></td>
                        <?php endif; ?>

                        <td>
                            <div class="actions">
                                <a href="<?= site_url('master-data/' . $type . '/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?= site_url('master-data/' . $type . '/delete/' . $item['id']) ?>" method="post" class="inline-form" onsubmit="return confirm('Hapus data ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('layout/footer') ?>
