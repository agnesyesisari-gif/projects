<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Routes untuk Jadwal Ibadah (menggunakan controller yang ada)
$routes->get('jadwal-ibadah', 'Admin\JadwalController::index');
$routes->get('jadwal-ibadah/(:segment)', 'Admin\JadwalController::detail/$1');

// Routes untuk Program Kerja (menggunakan controller yang ada)
$routes->get('program-kerja', 'Admin\KomisiController::index');
$routes->get('program-kerja/(:segment)', 'Admin\KomisiController::detail/$1');