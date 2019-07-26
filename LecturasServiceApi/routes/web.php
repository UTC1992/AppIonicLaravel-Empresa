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

// rutas de gestion
$router->get('/configuraciones/{id}','Configuracion\ConfigController@index');
$router->post('/configuraciones','Configuracion\ConfigController@store');
$router->post('/configuraciones/update','Configuracion\ConfigController@update');
$router->get('/configuraciones/delete/{id}','Configuracion\ConfigController@destroy');
$router->get('/configuraciones/validate/{id}','Configuracion\ConfigController@validateConfig');
$router->get('/configuraciones/drop/{id}','Configuracion\ConfigController@dropTable');

$router->post('/configuraciones/crear','Configuracion\ConfigController@createTableLecturasEmpresa');
$router->post('/actividades','Procesos\OrdenTrabajoController@getAllActivitiesByEmpresa');
$router->post('/filtros','Procesos\ProcesosController@getColumnsFilter');
$router->post('/data-filter','Procesos\ProcesosController@getDataGroupBy');
$router->post('/data-first','Procesos\ProcesosController@getDataFirstFilter');
$router->post('/data','Procesos\ProcesosController@getDataToDistribution');
$router->get('/orden-trabajo/{idEmpresa}','Procesos\OrdenTrabajoController@getOrdenTrabajoTecnicos');
$router->get('/consolidados/{idEmpresa}/{mes}','Procesos\LecturasController@index');
$router->get('/rutas/elepco','Procesos\OrdenTrabajoController@getRutasElepco');
$router->post('/rutas/distribuir','Procesos\OrdenTrabajoController@distribuirRutasTecnicos');
$router->get('/rutas','Procesos\OrdenTrabajoController@obtenerRutasDecobo');
$router->get('/rutas/tecnicos','Procesos\OrdenTrabajoController@obtenerDistribucionTecnicos');
$router->post('/rutas/delete-asignacion','Procesos\OrdenTrabajoController@deleteRutaTecnico');

$router->post('/lecturas/update','Procesos\LecturasController@subirRespaldos');
/**
 * rutras de orden trabaja distribucion
 */
 $router->post('/distribuir','Procesos\OrdenTrabajoController@distribuirRutas');

// rutas de carga
$router->post('/upload','Procesos\ProcesosController@carga');




/**
 * rutas de procesos
 */
$router->get('/catastros/proceso','Procesos\ProcesosController@procesarCatastros');
$router->get('/procesos/actualiar','Procesos\ProcesosController@actualizarOrdenTrabajo');
$router->get('/procesos/historial','Procesos\ProcesosController@generarGuardarHistorialDecobo');
$router->get('/procesos/oden-temp','Procesos\ProcesosController@generarOrdenTemp');
$router->get('/procesos/calcular-consumo/{agencia}','Procesos\ProcesosController@calcularConsumos');
$router->get('/procesos/valida-lecturas-final/{agencia}','Procesos\ProcesosController@validarLecturas');
$router->get('/procesos/valida-consumos/{agencia}','Procesos\ProcesosController@procesarConsumosFinal');
$router->get('/procesos/valida-lectura-menor/{agencia}','Procesos\ProcesosController@validaLecturaMenor');
//$router->get('/procesos/valid','Procesos\ProcesosController@validarLecturas');



/**
 * reportes
 */

$router->post('/reportes/avance','Procesos\ReportesController@consultarLecturas');
$router->get('/reportes/errores-consumo','Procesos\ReportesController@consultarErroresConsumo');
$router->get('/reportes/errores-lecturas','Procesos\ReportesController@consultarErroresLecturas');
$router->get('/reportes/envios/{fecha}','Procesos\ReportesController@consultarEnvios');

/**
 * rutas de app móvil
 */
$router->get('/data-movil/{idEmpresa}/{idTecnico}','Movil\MobileController@index');
$router->post('/catastros-insert','Movil\MobileController@insertCatastros');
$router->post('/lecturas-movil','Movil\MobileController@recibirLecturas');
