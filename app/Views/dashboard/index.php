<?php
// Assuming session_start() is called earlier in the script
$nama_admin = $_SESSION['nama_admin'] ?? 'Guest'; // Retrieve from session with fallback
?>
<h1>Halo, <?= esc($nama_admin) ?>! Dashboard coming soon...</h1>
<a href="/logout">Logout</a>