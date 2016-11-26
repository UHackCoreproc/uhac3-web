<?php

use Illuminate\Http\Request;
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
Route::group([
    'prefix' => 'v1',
    'namespace' => 'V1',
], function (Router $router) {

    /*
     * GROUP
     * prefix api/v1/auth/
     * as api.auth
     * */
    $router->group([
        'prefix' => 'auth',
        'as' => 'auth.'
    ], function(Router $router) {

        /*
         * POST
         * prefix api/v1/auth/login
         * as api.auth.login
         *
         * */
        $router->post('login',[
            'as' => 'login',
            'uses' => 'AuthController@login'
        ]);

    });

});
