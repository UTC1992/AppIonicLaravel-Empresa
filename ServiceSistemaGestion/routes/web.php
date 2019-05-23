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

/*Route::get('otro', function () {
    return view('welcome');
});

Route::get('/','Angular\ImportController@index')->name('index');
Route::post('cargar','Angular\ImportController@getExcel2')->name('cargar');
Route::post('cargar2','Angular\ImportController@getExcel')->name('cargar2');
Route::get('cargar3','Angular\ImportController@importCsvFile')->name('cargar3');
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/', 'HomeController@index')->name('home');
//Route::get('/home', 'HomeController@index')->name('home');

/*Route::prefix('users')->group(function(){
  Route::post('/logout','Auth\LoginController@userLogout')->name('user.logout');
  Route::get('/perfil','HomeController@showPerfil')->name('user.perfil');
});
*/

Route::prefix('admin')->group(function(){
  Route::get('/inicio', 'AdminController@index')->name('admin.dashboard');
  Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');

  Route::get('/logout','Auth\AdminLoginController@logout')->name('admin.logout');

  //password reset route
  Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
  Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
  Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
  Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');

  //perfil
  Route::get('/perfil','AdminController@showPerfil')->name('admin.perfil');
  Route::post('/updateperfil','AdminController@updatePerfil')->name('admin.update-perfil');
  Route::post('/updatepassword','AdminController@updatePassword')->name('admin.update-password');

});

Route::prefix('empresa')->group(function(){
   //empresa
  Route::get('/inicio','Admin\EmpresaController@index')->name('empresa.inicio');
  Route::post('/save','Admin\EmpresaController@guardar')->name('empresa.guardar');
  Route::get('/edit/{id}','Admin\EmpresaController@edit')->name('empresa.editar');
  Route::post('/update','Admin\EmpresaController@updateEmp')->name('empresa.update');
  Route::get('/delete/{id}','Admin\EmpresaController@deleteEmp')->name('empresa.delete');
  Route::get('gestion/{id}','Admin\EmpresaController@gestion')->name('empresa.gestion');
});

Route::group(['prefix' => 'modulo'], function(){
   //modulos
  Route::get('/inicio','Admin\ModuloController@index')->name('modulo.inicio');
  Route::post('/save','Admin\ModuloController@guardar')->name('modulo.guardar');
  Route::get('/edit/{id}','Admin\ModuloController@edit')->name('modulo.editar');
  Route::post('/update','Admin\ModuloController@updateMod')->name('modulo.update');
  Route::get('/delete/{id}','Admin\ModuloController@deleteMod')->name('modulo.delete');
});

Route::prefix('subscripcion')->group(function(){
   //subscripcion
  Route::get('/inicio','Admin\SubscripcionController@index')->name('subscripcion.inicio');
  Route::post('/save','Admin\SubscripcionController@guardar')->name('subscripcion.guardar');
  Route::get('/edit/{id}','Admin\SubscripcionController@edit')->name('subscripcion.editar');
  Route::post('/update','Admin\SubscripcionController@updateSub')->name('subscripcion.update');
  Route::get('/delete/{id}','Admin\SubscripcionController@deleteSub')->name('subscripcion.delete');
});

Route::prefix('user')->group(function(){
   //subscripcion
  Route::get('/inicio','Admin\UserController@index')->name('user.inicio');
  Route::post('/save','Auth\RegisterController@registerUser')->name('user.registerUser');
  Route::get('/edit/{id}','Admin\UserController@edit')->name('user.editar');
  Route::post('/update','Admin\UserController@updateUser')->name('user.update');
  Route::post('/updatepass','Admin\UserController@updatePassword')->name('user.updatePassword');
  Route::get('/delete/{id}','Admin\UserController@deleteUser')->name('user.delete');
});

Route::prefix('gestion')->group(function(){
   //servicios
  Route::get('/configuracion/{id}', 'Gateway\Lecturas\ConfigController@index');
  Route::post('/configuracion', 'Gateway\Lecturas\ConfigController@crear');
  Route::post('/configuracion/update', 'Gateway\Lecturas\ConfigController@update');
  Route::get('/configuracion/delete/{id}', 'Gateway\Lecturas\ConfigController@destroy');
  Route::post('/configuracion/create', 'Gateway\Lecturas\ConfigController@crearConfiguracion');
  Route::get('/configuracion/validate/{id}', 'Gateway\Lecturas\ConfigController@validateTableConfig');
  Route::get('/configuracion/drop/{id}', 'Gateway\Lecturas\ConfigController@dropTable');
});

Route::prefix('plan')->group(function(){
  //subscripcion
  Route::get('/inicio','Admin\PlanController@index')->name('plan.inicio');
  Route::post('/save','Admin\PlanController@guardar')->name('plan.guardar');
  Route::get('/edit/{id}','Admin\PlanController@edit')->name('plan.editar');
  Route::post('/update','Admin\PlanController@updatePlan')->name('plan.update');
  Route::get('/delete/{id}','Admin\PlanController@deletePlan')->name('plan.delete');
});

Route::prefix('historial')->group(function(){
  //subscripcion
  Route::get('/inicio','Admin\HistorialController@index')->name('historial.inicio');
  Route::post('/inicio','Admin\HistorialController@consultar')->name('historial.consultar');
});
