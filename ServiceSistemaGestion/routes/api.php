<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//rutas de servicio cliente angular
Route::group(['prefix' => 'angular', 'middleware' => 'cors'], function(){
    Route::resource('ordenes','Angular\OrdenTempController');
    Route::resource('tecnicos','Angular\TecnicoController');
    Route::get('delete-tecnico/{id_tecnico}','Angular\TecnicoController@delete');
    Route::get('get-tecnico/{id_tecnico}','Angular\TecnicoController@getTecnicoById');
    Route::post('update-tecnico','Angular\TecnicoController@editTecnicoAngular');
    Route::get('build-task/{tipo}/{id_tecnico}','Angular\TecnicoController@buildTaskTecnicos');
    Route::get('actividades-tecnicos','Angular\ActividadDiariaController@getActivitadesTecnico');
    Route::get('tecnicos-sin-actividades','Angular\TecnicoController@getTecnicosSinActividades');
    Route::get('actividades-tecnico/{id_tecn}','Angular\ActividadDiariaController@getActivitiesTecnico');
});

Route::group(['prefix' => 'mobile', 'middleware' => 'cors'], function(){
    Route::get('get-data/{cedula}','Mobile\MobileController@getTechnicalData');
    Route::post('insert-data','Mobile\MobileController@insertReconexionManual');
    Route::post('update-activities','Mobile\MobileController@updateActivities');
  });
