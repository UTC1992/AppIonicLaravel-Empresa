<?php

namespace App\Services;


use App\Traits\ConsumesExternalService;

/**
 * Service Lecturas
 */
class LecturasService
{

  use ConsumesExternalService;

/**
 * url base del servicio a consumir
 */
  public $baseUrl;

/**
 * key autentication service
 */
  public $secret;

  public function __construct()
  {
      $this->baseUrl=config('services.lecturas.base_url');
      $this->secret=config('services.lecturas.secret');
  }

  /**
   * servicio para obteener configuracion
   */
  public function getConfigCompany($id){
    return $this->performRequest('GET',"/configuraciones/{$id}");
  }

/**
 * crea una nueva instancia de cofiguracion
 */

 public function createNewConfigCompany($data){
   return $this->performRequest('POST',"/configuraciones",$data);
 }

/**
 * update instancia de configuracion
 */
 public function updateConfigCompany($data){
   return $this->performRequest('POST',"/configuraciones/update",$data);
 }
/**
 *borrar cinstancia de configuracion
 */

 public function deleteConfigCompany($id){
   return $this->performRequest('GET',"/configuraciones/delete/{$id}");
 }

 /**
  * generar configuracion
  */

  public function configGenerateCompany($data){
    return $this->performRequest('POST',"/configuraciones/crear",$data);
  }

  /**
   * validar exixtencia de confoguracion
   */
   public function validarConfiguracion($id){
     return $this->performRequest('GET',"/configuraciones/validate/{$id}");
   }

   /**
    * borrar tabla de actividdes de la empresa
    */

    public function dropTable($id){
      return $this->performRequest('GET',"/configuraciones/drop/{$id}");
    }
}
