<?php
namespace App\Traits;

use GuzzleHttp\Client;
/**
 *
 */
trait ConsumesExternalService
{
  /**
   * send a request to any service
   * @return string
   */
  public function performRequest($method,$requestUrl,$formParams=[],$headers=[])
  {
    $client= new Client([
      'base_uri'=>$this->baseUrl,
    ]);

    if(isset($this->secret)){
      $headers['Authorization']=$this->secret;
    }
    $response= $client->request($method,$requestUrl,['form_params'=>$formParams,'headers'=>$headers]);

    return $response->getBody()->getContents();
  }

/**
 * envia archivos al servicio
 */
  public function performRequestFiles($method,$requestUrl,$file,$id,$mes){
    $client= new Client([
      'base_uri'=>$this->baseUrl,
    ]);

    if(isset($this->secret)){
      $headers['Authorization']=$this->secret;
    }

    $response = $client->request($method, $requestUrl, [
        'multipart' => [
          [
              'name'     => 'mes',
              'contents' => $mes,
          ],
          [
                'name'     => 'id',
                'contents' => $id,
          ],
          [
                'name'     => 'file',
                'contents' => fopen($file, 'r')
          ],

        ],
        'headers'=>$headers
        ]);

        return $response->getBody()->getContents();
  }

  /**
   * envia archivos al servicio
   */
    public function performRequestFileBackup($method,$requestUrl,$file){
      $client= new Client([
        'base_uri'=>$this->baseUrl,
      ]);

      if(isset($this->secret)){
        $headers['Authorization']=$this->secret;
      }

      $response = $client->request($method, $requestUrl, [
          'multipart' => [
            [
                  'name'     => 'file',
                  'contents' => fopen($file, 'r')
            ],

          ],
          'headers'=>$headers
          ]);

          return $response->getBody()->getContents();
    }

}
