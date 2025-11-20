<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');            // Redirect to login page

$routes->get('/login', 'Auth::index');       // Display login form
$routes->post('/login', 'Auth::login');      // Process form submission
$routes->get('/logout', 'Auth::logout');     // Logout
$routes->get('/dashboard', 'Home::index');   // Dashboard (after login)

$routes->get('/product', 'Product::index');
$routes->post('/product', 'Product::store');
$routes->post('/product/(:num)', 'Product::update/$1');
$routes->post('/product/(:num)/delete', 'Product::delete/$1');

$routes->get('/settings', 'Settings::index');
$routes->post('/settings', 'Settings::save');
$routes->post('/settings/account', 'Settings::account');
$routes->get('/settings/users/new', 'Settings::newUserForm');
$routes->post('/settings/users', 'Settings::addUser');

$routes->get('/orders', 'Orders::index');
$routes->post('/orders', 'Orders::store');
$routes->post('/orders/(:num)', 'Orders::update/$1');
$routes->post('/orders/(:num)/status', 'Orders::updateStatus/$1');
$routes->post('/orders/(:num)/delete', 'Orders::delete/$1');

$routes->get('/reports', 'Reports::index');

$routes->get('/expenses', 'Expenses::index');