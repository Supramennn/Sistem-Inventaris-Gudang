<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventorySchema extends Migration
{
    public function up(): void
    {
        $this->createUserTable();
        $this->createReferenceTables();
        $this->seedReferenceData();
        $this->createBarangTable();
        $this->createTransaksiTable();
        $this->createTransaksiDetailTable();
        $this->copyLegacyTransaksiDetails();
    }

    public function down(): void
    {
        $this->forge->dropTable('transaksi_detail', true);
        $this->forge->dropTable('transaksi', true);
        $this->forge->dropTable('barang', true);
        $this->forge->dropTable('gudang', true);
        $this->forge->dropTable('supplier', true);
        $this->forge->dropTable('satuan', true);
        $this->forge->dropTable('kategori', true);
        $this->forge->dropTable('user', true);
    }

    private function createUserTable(): void
    {
        if ($this->db->tableExists('user')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'operator',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('user', true);
    }

    private function createReferenceTables(): void
    {
        if (! $this->db->tableExists('kategori')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'nama_kategori' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'deskripsi' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('kategori', true);
        }

        if (! $this->db->tableExists('satuan')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'nama_satuan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'singkatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('satuan', true);
        }

        if (! $this->db->tableExists('supplier')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'kode_supplier' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                ],
                'nama_supplier' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'alamat' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'telepon' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('kode_supplier');
            $this->forge->createTable('supplier', true);
        }

        if (! $this->db->tableExists('gudang')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'kode_gudang' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                ],
                'nama_gudang' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'lokasi' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('kode_gudang');
            $this->forge->createTable('gudang', true);
        }
    }

    private function createBarangTable(): void
    {
        if ($this->db->tableExists('barang')) {
            $this->ensureColumn('barang', 'updated_at', [
                'type' => 'DATETIME',
                'null' => true,
            ]);

            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'stok_minimum' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 10,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'satuan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'gudang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_barang');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('satuan_id', 'satuan', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('gudang_id', 'gudang', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('barang', true);
    }

    private function createTransaksiTable(): void
    {
        if ($this->db->tableExists('transaksi')) {
            $this->ensureColumn('transaksi', 'created_at', [
                'type' => 'DATETIME',
                'null' => true,
            ]);
            $this->ensureColumn('transaksi', 'updated_at', [
                'type' => 'DATETIME',
                'null' => true,
            ]);

            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'kode_transaksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tanggal' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_transaksi');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('transaksi', true);
    }

    private function createTransaksiDetailTable(): void
    {
        if ($this->db->tableExists('transaksi_detail')) {
            $this->ensureColumn('transaksi_detail', 'harga_satuan', [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ]);
            $this->ensureColumn('transaksi_detail', 'total_harga', [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ]);
            $this->ensureColumn('transaksi_detail', 'updated_at', [
                'type' => 'DATETIME',
                'null' => true,
            ]);

            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'transaksi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'barang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('transaksi_id');
        $this->forge->addKey('barang_id');
        $this->forge->addForeignKey('transaksi_id', 'transaksi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('barang_id', 'barang', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('transaksi_detail', true);
    }

    private function copyLegacyTransaksiDetails(): void
    {
        if (
            ! $this->db->tableExists('transaksi')
            || ! $this->db->tableExists('transaksi_detail')
            || ! $this->db->fieldExists('barang_id', 'transaksi')
            || ! $this->db->fieldExists('jumlah', 'transaksi')
        ) {
            return;
        }

        $select = ['id', 'barang_id', 'jumlah'];
        if ($this->db->fieldExists('harga_satuan', 'transaksi')) {
            $select[] = 'harga_satuan';
        }
        if ($this->db->fieldExists('total_harga', 'transaksi')) {
            $select[] = 'total_harga';
        }

        $rows = $this->db->table('transaksi')
            ->select(implode(', ', $select))
            ->where('barang_id IS NOT NULL', null, false)
            ->where('jumlah >', 0)
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $exists = $this->db->table('transaksi_detail')
                ->where('transaksi_id', $row['id'])
                ->where('barang_id', $row['barang_id'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('transaksi_detail')->insert([
                'transaksi_id' => $row['id'],
                'barang_id'    => $row['barang_id'],
                'jumlah'       => $row['jumlah'],
                'harga_satuan' => $row['harga_satuan'] ?? 0,
                'total_harga'  => $row['total_harga'] ?? 0,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function seedReferenceData(): void
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->tableExists('kategori') && $this->db->table('kategori')->countAllResults() === 0) {
            $this->db->table('kategori')->insert([
                'nama_kategori' => 'Umum',
                'deskripsi'     => 'Kategori bawaan',
                'created_at'    => $now,
            ]);
        }

        if ($this->db->tableExists('satuan') && $this->db->table('satuan')->countAllResults() === 0) {
            $this->db->table('satuan')->insert([
                'nama_satuan' => 'Unit',
                'singkatan'   => 'unit',
                'created_at'  => $now,
            ]);
        }

        if ($this->db->tableExists('supplier') && $this->db->table('supplier')->countAllResults() === 0) {
            $this->db->table('supplier')->insert([
                'kode_supplier' => 'SUP-UMUM',
                'nama_supplier' => 'Supplier Umum',
                'created_at'    => $now,
            ]);
        }

        if ($this->db->tableExists('gudang') && $this->db->table('gudang')->countAllResults() === 0) {
            $this->db->table('gudang')->insert([
                'kode_gudang' => 'GDG-UTAMA',
                'nama_gudang' => 'Gudang Utama',
                'created_at'  => $now,
            ]);
        }

        if ($this->db->tableExists('user') && $this->db->table('user')->countAllResults() === 0) {
            $this->db->table('user')->insert([
                'nama'       => 'Admin',
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => $now,
            ]);
        }
    }

    private function ensureColumn(string $table, string $column, array $definition): void
    {
        if ($this->db->fieldExists($column, $table)) {
            return;
        }

        $this->forge->addColumn($table, [$column => $definition]);
    }
}
