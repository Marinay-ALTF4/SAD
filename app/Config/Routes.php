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

// Product Routes
$routes->get('/product', 'Product::index');
$routes->post('/product', 'Product::store');
$routes->post('/product/(:num)', 'Product::update/$1');
$routes->post('/product/(:num)/delete', 'Product::delete/$1');

// Settings Routes (Admin Only)
$routes->get('/settings', 'Settings::index', ['filter' => ['auth', 'admin']]);
$routes->post('/settings', 'Settings::save', ['filter' => ['auth', 'admin']]);
$routes->post('/settings/account', 'Settings::account', ['filter' => ['auth', 'admin']]);
$routes->get('/settings/users/new', 'Settings::newUserForm', ['filter' => ['auth', 'admin']]);
$routes->post('/settings/users', 'Settings::addUser', ['filter' => ['auth', 'admin']]);
$routes->post('/settings/users/(:num)/role', 'Settings::updateUserRole/$1', ['filter' => ['auth', 'admin']]);
$routes->post('/settings/users/(:num)/delete', 'Settings::deleteUser/$1', ['filter' => ['auth', 'admin']]);
$routes->post('/settings/users/(:num)/profile', 'Settings::updateUserProfile/$1', ['filter' => ['auth', 'admin']]);

// Orders Routes
$routes->get('/orders', 'Orders::index');
$routes->post('/orders', 'Orders::store');
$routes->post('/orders/(:num)', 'Orders::update/$1');
$routes->post('/orders/(:num)/status', 'Orders::updateStatus/$1');
$routes->post('/orders/(:num)/delete', 'Orders::delete/$1');

// Reports Routes (Admin Only)
$routes->get('/reports', 'Reports::index', ['filter' => ['auth', 'admin']]);

// Expenses Routes
// Allow all authenticated users to view expenses; keep mutations admin-only for control
$routes->get('/expenses', 'Expenses::index', ['filter' => ['auth']]);
$routes->post('/expenses', 'Expenses::store', ['filter' => ['auth', 'admin']]);
$routes->post('/expenses/(:num)', 'Expenses::update/$1', ['filter' => ['auth', 'admin']]);
$routes->post('/expenses/(:num)/delete', 'Expenses::delete/$1', ['filter' => ['auth', 'admin']]);