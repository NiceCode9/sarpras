<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->post('/logout', 'Auth::logout');


// Proteksi route untuk yang sudah login
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'Dashboard::index');

    // Sarana Routes
    $routes->get('/sarana', 'Sarana::index');
    $routes->get('/sarana/create', 'Sarana::create');
    $routes->post('/sarana/store', 'Sarana::store');
    $routes->get('/sarana/edit/(:num)', 'Sarana::edit/$1');
    $routes->post('/sarana/update/(:num)', 'Sarana::update/$1');
    $routes->get('/sarana/delete/(:num)', 'Sarana::delete/$1');

    // Peminjaman Routes
    $routes->get('/peminjaman', 'Peminjaman::index');
    $routes->post('/peminjaman/create', 'Peminjaman::create');
    $routes->get('/peminjaman/admin', 'Peminjaman::admin');
    $routes->get('/peminjaman/action/(:num)/(:any)', 'Peminjaman::action/$1/$2');

    // Pemeliharaan Routes
    $routes->get('/pemeliharaan', 'Pemeliharaan::index');
    $routes->post('/pemeliharaan/store', 'Pemeliharaan::store');
    $routes->get('/pemeliharaan/calendar', 'Pemeliharaan::calendar');
    $routes->get('/pemeliharaan/delete/(:num)', 'Pemeliharaan::delete/$1');

    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/pengguna', 'Pengguna::index');
        $routes->get('/pengguna/create', 'Pengguna::create');
        $routes->post('/pengguna/store', 'Pengguna::store');
        $routes->get('/pengguna/edit/(:num)', 'Pengguna::edit/$1');
        $routes->post('/pengguna/update/(:num)', 'Pengguna::update/$1');
        $routes->get('/pengguna/delete/(:num)', 'Pengguna::delete/$1');

        $routes->get('/laporan', 'Laporan::index');
        $routes->get('/laporan/exportPDF', 'Laporan::exportPDF');
        $routes->get('/laporan/exportExcel', 'Laporan::exportExcel');

        $routes->get('/setting', 'Setting::index');
        $routes->post('/setting/updateDenda', 'Setting::updateDenda');
    });
});
