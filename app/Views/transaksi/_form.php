<?php
$isEdit     = isset($transaksi);
$formAction = $action ?? site_url('transaksi/store');
$kodeValue  = old('kode_transaksi', $transaksi['kode_transaksi'] ?? $kode ?? '');
$jenisValue = old('jenis', $transaksi['jenis'] ?? 'masuk');
$tanggal    = $transaksi['tanggal'] ?? date('Y-m-d');
$dateValue  = old('tanggal', date('Y-m-d', strtotime($tanggal)));
$notesValue = old('keterangan', $transaksi['keterangan'] ?? '');

$oldBarangIds = old('barang_id');
$oldJumlahs   = old('jumlah');
$rows          = [];

if (is_array($oldBarangIds)) {
    foreach ($oldBarangIds as $index => $barangId) {
        $rows[] = [
            'barang_id' => $barangId,
            'jumlah'    => is_array($oldJumlahs) ? ($oldJumlahs[$index] ?? '') : '',
        ];
    }
} elseif (! empty($detail)) {
    foreach ($detail as $item) {
        $rows[] = [
            'barang_id' => $item['barang_id'],
            'jumlah'    => $item['jumlah'],
        ];
    }
}

if ($rows === []) {
    $rows[] = ['barang_id' => '', 'jumlah' => ''];
}
?>

<div class="card form-wide">
    <form action="<?= esc($formAction) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="kode_transaksi">Kode Transaksi</label>
                <input id="kode_transaksi" type="text" name="kode_transaksi" value="<?= esc($kodeValue) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="<?= esc($dateValue) ?>" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Transaksi</label>
                <select id="jenis" name="jenis" required>
                    <option value="masuk" <?= $jenisValue === 'masuk' ? 'selected' : '' ?>>Barang Masuk</option>
                    <option value="keluar" <?= $jenisValue === 'keluar' ? 'selected' : '' ?>>Barang Keluar</option>
                </select>
            </div>
        </div>

        <div class="line-items">
            <div class="line-items-header">
                <h2>Daftar Barang</h2>
                <button type="button" class="btn btn-secondary btn-sm" data-add-row>Tambah Baris</button>
            </div>

            <div class="table-responsive">
                <table class="line-table" data-detail-table>
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td>
                                    <select name="barang_id[]" data-barang required>
                                        <option value="">Pilih barang</option>
                                        <?php foreach ($barang as $item): ?>
                                            <?php $selected = (string) $row['barang_id'] === (string) $item['id']; ?>
                                            <option
                                                value="<?= esc($item['id']) ?>"
                                                data-stok="<?= esc($item['stok']) ?>"
                                                data-satuan="<?= esc($item['satuan']) ?>"
                                                <?= $selected ? 'selected' : '' ?>
                                            >
                                                <?= esc($item['kode_barang']) ?> - <?= esc($item['nama_barang']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="stock-note" data-stock-note></span>
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" min="1" value="<?= esc($row['jumlah']) ?>" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" data-remove-row>Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3"><?= esc($notesValue) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Simpan' ?></button>
            <a href="<?= site_url('transaksi') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
(function () {
    const table = document.querySelector('[data-detail-table]');
    const addButton = document.querySelector('[data-add-row]');

    function updateStockNote(row) {
        const select = row.querySelector('[data-barang]');
        const note = row.querySelector('[data-stock-note]');
        const option = select.options[select.selectedIndex];

        note.textContent = option && option.value
            ? 'Stok: ' + option.dataset.stok + ' ' + option.dataset.satuan
            : '';
    }

    function resetRow(row) {
        row.querySelector('[data-barang]').value = '';
        row.querySelector('input[name="jumlah[]"]').value = '';
        updateStockNote(row);
    }

    table.addEventListener('change', function (event) {
        if (event.target.matches('[data-barang]')) {
            updateStockNote(event.target.closest('tr'));
        }
    });

    table.addEventListener('click', function (event) {
        if (! event.target.matches('[data-remove-row]')) {
            return;
        }

        const rows = table.querySelectorAll('tbody tr');
        const currentRow = event.target.closest('tr');

        if (rows.length === 1) {
            resetRow(currentRow);
            return;
        }

        currentRow.remove();
    });

    addButton.addEventListener('click', function () {
        const tbody = table.querySelector('tbody');
        const row = tbody.querySelector('tr').cloneNode(true);
        resetRow(row);
        tbody.appendChild(row);
    });

    table.querySelectorAll('tbody tr').forEach(updateStockNote);
})();
</script>
