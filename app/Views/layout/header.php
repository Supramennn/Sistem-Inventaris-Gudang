<?php $uri = service('uri')->getSegment(1); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inventaris Gudang') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">Inventaris Gudang</div>
        <nav class="sidebar-nav" aria-label="Menu utama">
            <a href="<?= site_url('dashboard') ?>" class="<?= $uri === 'dashboard' || $uri === '' ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= site_url('barang') ?>" class="<?= $uri === 'barang' ? 'active' : '' ?>">Data Barang</a>
            <a href="<?= site_url('transaksi') ?>" class="<?= $uri === 'transaksi' ? 'active' : '' ?>">Transaksi</a>
        </nav>
        <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </aside>

    <main class="main">
        <header class="topbar">
            <h1><?= esc($title ?? '') ?></h1>
            <span class="topbar-user"><?= esc(session()->get('nama_admin') ?? 'Admin') ?></span>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
