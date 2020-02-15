<?php

namespace Drupal\bec_ws\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Element\Date;


class ServiciosController extends ControllerBase
{

    public function sendGetRequest($url)
    {
        try {
            $client = \Drupal::httpClient();
            $request = $client->get($url);
            $response = array('state' => 1, 'response' => json_decode($request->getBody()));
        } catch (Exception $e) {
            $response = array('state' => 0, 'response' => $e->getMessage());
        }

        return $response;
    }

    //Función para enviar peticiones POST a una url con parametros
    public function sendPostRequest($url, $params)
    {
        try {
            $client = \Drupal::httpClient();
            $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
            $request = $client->post($url, array(
                    'headers' => $headers,
                    'body' => $params)
            );

            $response = array(
                'state' => 1,
                'response' => json_decode($request->getBody())
            );
        } catch (Exception $e) {
            $response = array(
                'state' => 0,
                'response' => $e->getMessage()
            );
        }

        return $response;
    }

    public function enviarPeticionPost($metodo, $params, $formatear, $fechaCompleta, $filtro, $campos, $cantidad)
    {
        $url = \Drupal::config('bec_ws.settings')->get('url_base') . $metodo;

        $paramsFilter = null;
        if (!empty($_POST)):
            if (isset($_POST["paramsFilter"]) && $paramsFilter = $_POST["paramsFilter"]):
                $params = null;
                foreach ($paramsFilter as $key => $param):

                    if ($metodo == "getCapacidadDisponiblePrimaria" && ($key == "fechaInicial" || $key == "fechaFinal")) {
                        if ($key == "fechaInicial") {
                            $tmp = explode("-", $param);
                            $params .= "anoInicial=" . $tmp[0] . "&";
                            $params .= "mesInicial=" . $tmp[1] . "&";
                        } elseif ($key == "fechaFinal") {
                            $tmp = explode("-", $param);
                            $params .= "anoFinal=" . $tmp[0] . "&";
                            $params .= "mesFinal=" . $tmp[1] . "&";
                        }
                    } else {
                        $params .= $key . "=" . $param . "&";
                    }
                endforeach;
            elseif (isset($_POST["quickDate"]) && $paramsFilterQ = $_POST["quickDate"]):
                $fecha = date("Y-m-d");
                $currentYear = date('Y');
                $currentMonth = date('m');
                if ($paramsFilterQ == "1m"):
                    $fechaInicial = date("Y-m-d", strtotime("-1 months"));
                    $yearInicial = date("Y", strtotime("-1 months"));
                    $monthInicial = date("m", strtotime("-1 months"));
                elseif ($paramsFilterQ == "3m"):
                    $fechaInicial = date("Y-m-d", strtotime("-3 months"));
                    $yearInicial = date("Y", strtotime("-3 months"));
                    $monthInicial = date("m", strtotime("-3 months"));
                elseif ($paramsFilterQ == "6m"):
                    $fechaInicial = date("Y-m-d", strtotime("-6 months"));
                    $yearInicial = date("Y", strtotime("-6 months"));
                    $monthInicial = date("m", strtotime("-6 months"));
                elseif ($paramsFilterQ == "1a"):
                    $fechaInicial = date("Y-m-d", strtotime("-1 year"));
                    $yearInicial = date("Y", strtotime("-1 year"));
                    $monthInicial = date("m", strtotime("-1 year"));
                endif;

                $paramsFilter["fechaInicial"] = $fechaInicial;
                $paramsFilter["fechaFinal"] = $fecha;
                $params = "";
                if ($metodo == "getCapacidadDisponiblePrimaria"):
                    $paramsFilter["codigoOperador"] = "228";
                    $paramsFilter["codigopunto"] = "120";
                    $params .= "anoInicial=" . $yearInicial . "&";
                    $params .= "mesInicial=" . $monthInicial. "&";
                    $params .= "anoFinal=" . $currentYear . "&";
                    $params .= "mesFinal=" . $currentMonth. "&";
                    $params .= "codigoOperador=" . "228". "&";
                    $params .= "codigopunto=" . "120";
                elseif ($metodo == "getComportamientoOperativoMercado"):
                    $params .= "codigoReporte=1&";
                    $params .= "fechaInicial=" . $fechaInicial . "&";
                    $params .= "fechaFinal=" . $fecha;
                else:
                    $paramsFilter["tramoOGrupo"] = "";
                    $params .= "fechaInicial=" . $fechaInicial . "&";
                    $params .= "fechaFinal=" . $fecha;
                endif;
            endif;
        endif;
        $data = $this->sendPostRequest($url, $params);

        $info = $this->$metodo($data);

        $info['paramsFilter'] = $paramsFilter;

        return $info;
    }

