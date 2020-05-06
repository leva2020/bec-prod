<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "reports_bi_block",
 *   admin_label = @Translation("reports BI")
 * )
 */
class reportsBiBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'reports_bi')
            ->sort('created', 'DESC')
            ->range(0, 10)
            ->execute();
        $reports = [];

        if (!empty($query)) {
            foreach ($query as $newId) {
                $new = Node::load($newId);
                $reports[] = $new;
            }
        }

        return array(
            '#theme' => 'reports_bi_block_custom',
            '#titulo' => $this->t('Tablas reports BI'),
            '#reports' => $reports
        );
    }

}
