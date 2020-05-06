<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "power_bi_block",
 *   admin_label = @Translation("Power BI")
 * )
 */
class PowerBiCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface) {
            // You can get nid and anything else you need from the node object.
            $nid = $node->id();
        }
        $query = Drupal::entityQuery('node')
            ->condition('type', 'power_bi')
            ->sort('created', 'ASC')
            ->range(0, 10)
            ->execute();
        $reports = [];

        if (!empty($query)) {
            foreach ($query as $newId) {
                if ($nid != $newId) {
                    $new = Node::load($newId);
                    $reports[] = $new;
                }
            }
        }

        return array(
            '#theme' => 'power_bi_block_custom',
            '#titulo' => $this->t('Tablas reports BI'),
            '#reports' => $reports
        );
    }

}
