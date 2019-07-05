<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "interest_block",
 *   admin_label = @Translation("Interes")
 * )
 */
class InterestCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {

        $query = Drupal::entityQuery('node')
            ->condition('type', 'bloque_interes')
            ->sort('created', 'DESC')
            ->range(0, 4)
            ->execute();
        $interests = [];

        if (!empty($query)) {
            foreach ($query as $interestId) {
                $interest = Node::load($interestId);
                $interests[] = $interest;
            }
        }

        return array(
            '#theme' => 'interest_blocks_custom',
            '#titulo' => $this->t('Bloque interese custom'),
            '#interests' => $interests
        );
    }

}
