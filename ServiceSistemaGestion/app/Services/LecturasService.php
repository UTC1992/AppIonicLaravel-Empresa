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

    /**
     * upload txt
     */
  public function uploadFile($file,$id,$mes){
    return $this->performRequestFiles('POST',"/upload",$file,$id,$mes);
  }

  /**
   * obtener campos de distribución
   */
  public function getFilterFields($data){
    return $this->performRequest('POST',"/filtros",$data);
  }

  /**
   * obtener datos del primer filtro
   */
   public function getFirstFilterFields($data){
     return $this->performRequest('POST',"/data-first",$data);
   }

   /**
    * obtener datos de filtro
    */
  public function getDataFilter($data){
     return $this->performRequest('POST',"/data-filter",$data);
  }

  /**
   * obtener datos de distribucioin mediante filtros
   */
   public function getDataDistributionService($data){
     return $this->performRequest('POST',"/data",$data);
   }

   /**
    * distribuir rutas de trabajo a técnico seleccionado
    */

    public function distribuirRutaService($data){
      return $this->performRequest('POST',"/rutas/distribuir",$data);
    }

    /**
     *obtiene rutas distribuidas a técnicos
     */
  public function orderTrabajoTecnicosService($idEmpresa){
       return $this->performRequest('GET',"/orden-trabajo/{$idEmpresa}");
  }

/**
 * obtien lecturas consolidadas por mes
 */
  public function lecturasConsolidadasService($idEmpresa,$mes){
    return $this->performRequest('GET',"/consolidados/{$idEmpresa}/{$mes}");
  }

  /**
   * procesar catatros del mes
   */
  public function procesarCatastrosService(){
    return $this->performRequest('GET',"/catastros/proceso");
  }
/**
 * procesar y actualizar ordn de trabajo temporal
 */
  public function procesarOrdenTemporalService(){
    return $this->performRequest('GET',"/procesos/actualiar");
  }

/**
 *generar historial
 */
  public function guardarHistorialService(){
    return $this->performRequest('GET',"/procesos/historial");
  }

/**
 * generar orden de trabajo para lecturas
 */
  public function generarOrdenTempService(){
    return $this->performRequest('GET',"/procesos/oden-temp");
  }

  /**
   * generar orden de trabajo para lecturas
   */
  public function obtenerRutasDecobo(){
      return $this->performRequest('GET',"/rutas");
  }

    /**
     * obtiene rutas tecnicos decobo
     */
  public function obtenerRutasAsignadasTecnicos(){
      return $this->performRequest('GET',"/rutas/tecnicos");
  }
    /**
       * eliminar asignacion tecnicos decobo
      */
  public function deleteAsignacion($data){
        return $this->performRequest('POST',"/rutas/delete-asignacion",$data);
    }

  /**
   * validar lecturas
   */
   public function validarLecturasServices($agencia){
     return $this->performRequest('GET',"/procesos/valida-lecturas-final/{$agencia}");
   }

   /**
    * calcular consumos
    */
    public function calculaConsumosService($agencia){
      return $this->performRequest('GET',"/procesos/calcular-consumo/{$agencia}");
    }
  /**
   * valida consumos
   */
   public function validaConsumosService($agencia){
     return $this->performRequest('GET',"/procesos/valida-consumos/{$agencia}");
   }

/**
 *  REPORTES
 */

 public function getReporteLecturasService($data){
   return $this->performRequest('POST',"/reportes/avance",$data);
 }

 /**
  * errores de consumo
  */
  public function getReporteErroresConsumoService(){
    return $this->performRequest('GET',"/reportes/errores-consumo");
  }

/**
 * errores lecturas
 */
 public function getReporteErroresLecturasService(){
   return $this->performRequest('GET',"/reportes/errores-lecturas");
 }

 /**
  * reporte sobre envios
  */
  public function getReporteEnviosService($mes){
    return $this->performRequest('GET',"/reportes/envios/{$mes}");
  }

  /**
   * valida lectura menores
   */
  public function validaLecturasMenoresServices($agencia){
    return $this->performRequest('GET',"/procesos/valida-lectura-menor/{$agencia}");
  }

  /**
   * actualiza registros de lecturas
   */
  public function uploadBackupFileService($data){
     return $this->performRequestFileBackup('POST',"/lecturas/update",$data);
  }

  /**
   * sctualiza distribucion temoral decobo
   */

   public function actualizarDistribucionService(){
     return $this->performRequest('GET',"/rutas/actualizar");
   }

   /**
    * borrar decobo tabla
    */
    public function borrarTablaDecoboService(){
       return $this->performRequest('GET',"/rutas/borrar/decobo");
    }
}
