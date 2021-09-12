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
//to generate key uncomment the below code and type '/key' in the URL
// $router->get('/key', function() {
//     return \Illuminate\Support\Str::random(32);
// });

$router->get('/image','ImageController@index');
$router->get('/image/{id}','ImageController@show');
$router->post('/image/store','ImageController@store');
$router->put('/image/update/{id}','ImageController@update');
$router->put('/image/remove/{id}','ImageController@Softdelete'); //soft delete images
$router->delete('/image/delete/{id}','ImageController@delete');
