<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inventaris Gudang') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 240px; background: #0f172a; color: #94a3b8;
            display: flex; flex-direction: column; padding: 1.5rem 0; position: fixed;
            height: 100vh; top: 0; left: 0;
        }
        .sidebar .brand {
            font-size: 1.1rem; font-weight: 700; color: #f1f5f9;
            padding: 0 1.5rem 1.5rem; border-bottom: 1px solid #1e293b;
        }
        .sidebar nav { padding: 1rem 0; flex: 1; }
        .sidebar nav a {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.7rem 1.5rem; color: #94a3b8;
            text-decoration: none; font-size: 0.9rem; transition: all 0.2s;
        }
        .sidebar nav a:hover, .sidebar nav a.active {
            background: #1e293b; color: #f1f5f9; border-left: 3px solid #3b82f6;
        }
        .sidebar .logout-btn {
            margin: 0 1rem 1rem;
            padding: 0.7rem; background: #7f1d1d; color: #fca5a5;
            border: none; border-radius: 8px; cursor: pointer;
            font-size: 0.875rem; width: calc(100% - 2rem); text-align: center;
            text-decoration: none; display: block; transition: background 0.2s;
        }
        .sidebar .logout-btn:hover { background: #991b1b; }

        /* Main */
        .main { margin-left: 240px; flex: 1; padding: 2rem; }
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem;
        }
        .topbar h1 { font-size: 1.4rem; color: #0f172a; font-weight: 700; }
        .topbar span { font-size: 0.875rem; color: #64748b; }

        /* Alert */
        .alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Card */
        .card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

        /* Table */
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { background: #f8fafc; color: #475569; font-weight: 600; padding: 0.75rem 1rem; text-align: left; border-bottom: 2px solid #e2e8f0; }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
        tr:hover td { background: #f8fafc; }

        /* Badge */
        .badge { padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* Buttons */
        .btn { padding: 0.5rem 1rem; border-radius: 7px; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: none; transition: all 0.2s; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger  { background: #ef4444; color: white; }
        .btn-danger:hover  { background: #dc2626; }
        .btn-secondary { background: #e2e8f0; color: #475569; }
        .btn-secondary:hover { background: #cbd5e1; }

        /* Form */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.85rem; color: #475569; font-weight: 600; margin-bottom: 0.4rem; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.7rem 0.9rem;
            border: 1px solid #cbd5e1; border-radius: 8px;
            font-size: 0.9rem; color: #0f172a; outline: none; transition: border 0.2s;
        }
        .form-group input:focus, .form-group select:focus { border-color: #3b82f6; }
        .form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="brand">🏭 Inventaris Gudang</div>
    <nav>
        <a href="/dashboard">📊 Dashboard</a>
        <a href="/barang">📦 Data Barang</a>
        <a href="/transaksi">🔄 Transaksi</a>
    </nav>
    <a href="/logout" class="logout-btn">⏻ Logout</a>
</div>
<div class="main">
    <div class="topbar">
        <h1><?= esc($title ?? '') ?></h1>
        <span>👤 <?= esc(session()->get('nama_admin')) ?></span>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php
// Deteksi halaman aktif
$uri = service('uri')->getSegment(1);
?>
<nav>
    <a href="/dashboard" class="<?= $uri === 'dashboard' || $uri === '' ? 'active' : '' ?>">📊 Dashboard</a>
    <a href="/barang"    class="<?= $uri === 'barang'    ? 'active' : '' ?>">📦 Data Barang</a>
    <a href="/transaksi" class="<?= $uri === 'transaksi' ? 'active' : '' ?>">🔄 Transaksi</a>
</nav>