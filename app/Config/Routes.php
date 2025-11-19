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

