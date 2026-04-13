<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', function () { // redirect to login
    return redirect()->to('/login');
});


// AUTH //
$routes->get('/register','Auth::register');
$routes->post('/register','Auth::store');

$routes->get('/login','Auth::login');
$routes->post('/login','Auth::authenticate');

$routes->get('/logout','Auth::logout');
$routes->get('/unauthorized', 'Auth::unauthorized');


// DASHBOARD //
$routes->get('/dashboard','Dashboard::index', [
    'filter' => ['auth', 'role:admin,teacher,coordinator'] // protected access
]);


// STUDENT PROFILE //
$routes->group('', ['filter' => ['auth', 'role:student']], function($routes){

    $routes->get('/profile','ProfileController::show');
    $routes->get('/profile/edit','ProfileController::edit');
    $routes->post('/profile/update','ProfileController::update');

});


// STUDENTS MANAGEMENT //
$routes->group('', ['filter' => ['auth']], function($routes){

    $routes->get('/students','Students::index', [
        'filter' => 'role:admin,teacher,coordinator'
    ]);

    $routes->get('/students/create','Students::create', [
        'filter' => 'role:admin,teacher'
    ]);

    $routes->post('/students/store','Students::store', [
        'filter' => 'role:admin,teacher'
    ]);

    $routes->post('/students/update/(:num)','Students::update/$1', [
        'filter' => 'role:admin,teacher,coordinator'
    ]);

    $routes->post('/students/delete/(:num)','Students::delete/$1', [
        'filter' => 'role:admin,teacher'
    ]);

});


// ADMIN //
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes){

    $routes->get('users','AdminController::index');
    $routes->post('users/assign-role/(:num)','AdminController::assignRole/$1');
    $routes->match(['get','post'], 'users/delete/(:num)','AdminController::delete/$1');

});


// API ROUTES //
$routes->group('api', function($routes){

    // Students API
    $routes->get('students', 'Api\\StudentApi::index');
    $routes->get('students/(:num)', 'Api\\StudentApi::show/$1');
    $routes->post('students', 'Api\\StudentApi::create');
    $routes->put('students/(:num)', 'Api\\StudentApi::update/$1');
    $routes->delete('students/(:num)', 'Api\\StudentApi::delete/$1');

    // Users API
    $routes->get('users', 'Api\\UserApi::index');
    $routes->get('users/(:num)', 'Api\\UserApi::show/$1');
    $routes->delete('users/(:num)', 'Api\\UserApi::delete/$1');

    // Profile API
    $routes->get('profile/(:num)', 'Api\\ProfileApi::show/$1');
    $routes->put('profile/(:num)', 'Api\\ProfileApi::update/$1');

    // Auth API
    $routes->post('login', 'Api\\AuthApi::login');
    $routes->post('register', 'Api\\AuthApi::register');

});