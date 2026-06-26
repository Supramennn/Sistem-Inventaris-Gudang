<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedOperatorViewerUsers extends Migration
{
    public function up(): void
    {
        $this->insertUserIfMissing([
            'nama'       => 'Operator',
            'username'   => 'operator',
            'password'   => password_hash('Operator123', PASSWORD_DEFAULT),
            'role'       => 'operator',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insertUserIfMissing([
            'nama'       => 'Viewer',
            'username'   => 'viewer',
            'password'   => password_hash('Viewer123', PASSWORD_DEFAULT),
            'role'       => 'viewer',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down(): void
    {
        $this->db->table('user')->whereIn('username', ['operator', 'viewer'])->delete();
    }

    private function insertUserIfMissing(array $user): void
    {
        $exists = $this->db->table('user')
            ->where('username', $user['username'])
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $this->db->table('user')->insert($user);
    }
}
