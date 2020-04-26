<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "archivo_informe_diario_block",
 *   admin_label = @Translation("Archivos de Informe Diario")
 * )
 */
class ArchivoInformeDiarioCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $archivos_informe = [];
        $estructura = [];
        $anio = "";
        $mes = "";
        $conversion_meses = array(
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'Diciembre',
        );

        $query = Drupal::entityQuery('node')
            ->condition('type', 'archivo_informe_diario')
            ->sort('field_apig_mes', 'DESC')
            ->sort('field_apig_dia', 'DESC')
            ->execute();

        if (!empty($query)) {
            foreach ($query as $archivo_id) {
                $archivo = Node::load($archivo_id);
                $archivos_informe[] = $archivo;
            }
        }
        foreach ($archivos_informe as $node) {
            $anio = $node->get('field_apig_anio')->getValue()[0]["value"];
            $mes = $node->get('field_apig_mes')->getValue()[0]["value"];
            $mes = (array_key_exists($mes, $conversion_meses) ? $conversion_meses[$mes] : 'desconocido');

            $estructura[$anio][$mes][] = array(
                    'titulo' => $node->getTitle(),
                    'archivo' => $node->get('field_apig_archivo')->entity->url(),
                    'day' => $node->get('field_apig_dia')->getValue()[0]["value"]
                );
        }
        unset($archivos_informe);

        return array(
            '#theme' => 'archivo_informe_diario_block_custom',
            '#titulo' => $this->t('Listado de archivos informes Diarios'),
            '#archivos' => $estructura
        );
    }
}
