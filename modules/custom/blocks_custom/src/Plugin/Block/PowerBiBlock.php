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
class PowerBiBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'power_bi')
            ->sort('created', 'DESC')
            ->range(0, 10)
            ->execute();
        $power = [];

        if (!empty($query)) {
            foreach ($query as $newId) {
                $new = Node::load($newId);
                $power[] = $new;
            }
        }

        return array(
            '#theme' => 'power_bi_block_custom',
            '#titulo' => $this->t('Tablas Power BI'),
            '#power' => $power
        );
    }

}
