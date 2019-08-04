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
/**
 * ruta de gestion usuario y permisos
 */
Route::get('usuarioAutenticado','AuthApi\SecurityController@getUserData');
Route::post('empresaActiva','AuthApi\SecurityController@validarEmpresaActiva');
Route::post('editEmpresa','AuthApi\SecurityController@editarEmpresa');
Route::post('editNombre','AuthApi\SecurityController@editarNombre');
Route::post('editEmail','AuthApi\SecurityController@editarEmail');
Route::post('editPassword','AuthApi\SecurityController@editarPassword');
Route::get('modulos','AuthApi\PermisosController@getModulosEmpresa');


//rutas de servicio cliente angular
Route::group(['prefix' => 'angular'], function(){
    Route::resource('ordenes','Angular\OrdenTempController');
    Route::resource('tecnicos','Angular\TecnicoController');
    Route::get('tecnicos-cortes','Angular\TecnicoController@getTecnicosCortes');
    Route::get('tecnicos-lecturas','Angular\TecnicoController@getTecnicosLecturas');
    Route::get('delete-tecnico/{id_tecnico}','Angular\TecnicoController@delete');
    Route::get('get-tecnico/{id_tecnico}','Angular\TecnicoController@getTecnicoById');
    Route::post('update-tecnico','Angular\TecnicoController@editTecnicoAngular');
    Route::post('build-task','Angular\TecnicoController@buildTaskTecnicos');
    Route::get('actividades-tecnicos','Angular\ActividadDiariaController@getActivitadesTecnico');
    Route::get('tecnicos-sin-actividades','Angular\TecnicoController@getTecnicosSinActividades');
    Route::get('tecnicos-sin-lecturas','Angular\TecnicoController@getTecnicosSinActividadesLecturas');
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
    Route::post('delete-activities','Angular\ActividadDiariaController@eliminarActividades');
	  Route::post('get-rec-manual','Angular\TecnicoController@getReconexionesManualesTecnico');
    Route::get('planes','AuthApi\PermisosController@getPlanEmpresa');

    // gateway routes

    Route::get('rutas','Gateway\Lecturas\LecturasController@obtenerRutasDecobo');
    Route::get('rutas/asignadas','Gateway\Lecturas\LecturasController@obtenerRutasAsignadas');
    Route::get('rutas/create','Gateway\Lecturas\LecturasController@asignarRutas');
    Route::post('rutas/distribucion','Gateway\Lecturas\LecturasController@distribuirRuta');


    Route::post('upload','Gateway\Lecturas\LecturasController@uploadFile');
    Route::get('filtros','Gateway\Lecturas\LecturasController@getFilterFiels');
    Route::get('data-first','Gateway\Lecturas\LecturasController@getFirstFilterFields');
    Route::post('data','Gateway\Lecturas\LecturasController@getDataFilter');
    Route::post('data-distribution','Gateway\Lecturas\LecturasController@getDataDistribution');


    Route::get('orden-trabajo','Gateway\Lecturas\LecturasController@getOrdenTrabajoTecnicosLecturas');
    Route::get('catastros/proceso','Gateway\Lecturas\LecturasController@procesarCatastros');
    Route::get('procesos/orden-temp','Gateway\Lecturas\LecturasController@procesarActualizarOrdenTemporal');
    Route::get('procesos/historial','Gateway\Lecturas\LecturasController@guardarHistorial');
    Route::get('procesos/orden-trabajo','Gateway\Lecturas\LecturasController@generarOrdenTrabajo');
    Route::get('procesos/valida-lecturas-final/{agencia}','Gateway\Lecturas\LecturasController@validarLecturas');
    Route::get('procesos/valida-consumos/{agencia}','Gateway\Lecturas\LecturasController@validaConsumos');
    Route::get('procesos/calcula-consumos/{agencia}','Gateway\Lecturas\LecturasController@calculaConsumosService');
    Route::get('procesos/valida-lecturas-menores/{agencia}','Gateway\Lecturas\LecturasController@validaLecturasMenores');


    // observaciones
    Route::get('observaciones','AuthApi\PermisosController@getObservacionesEmpresa');
    Route::post('observaciones','AuthApi\PermisosController@crearObservacion');
    Route::post('update-observacion','AuthApi\PermisosController@actualizarObservacion');
    Route::get('get-observacion/{id_obs}','AuthApi\PermisosController@getObservacionById');
    Route::get('borrar-observacion/{id_obs}','AuthApi\PermisosController@borrarObservacion');

    Route::get('tecnicos-con-lecturas','Angular\TecnicoController@getTecnicosConActividadesLecturas');
    Route::post('delete-distribution','Gateway\Lecturas\LecturasController@deleteAsignacionLecturas');
    Route::get('distribiciones/actualizar','Gateway\Lecturas\LecturasController@actualizarDistribuciones');
    Route::get('decobo/truncate','Gateway\Lecturas\LecturasController@borrarTablaDecobo');

    //reportes lecturas
    Route::post('reportes/lecturas','Gateway\Lecturas\LecturasController@getLecturasTrabajo');
    Route::get('reportes/error-consumos','Gateway\Lecturas\LecturasController@reporteErroresConsumos');
    Route::get('reportes/error-lecturas','Gateway\Lecturas\LecturasController@reporteErroresLecturas');
    Route::get('reportes/envios/{mes}','Gateway\Lecturas\LecturasController@reporteEnviosLecturas');

    //lecturas backups
    Route::post('lecturas/update','Gateway\Lecturas\LecturasController@uploadBackupFile');
});

