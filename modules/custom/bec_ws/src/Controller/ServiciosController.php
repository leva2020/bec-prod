<?php

namespace Drupal\bec_ws\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Element\Date;


class ServiciosController extends ControllerBase {

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
    public function sendPostRequest($url, $params) {
        try {
            $client = \Drupal::httpClient();
            $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
            $request = $client->post($url, array(
                'headers' => $headers,
                'body' => $params)
            );

            $response = array(
                'state' => 1,
                'response' =>json_decode($request->getBody())
            );
        } catch (Exception $e) {
            $response = array(
                'state' => 0,
                'response' =>$e->getMessage()
            );
        }

        return $response;
    }

    public function enviarPeticionPost($metodo, $params, $formatear, $fechaCompleta, $filtro, $campos, $cantidad) {
        $url = \Drupal::config('bec_ws.settings')->get('url_base') . $metodo;

        $paramsFilter = null;
        if ( !empty($_POST) ):
            if ($paramsFilter = $_POST["paramsFilter"]):
                $params = null;
                foreach ($paramsFilter as $key => $param):
                    $params .= $key . "=" . $param . "&";
                endforeach;
            endif;
        endif;

        if ( !empty($_GET) ):
            if ($filterDate = $_GET["quickDate"]):
                $parametros = array();
                $params = explode("&", $params);

                foreach ($params as $param):
                    $tmp = explode("=", $param);
                    $parametros[$tmp[0]] = $tmp[1];
                endforeach;
                $fechaFinal = date_create($parametros["fechaFinal"]);

                if ($filterDate == "1m"):
                    $fechaInicial = date_format(date_modify($fechaFinal, "-1 month"), "Y-m-d");
                elseif ($filterDate == "3m"):
                    $fechaInicial = date_format(date_modify($fechaFinal, "-3 month"), "Y-m-d");
                elseif ($filterDate == "6m"):
                    $fechaInicial = date_format(date_modify($fechaFinal, "-6 month"), "Y-m-d");
                elseif ($filterDate == "1a"):
                    $fechaInicial = date_format(date_modify($fechaFinal, "-1 year"), "Y-m-d");
                endif;

                $parametros["fechaInicial"] = $fechaInicial;

                $params= "";
                foreach ($parametros as $key => $param):
                    $params .= $key . "=" . $param . "&";
                endforeach;

                $paramsFilter["fechaInicial"] = $fechaInicial;
                $paramsFilter["fechaFinal"] = $parametros["fechaFinal"];
            endif;
        endif;

        $data = $this->sendPostRequest($url, $params);

        $info = $this->$metodo($data);

        $info['paramsFilter'] = $paramsFilter;

        /* if ($formatear) {
         switch ($metodo) {
         case 'getCantidadEnergiaInyectadaOpe_R':
         # PARAMS: fechaInicial=2018-12-01&fechaFinal=2019-01-31&codigoPozo=0
         $data=$this->energiaInyectada($data['response'],$fechaCompleta,$filtro,$campos,$cantidad);
         break;

         case 'getCantidadEnergiaSuministrar':
         # PARAMS: fechaInicial=2018-12-01&fechaFinal=2019-01-31&codigoPozo=0
         $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCantidadAutorizadaNominaciones':
         # PARAMS: fechaInicial=2018-06-01&fechaFinal=2019-01-01&codigoOperador=0
         $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCantidadTomadaTransportadoresTramo':
         # PARAMS: fechaInicial%0A=2018-01-01&fechaFinal=2019-01-01&codigoPozo=0
         $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCantidadTomadaComercializadores':
         # PARAMS: fechaInicial=2018-06-01&fechaFinal=2019-03-31&codigoPozo=0
         $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCantidadTomadaDiariamenteSnt':
         # PARAMS: fechaInicial=2018-06-01&fechaFinal=2019-01-01&codigoTipoDemanda=0
         $data=$this->energiaSuministrar($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCantidadContratadaPorPuntoDeEntrega':
         # PARAMS: puntoTramo=39&pCodigoModalidad=1
         $data=$this->getCantidadContratadaPorPuntoDeEntrega($data['response'],$fechaCompleta,$cantidad);
         break;

         case 'getCapacidadContratadaTramoGrupoGasoductos':
         # PARAMS: fechaInicial=2018-04-01 &fechaFinal=2019-03-31&tramoOGrupo=
         $data=$this->getCapacidadContratadaTramoGrupoGasoductos($data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getInfGrafIni':
         # PARAMS: puntoTramo=116&pCodigoModalidad=1
         $data = $this->getInfGrafIni( $data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getCapacidadMaximaMedianoPlazo':
         # PARAMS: codigoOperador=228
         $data = $this->getCagetCapacidadMaximaMedianoPlazo( $data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getCapacidadDisponiblePrimaria':
         # PARAMS: anoInicial=2018&mesInicial=12&anoFinal=2019&mesFinal=02&codigoOperador=228&codigopunto=120
         $data = $this->getCapacidadDisponiblePrimaria( $data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getCAntidadContratadaPuntoEntrega':
         # PARAMS: puntoTramo=39&pCodigoModalidad=1
         $data = $this->getCAntidadContratadaPuntoEntrega( $data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getPuntoEntregaSuministro':
         # PARAMS: fechaInicial=2018-12-01&fechaFinal=2019-02-28&codigoPunto=1&codigoModalidad=21
         $data = $this->getPuntoEntregaSuministro( $data['response'], $fechaCompleta, $cantidad);
         break;

         case 'getAgregadoNacionalSuministro':
         # PARAMS: puntoTramo=116&pCodigoModalidad=1
         break;

         case 'getPrecioNegociadoPorTramo':
         # PARAMS: puntoTramo=116&pCodigoModalidad=1
         # $data = $this->getPrecioNegociadoPorTramo( $data['response']);
         $data = $data['response'];
         break;

         case 'getCapacidadNegociadaPorTramosOGrupoDeGasoductos':
         # PARAMS: fechaInicial=2018-12-01&fechaFinal=2019-02-28&codigoPunto=1064&codigoModalidad=1
         $data = $data['response'];
         # var_dump($this->$metodo($data));
         break;

         case 'getPuntoDeEntregaSuministro':
         # code...
         break;

         case 'getPrecioVentaUsuariosNoRegulados':
         # code...
         break;

         case 'getCantidadEnergiaInyectadaOpe_New':
         # code...
         break;

         case 'getCantidadDeclaradaPorNoComercializadoresNoInyectada':
         # code...
         break;

         case 'getCantidadTomadaContratosParqueo':
         # code...
         break;

         case 'getComportamientoTransaccionalMercado':
         # code...
         break;

         case 'getComportamientoOperativoMercado':
         # code...
         break;

         case 'getCuadroMErcadoComportamientoTansaccionalMercadoHomepage':
         # code...
         break;

         default:
         # code...
         break;
         }
         }*/
        return $info;
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

    public function getCapacidadNegociadaPorTramosOGrupoDeGasoductos($data) {
        return $data;
    }

    public function getPrecioNegociadoPorTramo($data) {

        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;

                $date = date("Y/m/d", strtotime($date));
                $labels[] = $date;

                $dataDataSets['precio_minimo'][] = round($value->precio_minimo, 2);
                $dataDataSets['precio_promedio'][] = round($value->precio_promedio, 2);
                $dataDataSets['precio_maximo'][] = round($value->precio_maximo, 2);
            }

            $info['desc_punto_tramo'] = $data["response"][0]->desc_punto_tramo;
            $info['desc_modalidad'] = $data["response"][0]->desc_modalidad;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'response' => $data['response']
        );

