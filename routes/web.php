<?php

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

$router->get('/node/{id}', 'NodeController@show');
$router->post('/node', 'NodeController@store');
$router->delete('/node/{id}', 'NodeController@delete');
$router->delete('/node/{id}/save-children', 'NodeController@deleteSaveChildren');
$router->patch('/node/{id}', 'NodeController@update');