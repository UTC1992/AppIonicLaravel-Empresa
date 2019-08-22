<?php

namespace App\Traits;
use Illuminate\Http\Response;
/**
 *
 */
trait ApiResponser
{
  /**
   * construye respuest de exito
   * resive parametro estado y datos
   */
  public function successResponse($data,$code=Response::HTTP_OK)
  {
      return response()->json(['data'=>$data],$code);
  }

/**
 * construye respuesta de error
 */
  public function errorResponse($message,$code)
  {
    return response()->json(['error'=>$message,'code'=>$code],$code);
  }
}
