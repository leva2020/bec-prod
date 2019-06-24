<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "interests_block",
 *   admin_label = @Translation("Interesas")
 * )
 */
class InterestsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'publicaciones')
            ->execute();
        $empresas = [];

        if (!empty($query)) {
            foreach ($query as $empresaId) {
                $empresa = Node::load($empresaId);
                $empresas[] = $empresa;
            }
        }

        return array(
            '#theme' => 'interests_block_custom',
            '#titulo' => $this->t('Listado de intereses custom'),
            '#news' => $empresas
        );
    }

}
