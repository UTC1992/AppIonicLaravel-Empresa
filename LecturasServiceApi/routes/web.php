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
$router->post('/filtro','Procesos\ProcesosController@getColumnFilter');

// rutas de carga
$router->post('/upload','Procesos\ProcesosController@carga');
