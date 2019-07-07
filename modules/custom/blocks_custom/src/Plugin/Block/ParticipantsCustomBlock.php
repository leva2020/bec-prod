<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "participants_block",
 *   admin_label = @Translation("Participantes")
 * )
 */
class ParticipantsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'detalle_participantes')
            ->sort('created', 'DESC')
            ->execute();
        $participants = [];

        if (!empty($query)) {
            foreach ($query as $participantId) {
                $participant = Node::load($participantId);
                $participants[] = $participant;
            }
        }

        return array(
            '#theme' => 'participant_blocks_custom',
            '#titulo' => $this->t('Listado de participantes custom'),
            '#participants' => $participants
        );
    }

}
