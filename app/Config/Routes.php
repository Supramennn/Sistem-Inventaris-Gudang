<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/',        'Auth::index');
$routes->get('/login',   'Auth::index');
$routes->post('/login',  'Auth::login');
$routes->get('/logout',  'Auth::logout');

// Semua route ini wajib login
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    // Nanti tambah route barang & transaksi di sini
});