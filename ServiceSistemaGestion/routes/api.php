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

Route::get('usuarioAutenticado','AuthApi\SecurityController@getUserData');
Route::post('empresaActiva','AuthApi\SecurityController@validarEmpresaActiva');
Route::post('editEmpresa','AuthApi\SecurityController@editarEmpresa');
Route::post('editNombre','AuthApi\SecurityController@editarNombre');
Route::post('editEmail','AuthApi\SecurityController@editarEmail');
Route::post('editPassword','AuthApi\SecurityController@editarPassword');

//rutas de servicio cliente angular
Route::group(['prefix' => 'angular'], function(){
    Route::resource('ordenes','Angular\OrdenTempController');
    Route::resource('tecnicos','Angular\TecnicoController');
    Route::get('delete-tecnico/{id_tecnico}','Angular\TecnicoController@delete');
    Route::get('get-tecnico/{id_tecnico}','Angular\TecnicoController@getTecnicoById');
    Route::post('update-tecnico','Angular\TecnicoController@editTecnicoAngular');
    Route::post('build-task','Angular\TecnicoController@buildTaskTecnicos');
    Route::get('actividades-tecnicos','Angular\ActividadDiariaController@getActivitadesTecnico');
    Route::get('tecnicos-sin-actividades','Angular\TecnicoController@getTecnicosSinActividades');
    Route::get('actividades-tecnico/{id_tecn}/{tipo}/{sector}','Angular\ActividadDiariaController@getActivitiesTecnico');
    Route::get('finalizar/{id_tecn}','Angular\ActividadDiariaController@validateActivitiesByTecnico');
    Route::get('actividades-fecha/{created_at}/{id_tecn}/{actividad}/{estado}','Angular\ActividadDiariaController@getActivitiesToDay');
    Route::get('cantones/{type}','Angular\ActividadDiariaController@getCantonesActividades');
    Route::get('sectores/{actividad}/{canton}','Angular\ActividadDiariaController@getSectores');
    Route::get('cantidad-actividades/{actividad}/{canton}/{sector}','Angular\ActividadDiariaController@getActivitiesBySectors');
    Route::post('cantidad-post','Angular\ActividadDiariaController@getActivitiesBySectorsPost');
    Route::get('cambiar-estado/{id}','Angular\TecnicoController@changeStateTecnico');
    Route::get('validar-rec','Angular\ActividadDiariaController@validarActividadesManuales');
    Route::get('cont-rec','Angular\ActividadDiariaController@getRecManualesSinProcesar');
    Route::get('consolidar-actividades/{created_at}','Angular\ActividadDiariaController@consolidarActividadesDiarias');
    Route::get('actividades-consolidadas/{created_at}','Angular\ActividadDiariaController@getActividadesByDate');
    Route::get('mostrar-distribucion','Angular\ActividadDiariaController@getDistribucion');
    Route::get('delete-distribucion/{id_tecn}/{sector}/{cantidad}/{tipo}','Angular\OrdenTempController@deleteDistribucion');
    //Route::get('export/{type}','Angular\ActividadDiariaController@exportExcelConsolidado');
    Route::get('delete-activities','Angular\ActividadDiariaController@eliminarActividades');
});

Route::group(['prefix' => 'mobile'], function(){
    Route::get('get-data/{cedula}','Mobile\MobileController@getTechnicalData');
    Route::post('insert-data','Mobile\MobileController@insertReconexionManual');
    Route::post('update-activities','Mobile\MobileController@updateActivities');
  });

Route::get('export/{date}/{empresa}','Angular\ImportController@exportExcelConsolidado');
