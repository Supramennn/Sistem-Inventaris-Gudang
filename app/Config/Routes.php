<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('barang', 'Barang::index');
    $routes->group('', ['filter' => 'role:admin,operator'], static function (RouteCollection $routes): void {
        $routes->get('barang/create', 'Barang::create');
        $routes->post('barang/store', 'Barang::store');
        $routes->get('barang/edit/(:num)', 'Barang::edit/$1');
        $routes->post('barang/update/(:num)', 'Barang::update/$1');
        $routes->post('barang/delete/(:num)', 'Barang::delete/$1');
    });

    $routes->group('', ['filter' => 'role:admin'], static function (RouteCollection $routes): void {
        $routes->get('master-data', 'MasterData::index');
        $routes->get('master-data/(:segment)', 'MasterData::index/$1');
        $routes->get('master-data/(:segment)/edit/(:num)', 'MasterData::edit/$1/$2');
        $routes->post('master-data/(:segment)/store', 'MasterData::store/$1');
        $routes->post('master-data/(:segment)/update/(:num)', 'MasterData::update/$1/$2');
        $routes->post('master-data/(:segment)/delete/(:num)', 'MasterData::delete/$1/$2');
    });

    $routes->get('transaksi', 'Transaksi::index');
    $routes->group('', ['filter' => 'role:admin,operator'], static function (RouteCollection $routes): void {
        $routes->get('transaksi/create', 'Transaksi::create');
        $routes->post('transaksi/store', 'Transaksi::store');
        $routes->get('transaksi/edit/(:num)', 'Transaksi::edit/$1');
        $routes->post('transaksi/update/(:num)', 'Transaksi::update/$1');
        $routes->post('transaksi/delete/(:num)', 'Transaksi::delete/$1');
    });
});