    public function getCuadroMercadoResumenContratos($data)
    {
        return $data['response'];
    }

    public function energiaInyectada($data, $fechaCompleta, $filtro, $cantidad)
    {
        $info = array();
        foreach ($data as $item) {
            if ($fechaCompleta) {
                if (isset($info[$item->año][$item->mes][$item->dia][$item->$filtro])) {
                    $info[$item->año][$item->mes][$item->dia][$item->$filtro] = $info[$item->año][$item->mes][$item->dia][$item->$filtro] + $item->$cantidad;
                } else {
                    $info[$item->año][$item->mes][$item->dia][$item->$filtro] = $item->$cantidad;
                }
                ksort($info[$item->año]);
            }
        }
        $format = array();
        $labels = array();
        foreach ($info as $key => $value) {
            $año = $key;
            foreach ($value as $key2 => $value2) {
                $mes = $key2;
                foreach ($value2 as $key3 => $value3) {
                    $dia = $key3;
                    array_push($labels, $dia . '/' . $mes . '/' . $año);
                    foreach ($value3 as $key4 => $value4) {
                        if (!is_array($format[$key4])) {
                            $format[$key4] = array();
                        }
                        array_push($format[$key4], $value4);
                    }
                }
            }
        }

        return array('labels' => $labels, 'data' => $format);
    }

    public function energiaSuministrar($data, $fechaCompleta, $cantidad)
    {
        $info = array();
        foreach ($data as $item) {
            if ($fechaCompleta) {
                if (isset($info[$item->año][$item->mes][$item->dia])) {
                    $info[$item->año][$item->mes][$item->dia] = $info[$item->año][$item->mes][$item->dia] + $item->$cantidad;
                } else {
                    $info[$item->año][$item->mes][$item->dia] = $item->$cantidad;
                }
                ksort($info[$item->año]);
            }
        }
        $format = array();
        $labels = array();
        foreach ($info as $key => $value) {
            $año = $key;
            foreach ($value as $key2 => $value2) {
                $mes = $key2;
                foreach ($value2 as $key3 => $value3) {
                    $dia = $key3;
                    array_push($labels, $dia . '/' . $mes . '/' . $año);
                    array_push($format, $value3);
                }
            }
        }

        return array('labels' => $labels, 'data' => $format);
    }

    public function getCapacidadNegociadaPorTramosOGrupoDeGasoductos($data)
    {
        return $data;
    }

    public function getPrecioNegociadoPorTramo($data)
    {

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getCantidadContratadaPuntoEntrega($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;
                $date = date("Y/m/d", strtotime($date));
                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets[$value->desc_modalidad . "(MBTUD)"][$date] = $value->capac_cant;
                else:
                    $dataDataSets[$value->desc_modalidad . "(MBTUD)"][$date] += $value->capac_cant;
                endif;
            }
            foreach ($dataDataSets as $key => $infoXModalidad) {
                foreach ($infoXModalidad as $tmp) {
                    $dataSets[$key][] = $tmp;
                }
            }
            $info['desc_punto_tramo'] = $data["response"][0]->desc_punto_tramo;
            $info['desc_modalidad'] = $data["response"][0]->desc_modalidad;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getPuntoDeEntregaSuministro($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;
                $date = date("Y/m", strtotime($date));
                if (!in_array($date, $labels)):
                    $labels[] = $date;
                    $dataDataSets["capac_cant"][$date] = 0;
                endif;
                $dataDataSets['capac_cant'][$date] += $value->cantidad;

            }
            foreach ($dataDataSets["capac_cant"] as $tmp):
                $dataSets["capac_cant"][] = $tmp;
            endforeach;
            $info['desc_punto_tramo'] = $data["response"][0]->desc_punto_tramo;
            $info['desc_modalidad'] = $data["response"][0]->desc_modalidad;
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'num_resultados' => count($data['response']),
            'dataTables' => $data["response"],
            'response' => $data['response']
        );

