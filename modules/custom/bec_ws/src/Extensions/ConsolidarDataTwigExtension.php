<?php
namespace Drupal\bec_ws\Extensions;
use Drupal\bec_ws\Controller\ServiciosController;

/**
 * Class DefaultService.
 *
 * @package Drupal\bec_ws
 */
class ConsolidarDataTwigExtension extends \Twig_Extension {

    /**
    * {@inheritdoc}
    * This function must return the name of the extension. It must be unique.
    */
    public function getName() {
        return 'consolidar_data';
    }

    /**
    * In this function we can declare the extension function
    */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction(
                'consolidar_data',
                array($this, 'consolidar_data'),
                array('is_safe' => array('html'))
            )
        );
    }

    /**
    * The php function to load a given block
    */
    public function consolidar_data($infoNacional, $infoImportada) {

//         echo "<pre> NACIONAL";
//         var_dump($infoNacional["num_resultados"]);
//         echo "</pre>";

//         echo "<pre> IMPORTADA";
//         var_dump($infoImportada["num_resultados"]);
//         echo "</pre>";

        $info = $infoNacional;
        $info["num_resultados"] += $infoImportada["num_resultados"];

        foreach($infoImportada['dataDataSets']['Importada'] as $key => $item) {
            $info["dataDataSets"]["Importada"][$key] += $item;
        }

        $info["dataTables"] = array_merge($infoImportada["dataTables"], $info["dataTables"]);

        foreach($info["dataTables"] as $dataTable) {
            foreach($dataTable as $key => $item) {
                if ($key == "ano") {
                    $dataTable->año = $item;
                } elseif ($key == "año") {
                    $dataTable->ano = $item;
                } elseif ($key == "cantidad") {
                    $dataTable->cantidad_mbtud = $item[$key];
                } elseif ($key == "cantidad_mbtud") {
                    $dataTable->cantidad = $item;
                }

            }
        }

//         echo "<pre> INFO";
//         var_dump(count($info["response"]));
//         echo "</pre>";

        $data = array(
            'data' => $info["data"],
            'labels' => $info["labels"],
            'dataDataSets' => $info["dataDataSets"],
            'num_resultados' => $info["num_resultados"],
            'dataTables' => $info["dataTables"],
            'response' => $info["response"]
        );
        return $data;
    }

}
