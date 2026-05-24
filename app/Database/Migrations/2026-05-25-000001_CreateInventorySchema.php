<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventorySchema extends Migration
{
    public function up(): void
    {
        $this->createAdminTable();
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
        $this->forge->dropTable('admin', true);
    }

    private function createAdminTable(): void
    {
        if ($this->db->tableExists('admin')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_admin' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('admin', true);
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
                'unsigned'       => true,
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
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
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
        $this->forge->addUniqueKey('kode_barang');
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
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_transaksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
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
                'type' => 'DATE',
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
        $this->forge->createTable('transaksi', true);
    }

    private function createTransaksiDetailTable(): void
    {
        if ($this->db->tableExists('transaksi_detail')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaksi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'barang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
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

        $rows = $this->db->table('transaksi')
            ->select('id, barang_id, jumlah')
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
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
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
