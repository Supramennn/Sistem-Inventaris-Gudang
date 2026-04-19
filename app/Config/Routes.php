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
    $routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');

    // CRUD Barang
    $routes->get('/barang',              'Barang::index');
    $routes->get('/barang/create',       'Barang::create');
    $routes->post('/barang/store',       'Barang::store');
    $routes->get('/barang/edit/(:num)',  'Barang::edit/$1');
    $routes->post('/barang/update/(:num)', 'Barang::update/$1');
    $routes->get('/barang/delete/(:num)', 'Barang::delete/$1');

    // Transaksi
    $routes->get('/transaksi',                 'Transaksi::index');
    $routes->get('/transaksi/create',          'Transaksi::create');
    $routes->post('/transaksi/store',          'Transaksi::store');
    $routes->get('/transaksi/edit/(:num)',     'Transaksi::edit/$1');
    $routes->post('/transaksi/update/(:num)', 'Transaksi::update/$1');
    $routes->get('/transaksi/delete/(:num)',  'Transaksi::delete/$1');
});
});