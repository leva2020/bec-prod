<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "paginator_reports_prime_block",
 *   admin_label = @Translation("Paginator Informes Prime")
 * )
 */
class PaginatorReportPrimeCustomBlock extends BlockBase
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

        $nid = 0;
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface) {
            $nid = $node->id();
        }

        if (!empty($query)) {
            foreach ($query as $reportId) {
                $report = Node::load($reportId);
                if ($reportId != $nid) {
                    $reports[] = $report;
                }
            }
        }

        return array(
            '#theme' => 'paginator_reports_prime_block_custom',
            '#titulo' => $this->t('Paginator Listado Reportes'),
            '#reports' => $reports
        );
    }

}
