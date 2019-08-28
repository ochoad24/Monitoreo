<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::get('/', function () {
        return view('content');
    });
    // Rutas para los roles
    Route::get('/Rol','RolController@index');
    Route::post('/Rol/nuevo','RolController@store');
    Route::put('/Rol/editar','RolController@edit');
    Route::delete('/Rol/{rol}/delete', 'RolController@drop');
