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

Route::get('otro', function () {
    return view('welcome');
});

Route::get('/','Angular\ImportController@index')->name('index');
Route::post('cargar','Angular\ImportController@getExcel2')->name('cargar');
Route::post('cargar2','Angular\ImportController@getExcel')->name('cargar2');
Route::get('cargar3','Angular\ImportController@importCsvFile')->name('cargar3');
