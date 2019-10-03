<?php

use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'account', 'namespace' => 'Account'], function (Router $router) : void {
    $router->resource('/', 'AccountApiController', ['only' => ['index']]);
    // $router->resource('bs', 'BsAccountApiController', ['only' => ['store', 'update']]);
    // $router->resource('pl', 'PlAccountApiController', ['only' => ['store', 'update']]);
});