        return $data;
    }

    public function getComportamientoOperativoMercado($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;
                $date = date("Y/m/d", strtotime($date));
                $labels[] = $date;
                $dataDataSets['cantidad'][] = $value->cantidad;
            }
        }

        $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataDataSets,
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );
        return $data;
    }

    public function getCantidadContratadaPorPuntoDeEntrega($data)
    {
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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getCapacidadContratadaTramoGrupoGasoductos($data)
    {
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
                $date = $this->formatMonthLabel($value->mes) . "/" . $value->año;
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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );
        //         var_dump($data['dataDataSets']);exit;
        //         var_dump($data['dataDataSets']['modalidad']);

        return $data;
    }

    public function getCapacidadMaximaMedianoPlazo($data)
    {

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function formatMonthLabel($month)
    {
        $months = [
            '1' => 'Ene',
            '01' => 'Ene',
            '2' => 'Feb',
            '02' => 'Feb',
            '3' => 'Mar',
            '03' => 'Mar',
            '4' => 'Abr',
            '04' => 'Abr',
            '5' => 'May',
            '05' => 'May',
            '6' => 'Jun',
            '06' => 'Jun',
            '7' => 'Jul',
            '07' => 'Jul',
            '8' => 'Ago',
            '08' => 'Ago',
            '9' => 'Sep',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dic',
        ];
        return $months[$month];
    }

    public function getCapacidadDisponiblePrimaria($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $key => $value) {
                $date = $this->formatMonthLabel($value->mes) . "/" . $value->año;

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );
    }

    public function getPrecioVentaUsuariosNoRegulados($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $key => $value):
                $date = $value->año . "/" . $this->formatMonthLabel($value->mes);

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
            foreach ($dataDataSets["Precio promedio"] as $tmp):
                $dataSets["Precio promedio"][] = $tmp;
            endforeach;
        }

        return $data = array(
            'info' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'dataTables' => $data["response"],
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );
    }

    public function getPTDVFYCIDVF($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $this->formatMonthLabel($value->mes) . "./" . $value->ano;

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getInfGrafIni($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value) {
                $date = $value->fecha;
                $date = date("Y/m/d", strtotime($date));

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getPuntoEntregaSuministro($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value):
                $date = $this->formatMonthLabel($value->mes) . "./" . $value->año;
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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getAgregadoNacionalSuministro($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            foreach ($data['response'] as $key => $value):
                $date = $this->formatMonthLabel($value->mes) . "./" . $value->año;

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
            'num_resultados' => count($data['response']),
            'response' => $data['response']
        );

        return $data;
    }

    public function getInformaciontransaccionalSUVCP_CEN_UVCP($data)
    {
        return $data["response"];
    }

    public function getInformaciontransaccionalSUVCP_CEN_UVCP_AN($data)
    {
        return $data["response"];
    }

    public function getCapacidadTransporteNegociadaUVCP($data)
    {
        return $data["response"];
    }

    public function getCapacidadTransporteNegociadaUVCP_AN($data)
    {
        return $data["response"];
    }

    public function getCantidadesAdjudicadasPuntoEntrega($data)
    {
        return $data["response"];
    }

    public function getCPublicacionOfertaSubastaBimestral($data)
    {
        return $data["response"];
    }

    public function getSUVLPCantidadesAdjudicadasPuntoEntrega($data)
    {
        return $data;
    }

    public function getSubastaSuministroConInterrupciones($data)
    {
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $date = $info->fecha;
                $key = date("Y/m/d", strtotime($date));
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                    //                     $dataGraficas[$key]["precio_prom"] += $info->precio_prom;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    //                     $dataGraficas[$key]["precio_prom"] = $info->precio_prom;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getInformacionOperativaGasoductosConexion($data)
    {

        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->fecha;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key]["kpcd"] += $info->equivalente_kpcd;
                    $dataGraficas[$key]["cmmp"] += $info->cantidad_cmmp;
                } else {
                    $dataGraficas[$key]["cmmp"] = $info->cantidad_cmmp;
                    $dataGraficas[$key]["kpcd"] = $info->equivalente_kpcd;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getCantidadEnergiaInyectadaOpe_R($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            if (isset($data['response'])):
                foreach ($data['response'] as $key => $value) {
                    $date = $value->dia . "/" . $this->formatMonthLabel($value->mes) . "/" . $value->año;
                    if (!in_array($date, $labels)):
                        $labels[] = $date;
                        $dataDataSets["Nacional"][$date] = 0;
                        $dataDataSets["Importada"][$date] = 0;
                    endif;
                    if ($value->tipo_produccion == "NACIONAL") {
                        $dataDataSets["Nacional"][$date] += $value->cantidad;
                    } else {
                        $dataDataSets["Importada"][$date] += $value->cantidad;
                    }
                }
            endif;
        }
        foreach ($dataDataSets["Nacional"] as $tmp):
            $dataSets["Nacional"][] = $tmp;
        endforeach;
        foreach ($dataDataSets["Importada"] as $tmp):
            $dataSets["Importada"][] = $tmp;
        endforeach;
        $data = array(
            'data' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'num_resultados' => count($data['response']),
            'dataTables' => $data['response'],
            'response' => $data["response"]
        );
        return $data;
    }

    public function getCantidadEnergiaInyectadaOpe_New($data)
    {
        $labels = array();
        $dataDataSets = array();
        $info = array();
        $dataSets = array();

        if (is_array($data['response'])) {
            if (isset($data['response'])):
                foreach ($data['response'] as $key => $value) {
                    $date = $value->dia . "/" . $this->formatMonthLabel($value->mes) . "/" . $value->ano;
                    if (!in_array($date, $labels)):
                        $labels[] = $date;
                        $dataDataSets["Nacional"][$date] = 0;
                        $dataDataSets["Importada"][$date] = 0;
                    endif;
                    if ($value->tipo_produccion == "NACIONAL") {
                        $dataDataSets["Nacional"][$date] += $value->cantidad_mbtud;
                    } else {
                        $dataDataSets["Importada"][$date] += $value->cantidad_mbtud;
                    }
                }
            endif;
        }
        foreach ($dataDataSets["Nacional"] as $tmp):
            $dataSets["Nacional"][] = $tmp;
        endforeach;
        foreach ($dataDataSets["Importada"] as $tmp):
            $dataSets["Importada"][] = $tmp;
        endforeach;
        $data = array(
            'data' => $info,
            'labels' => $labels,
            'dataDataSets' => $dataSets,
            'num_resultados' => count($data['response']),
            'dataTables' => $data['response'],
            'response' => $data["response"]
        );
        return $data;
    }

    public function getCantidadEnergiaSuministrar($data)
    {
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->año;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
//         echo count($data["dataTables"]);
//         var_dump($data);exit;
        return $data;
    }

    public function getCantidadDeclaradaPorNoComercializadoresNoInyectada($data)
    {
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->ano;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;

    }

    public function getCantidadAutorizadaNominaciones($data)
    {
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->año;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getCantidadTomadaTransportadoresTramo($data)
    {
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->año;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getCantidadEnergiaTomadaComercializadores($data)
    {
//         var_dump($data["response"]);exit;
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->año;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getCantidadTomadaDiariamenteSnt($data)
    {
//                 var_dump($data["response"]);exit;
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->ano;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad_mbtud;
                } else {
                    $dataGraficas[$key] = $info->cantidad_mbtud;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }

    public function getCantidadTomadaContratosParqueo($data)
    {
//                         var_dump($data["response"]);exit;
        $dataGraficas = array();
        $labels = array();

        if (is_array($data['response'])) {
            foreach ($data["response"] as $info) {
                $key = $info->dia . "/" . $this->formatMonthLabel($info->mes) . "/" . $info->año;
                if (array_key_exists($key, $dataGraficas)) {
                    $dataGraficas[$key] += $info->cantidad;
                } else {
                    $dataGraficas[$key] = $info->cantidad;
                    $labels[] = $key;
                }
            }
        }

        $data = array(
            'labels' => $labels,
            'dataTables' => $data['response'],
            'dataGraficas' => $dataGraficas,
            'num_resultados' => count($data['response']),
            'response' => $data["response"]
        );
        //         echo count($data["dataTables"]);
        //         var_dump($data);exit;
        return $data;
    }


}