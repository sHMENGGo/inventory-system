<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------------------------------------------------------------------
// Authentication (guests only)
// ---------------------------------------------------------------------------
$routes->get('login', 'AuthController::showLogin');
$routes->post('login', 'AuthController::login');
$routes->get('register', 'AuthController::showRegister');
$routes->post('register', 'AuthController::register');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// ---------------------------------------------------------------------------
// Dashboard (any logged-in user)
// ---------------------------------------------------------------------------
$routes->get('/', 'Home::index', ['filter' => 'auth']);

// ---------------------------------------------------------------------------
// Items — everyone can look, only admins can change
// ---------------------------------------------------------------------------
$routes->get('items', 'ItemController::index', ['filter' => 'auth']);

$routes->group('items', ['filter' => 'admin'], static function ($routes) {
    $routes->get('new', 'ItemController::create');
    $routes->post('/', 'ItemController::store');
    $routes->get('(:segment)/edit', 'ItemController::edit/$1');
    $routes->post('(:segment)', 'ItemController::update/$1');
    $routes->post('(:segment)/delete', 'ItemController::delete/$1');
});

// ---------------------------------------------------------------------------
// Users — administrators only
// ---------------------------------------------------------------------------
$routes->group('users', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('new', 'UserController::create');
    $routes->post('/', 'UserController::store');
    $routes->get('(:segment)/edit', 'UserController::edit/$1');
    $routes->post('(:segment)', 'UserController::update/$1');
    $routes->post('(:segment)/delete', 'UserController::delete/$1');
});

// ---------------------------------------------------------------------------
// Requests — logged-in users see and manage their own
// ---------------------------------------------------------------------------
$routes->group('requests', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'RequestController::index');
    $routes->get('new', 'RequestController::create');
    $routes->post('/', 'RequestController::store');
    $routes->get('(:segment)', 'RequestController::show/$1');
    $routes->get('(:segment)/edit', 'RequestController::edit/$1');
    $routes->post('(:segment)', 'RequestController::update/$1');
    $routes->post('(:segment)/delete', 'RequestController::delete/$1');
    $routes->post('(:segment)/comments', 'RequestController::comment/$1');
});

// Workflow actions — administrators only.
$routes->group('requests', ['filter' => 'admin'], static function ($routes) {
    $routes->post('(:segment)/approve', 'RequestController::approve/$1');
    $routes->post('(:segment)/reject', 'RequestController::reject/$1');
    $routes->post('(:segment)/receive', 'RequestController::receive/$1');
});