Route::group(['prefix' => 'mobile'], function(){
    Route::get('get-data/{cedula}','Mobile\MobileController@getTechnicalData');
    Route::post('insert-data','Mobile\MobileController@insertReconexionManual');
    Route::post('update-activities','Mobile\MobileController@updateActivities');
    // gateway routes Mobile
  });


// login app lecturasA
  //Route::post('app/login/','Gateway\Lecturas\LecturasAppController@login');

// rutas de movil lecturas
  Route::group(['middleware' => 'app'], function () {
      Route::post('login/','Gateway\Lecturas\LecturasAppController@login');
      Route::get('rutas/{idEmpresa}/{idTecnico}','Gateway\Lecturas\LecturasAppController@index');
      Route::post('lecturas','Gateway\Lecturas\LecturasAppController@updateLecturas');
      Route::get('observaciones/{id}','Gateway\Lecturas\LecturasAppController@getObservaciones');
      Route::post('catastros/create','Gateway\Lecturas\LecturasAppController@insertarCatastros');
      Route::get('permisos/borrado/{cedula}/{tipo}','Gateway\Lecturas\LecturasAppController@consultarPermisoBorradoTecnico');
  });

  /**
   * rutas de estadisitca
   */
   Route::group(['prefix' => 'reportes'], function(){
     // estadistica de cortes
       Route::post('cortes-diario','Reportes\ReportesController@getEstadisticaDiariaCortes');
       Route::post('cortes-mes','Reportes\ReportesController@getEstadisticaMesCortes');
       Route::post('cortes-tecnicos-diario','Reportes\ReportesController@getEstadisticaTecnicosCortes');
       Route::post('cortes-tecnicos-mes','Reportes\ReportesController@getEstadisticaTecnicosMesCortes');
       Route::post('medidor-cortes','Reportes\ReportesController@reporteMedidorFecha');
       Route::get('productividad-tecnico/{fecha}','Reportes\ReportesController@productividadTecnico');

     });



Route::get('export/{date}/{empresa}','Angular\ImportController@exportExcelConsolidado');
Route::get('export-lecturas/{idEmpresa}/{mes}','Angular\ImportController@exportarConsolidadoLecturas');

Route::get('export-test/{date}/{empresa}','Angular\ImportController@test');
