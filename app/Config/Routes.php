<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');


// Proteksi route untuk yang sudah login
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    // Tambahkan route protected lainnya di sini
});
