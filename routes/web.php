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

Route::get('/proyecto', 'ProyectoController@index');
Route::post('/proyecto/registrar', 'ProyectoController@store');
Route::put('/proyecto/actualizar', 'ProyectoController@update');

Route::get('/org', 'OrganizacionController@index');
Route::get('/org/dept', 'OrganizacionController@depart');
Route::post('/org/registrar', 'OrganizacionController@store');
Route::put('/org/actualizar', 'OrganizacionController@update');