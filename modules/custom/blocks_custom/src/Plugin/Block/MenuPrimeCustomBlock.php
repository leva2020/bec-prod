<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "menu_prime_block",
 *   admin_label = @Translation("Menu Prime")
 * )
 */
class MenuPrimeCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $user = \Drupal::currentUser()->getUsername();
        return array(
            '#theme' => 'filters_publications_block_custom',
            '#titulo' => $this->t('Menu Prime'),
            '#user' => $user
        );
    }

}
