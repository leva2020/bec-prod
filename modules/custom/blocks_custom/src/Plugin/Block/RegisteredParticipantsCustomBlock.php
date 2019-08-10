<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "registered_participants_block",
 *   admin_label = @Translation("Participantes Registrados")
 * )
 */
class RegisteredParticipantsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return array(
            '#theme' => 'registered_participants_block_custom',
            '#titulo' => $this->t('Participantes Registrados'),
            '#title' => ''
        );
    }
}
