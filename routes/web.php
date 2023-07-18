<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'LoginController@login');

$router->group(['middleware' => ['jwt']], function () use ($router) {
    $router->post('/logout', 'LogoutController@logout');

    $router->get('all', 'UserController@all');
    $router->get('/get-by-id', 'UserController@getById');
    $router->post('create', 'UserController@create');
    $router->post('update', 'UserController@update');
    $router->put('update/{id}', 'UserController@update');
    $router->delete('delete/{id}', 'UserController@delete');
    $router->post('destroy', 'UserController@destroy');
    $router->get('/profil', 'UserController@profil');
    $router->post('/upload', 'UserController@upload');
});