        return $data;
    }

    public function getCantidadContratadaPuntoEntrega($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;

                $date = date("Y/m/d", strtotime($date));
                $labels[] = $date;

                $dataDataSets['capac_cant'][] = $value->capac_cant;

            }

            $info['desc_punto_tramo'] = $data["response"][0]->desc_punto_tramo;
            $info['desc_modalidad'] = $data["response"][0]->desc_modalidad;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'response' => $data['response']
        );

        return $data;
    }

    public function getCantidadContratadaPorPuntoDeEntrega($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;

                $date = date("Y/m/d", strtotime($date));
                $labels[] = $date;

                $dataDataSets['precio_minimo'][] = $value->precio_minimo;
                $dataDataSets['precio_promedio'][] = $value->precio_promedio;
                $dataDataSets['precio_maximo'][] = $value->precio_maximo;

            }

            $info['desc_punto_tramo'] = $data["response"][0]->desc_punto_tramo;
            $info['desc_modalidad'] = $data["response"][0]->desc_modalidad;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'response' => $data['response']
        );

        return $data;
    }

    public function getCapacidadContratadaTramoGrupoGasoductos($data) {
        $labels = array();
        $modalidades = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        //         $tmpModalidades = array();

        sort($data["response"]);

        //         var_dump($data);

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->mes . "/" . $value->año;
                //             $date = date("m/Y", strtotime($date));
                $modalidad = $value->modalidad;
                $cantidad = $value->cantidad;


                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets[$date]['modalidad'] = array();
                endif;

                if (!in_array($modalidad, $modalidades)):
                    $modalidades[] = $modalidad;
                endif;

                if (array_key_exists($modalidad, $dataDataSets[$date]['modalidad'])):
                    $dataDataSets[$date]['modalidad'][$modalidad] += $cantidad;
                else:
                    $dataDataSets[$date]['modalidad'][$modalidad] = $cantidad;
                endif;

                //             if (array_key_exists($value->modalidad, $dataDataSets['modalidad'])):
                //                 //                 if (in_array($value->modalidad, $dataDataSets['modalidad'][$key])):
                //                 $dataDataSets['modalidad'][$value->modalidad][] += $value->cantidad;
                //             else:
                //                 $dataDataSets['modalidad'] += array($value->modalidad => $value->cantidad);
                //             endif;
            }
            // var_dump($modalidades);exit;
            $tmpLabels = $labels;
            foreach ($dataDataSets as $key => $tmpLabels):
                foreach ($modalidades as $modalidad):
                    if (!array_key_exists($modalidad, $tmpLabels['modalidad'])):
                        $dataDataSets[$key]['modalidad'][$modalidad] = 0;
                    endif;
                    $dataSets[$modalidad][] = $dataDataSets[$key]['modalidad'][$modalidad];
                endforeach;
            endforeach;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );
        //         var_dump($data['dataDataSets']);exit;
        //         var_dump($data['dataDataSets']['modalidad']);

        return $data;
    }

    public function getCapacidadMaximaMedianoPlazo($data) {

        $labels = array();
        $dataDataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $labels[] = "";

                $dataDataSets[$value->trasportador][] = $value->capacidad_maxima;
            }
        }

        $data = array(
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );

        return $data;
    }

    public function getCapacidadDisponiblePrimaria($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $key => $value) {
                $date = $value->mes . "/" . $value->año;

                $labels[] = $date;

                $dataDataSets[] = $value->capacidad;
            }

            $info = array(
                'transportador' => $data['response'][0]->trasportador,
                'tramo' => $data['response'][0]->tramo
            );
        }

        return $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );
    }

    public function getPrecioVentaUsuariosNoRegulados($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $key => $value):
                $date = $value->año . "/" . $value->mes;

                if (!in_array($date, $labels)):
                    $labels[] = $date;

                    $dataDataSets["Precio mínimo"][$date] = $value->precio_min;
                    $dataDataSets["Precio máximo"][$date] = $value->precio_max;
                    $dataDataSets["Precio promedio"][$date] = $value->precio_prom;
                else:

                    if ($value->precio_min != "N.D."):
                        $dataDataSets["Precio mínimo"][$date] += $value->precio_min;
                    endif;
                    if ($value->precio_min != "N.D."):
                        $dataDataSets["Precio máximo"][$date] += $value->precio_max;
                    endif;
                    if ($value->precio_min != "N.D."):
                        $dataDataSets["Precio promedio"][$date] += $value->precio_prom;
                    endif;
                endif;
            endforeach;

            foreach ($dataDataSets["Precio mínimo"] as $tmp):
                $dataSets["Precio mínimo"][] = $tmp;
            endforeach;
            foreach ($dataDataSets["Precio máximo"] as $tmp):
                $dataSets["Precio máximo"][] = $tmp;
            endforeach;
            foreach ($dataDataSets["Precio máximo"] as $tmp):
                $dataSets["Precio promedio"][] = $tmp;
            endforeach;
        }

        return $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );
    }

    public function getPTDVFYCIDVF($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->mes . "./" . $value->ano;

                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets["PTDVF (MBTUD)"][$date] = 0;
                    $dataDataSets["CIDVF (MBTUD)"][$date] = 0;
                endif;

                $dataDataSets["PTDVF (MBTUD)"][$date] += $value->ptdvf;
                $dataDataSets["CIDVF (MBTUD)"][$date] += $value->cidvf;
            }

            foreach ($dataDataSets["PTDVF (MBTUD)"] as $tmp):
                $dataSets["PTDVF (MBTUD)"][] = $tmp;
            endforeach;
            foreach ($dataDataSets["CIDVF (MBTUD)"] as $tmp):
                $dataSets["CIDVF (MBTUD)"][] = $tmp;
            endforeach;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data['response'],
            'response' => $data['response']
        );

        return $data;
    }

    public function getInfGrafIni($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;

                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets[$value->desc_modalidad][$key] = $value->capac_cant;
                else:
                $dataDataSets[$value->desc_modalidad][$key] += $value->capac_cant;
                endif;
            }
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'response' => $data['response']
        );

        return $data;
    }

    public function getPuntoEntregaSuministro($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value):
                $date = $value->mes . "./" . $value->año;

                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets["Cantidad (MBTUD)"][$date] = $value->cantidad;
                else:
                    $dataDataSets["Cantidad (MBTUD)"][$date] += $value->cantidad;
                endif;
            endforeach;

            foreach ($dataDataSets["Cantidad (MBTUD)"] as $tmp):
                $dataSets["Cantidad (MBTUD)"][] = $tmp;
            endforeach;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );

        return $data;
    }

    public function getAgregadoNacionalSuministro($data) {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value):
                $date = $value->mes . "./" . $value->año;

                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets["Cantidad (MBTUD)"][$date] = $value->cantidad;
                    $dataDataSets["Precio promedio"][$date] = $value->precio_prom;
                else:
                    $dataDataSets["Cantidad (MBTUD)"][$date] += $value->cantidad;
                    $dataDataSets["Precio promedio"][$date] += $value->precio_prom;
                endif;
            endforeach;

            foreach ($dataDataSets["Cantidad (MBTUD)"] as $tmp):
                $dataSets["Cantidad (MBTUD)"][] = $tmp;
            endforeach;

            foreach ($dataDataSets["Precio promedio"] as $tmp):
                $dataSets["Precio promedio"][] = $tmp;
            endforeach;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data["response"],
            'response' => $data['response']
        );

        return $data;
    }

    public function getInformaciontransaccionalSUVCP_CEN_UVCP($data) {
        return $data["response"];
    }

    public function getInformaciontransaccionalSUVCP_CEN_UVCP_AN($data) {
        return $data["response"];
    }

    public function getCapacidadTransporteNegociadaUVCP($data) {
        return $data["response"];
    }

    public function getCapacidadTransporteNegociadaUVCP_AN($data) {
        return $data["response"];
    }

    public function getCantidadesAdjudicadasPuntoEntrega($data) {
        return $data["response"];
    }

    public function getCPublicacionOfertaSubastaBimestral($data) {
        return $data["response"];
    }

    public function getSUVLPCantidadesAdjudicadasPuntoEntrega($data){
        return $data;
    }

    public function getSubastaSuministroConInterrupciones($data) {
        return $data["response"];
    }

    public function getInformacionOperativaGasoductosConexion($data)
    {
        return $data["response"];
    }

    public function getCantidadEnergiaInyectadaOpe_R($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();
        if (isset($data['response'])):
            foreach ($data['response'] as $key => $value) {
                $date = $value->mes . "./" . $value->ano;
                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets["PTDVF (MBTUD)"][$date] = 0;
                    $dataDataSets["CIDVF (MBTUD)"][$date] = 0;
                endif;
                $dataDataSets["PTDVF (MBTUD)"][$date] += $value->ptdvf;
                $dataDataSets["CIDVF (MBTUD)"][$date] += $value->cidvf;
            }
        endif;
        foreach ($dataDataSets["PTDVF (MBTUD)"] as $tmp):
            $dataSets["PTDVF (MBTUD)"][] = $tmp;
        endforeach;
        foreach ($dataDataSets["CIDVF (MBTUD)"] as $tmp):
            $dataSets["CIDVF (MBTUD)"][] = $tmp;
        endforeach;
        $data = array(
            'data' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data['response']
        );
        return $data;
    }

    public function getCantidadEnergiaSuministrar($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadDeclaradaPorNoComercializadoresNoInyectada($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadAutorizadaNominaciones($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadTomadaTransportadoresTramo($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadTomadaComercializadores($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadTomadaDiariamenteSnt($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }
    public function getCantidadTomadaContratosParqueo($data)
    {
        $data = array(
            'dataTables' => $data['response']
        );
        return $data;
    }


}