<?php

namespace Drupal\bec_ws\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;


class ServiciosController extends ControllerBase
{
    
    public function sendGetRequest($url){
      try {
        $client = \Drupal::httpClient();
        $request = $client->get($url);
        $response = array('state' => 1,'response' =>json_decode($request->getBody()) );
      } catch (Exception $e) {
        $response = array('state' => 0,'response' =>$e->getMessage() );
      }

        return $response;
    }
    //Función para enviar peticiones POST a una url con parametros
    public function sendPostRequest($url,$param){
      try {
        $client = \Drupal::httpClient();
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $request = $client->post($url, array('headers' => $headers, 'body' => $param));        
        $response = array('state' => 1,'response' =>json_decode($request->getBody()) );
        print "<pre>";
        var_dump($response);
        die("prueba back");
      } catch (Exception $e) {
        $response = array('state' => 0,'response' =>$e->getMessage() );
      }
      return $response;
    }

    public function enviarPeticionPost($metodo,$param){      
      $url=\Drupal::config('bec_ws.settings')->get('url_base').$metodo;
      return new JsonResponse($this->sendPostRequest($url,$param) );
    }

}
