<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "archivo_informe_prime_igas_block",
 *   admin_label = @Translation("Archivos de Informe Prime IGas")
 * )
 */
class ArchivoInformePrimeIGasCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'archivo_prime_igas')
            ->sort('field_apig_dia', 'DESC')
            ->execute();
        $archivos_informe = [];
        $estructura = [];

        if (!empty($query)) {
            foreach ($query as $archivo_id) {
                $archivo = Node::load($archivo_id);
                $archivos_informe[] = $archivo;
            }
        }
        foreach ($archivos_informe as $node) {
            $estructura[$node->get('field_apig_anio')->getValue()[0]["value"]]
                [$node->get('field_apig_mes')->getValue()[0]["value"]][] = array(
                    'titulo' => $node->getTitle(),
                    'archivo' => $node->get('field_apig_archivo')->entity->url()
                );
        }
        unset($archivos_informe);

        return array(
            '#theme' => 'archivo_informe_prime_igas_block_custom',
            '#titulo' => $this->t('Listado de archivos informe prime IGas'),
            '#archivos' => $estructura
        );
    }
}
