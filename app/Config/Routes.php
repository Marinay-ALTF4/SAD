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

$routes->get('/settings', 'Settings::index');
$routes->post('/settings', 'Settings::save');
$routes->post('/settings/account', 'Settings::account');
$routes->get('/settings/users/new', 'Settings::newUserForm');
$routes->post('/settings/users', 'Settings::addUser');

$routes->get('/orders', 'Orders::index');

$routes->get('/reports', 'Reports::index');

$routes->get('/expenses', 'Expenses::index');