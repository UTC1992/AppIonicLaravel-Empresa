<?php

namespace App\Services;


use App\Traits\ConsumesExternalService;

/**
 * Service Lecturas
 */
class LecturasAppService
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
   * servicio para obtener rutas distribuidas a técnicos
   */
  public function getDataDistributionByTecnicoService($idEmpresa,$idTecnico){
    return $this->performRequest('GET',"/data-movil/{$idEmpresa}/{$idTecnico}");
  }

  /**
   * insertar catastros
   */
   public function insertarCatastrosService($data){
     return $this->performRequest('POST',"/catastros-insert",$data);
   }

   /**
    * recibe y actualiza datos de lectutas y orden rabajo
    */
    public function updateLecturasService($data){
      return $this->performRequest('POST',"/lecturas-movil",$data);
    }

}
