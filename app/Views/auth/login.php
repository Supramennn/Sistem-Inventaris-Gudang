<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Inventaris Gudang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #1e293b;
            padding: 2.5rem;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }
        h1 { color: #f1f5f9; font-size: 1.5rem; margin-bottom: 0.25rem; }
        p.sub { color: #64748b; font-size: 0.875rem; margin-bottom: 2rem; }
        label { display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.4rem; }
        input {
            width: 100%; padding: 0.75rem 1rem;
            background: #0f172a; border: 1px solid #334155;
            border-radius: 8px; color: #f1f5f9;
            font-size: 0.95rem; margin-bottom: 1.25rem;
            outline: none; transition: border 0.2s;
        }
        input:focus { border-color: #3b82f6; }
        button {
            width: 100%; padding: 0.85rem;
            background: #3b82f6; color: white;
            border: none; border-radius: 8px;
            font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
        }
        button:hover { background: #2563eb; }
        .alert {
            padding: 0.75rem 1rem; border-radius: 8px;
            margin-bottom: 1.25rem; font-size: 0.875rem;
        }
        .alert-error { background: #450a0a; color: #fca5a5; border: 1px solid #7f1d1d; }
    </style>
</head>
<body>
<div class="card">
    <h1>🏭 Inventaris Gudang</h1>
    <p class="sub">Masuk untuk melanjutkan</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <?= csrf_field() ?>
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required autofocus>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
        <button type="submit">Masuk</button>
    </form>
</div>
</body>
</html>