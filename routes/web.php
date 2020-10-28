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

$router->get('/category', 'CategoryController@listCategory');

$router->post('/addCategory', 'CategoryController@addCategory');

$router->put('/updateCategory/{id}', 'CategoryController@updateCategory');

$router->delete('/deleteCategory/{id}', 'CategoryController@deleteCategory');

