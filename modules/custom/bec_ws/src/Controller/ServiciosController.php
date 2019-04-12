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
      } catch (Exception $e) {
        $response = array('state' => 0,'response' =>$e->getMessage() );
      }
      return $response;
    }

    public function enviarPeticionPost($metodo,$param,$formatear,$fechaCompleta,$filtro,$campos,$cantidad){      
      $url=\Drupal::config('bec_ws.settings')->get('url_base').$metodo;
      $data=$this->sendPostRequest($url,$param);
      if ($formatear) {
        switch ($metodo) {
          case 'getCantidadEnergiaInyectadaOpe_R':
            $data=$this->energiaInyectada($data['response'],$fechaCompleta,$filtro,$campos,$cantidad);
            break;

          case 'getCantidadEnergiaSuministrar':
            $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
            break;

          case 'getCantidadAutorizadaNominaciones':
            $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
            break;
          
          default:
            # code...
            break;
        }
        
      }
      return $data;
    }

    public function energiaInyectada($data,$fechaCompleta,$filtro,$cantidad){
      $info=array();
      foreach ($data as $item) {          
        if ($fechaCompleta) {                  
          if (isset($info[$item->año][$item->mes][$item->dia][$item->$filtro])) {
            $info[$item->año][$item->mes][$item->dia][$item->$filtro]=$info[$item->año][$item->mes][$item->dia][$item->$filtro]+$item->$cantidad;
          }else{
            $info[$item->año][$item->mes][$item->dia][$item->$filtro]=$item->$cantidad;            
          }        
          ksort($info[$item->año]);
        }
      }
      $format=array();
      $labels=array();
      foreach ($info as $key => $value) {
        $año=$key;
          foreach ($value as $key2 => $value2) {
            $mes=$key2;
              foreach ($value2 as $key3 => $value3) {
                $dia=$key3;
                array_push($labels, $dia.'/'.$mes.'/'.$año);
                  foreach ($value3 as $key4 => $value4) {                     
                    if (!is_array($format[$key4])) {
                      $format[$key4]=array();
                    }                     
                     array_push($format[$key4],$value4 );
                  }
              }
          }
      }

        return array('labels'=>$labels,'data'=>$format);
    }

    public function energiaSuministrar($data,$fechaCompleta,$cantidad){
      $info=array();
      foreach ($data as $item) {          
        if ($fechaCompleta) {                  
          if (isset($info[$item->año][$item->mes][$item->dia])) {
            $info[$item->año][$item->mes][$item->dia]=$info[$item->año][$item->mes][$item->dia]+$item->$cantidad;
          }else{
            $info[$item->año][$item->mes][$item->dia]=$item->$cantidad;            
          }        
          ksort($info[$item->año]);
        }
      }
      $format=array();
      $labels=array();
      foreach ($info as $key => $value) {
        $año=$key;
          foreach ($value as $key2 => $value2) {
            $mes=$key2;
              foreach ($value2 as $key3 => $value3) {
                $dia=$key3;
                array_push($labels, $dia.'/'.$mes.'/'.$año);
                array_push($format, $value3);
              }
          }
      }

        return array('labels'=>$labels,'data'=>$format);
    }

}
