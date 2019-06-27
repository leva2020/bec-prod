<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "reports_prime_block",
 *   admin_label = @Translation("Informes Prime")
 * )
 */
class ReportsPrimeCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'informe_prime')
            ->sort('created', 'DESC')
            ->execute();
        $reports = [];

        if (!empty($query)) {
            foreach ($query as $reportId) {
                $report = Node::load($reportId);
                $reports[] = $report;
            }
        }

        return array(
            '#theme' => 'reports_prime_block_custom',
            '#titulo' => $this->t('Listado Reportes'),
            '#reports' => $reports
        );
    }

}
