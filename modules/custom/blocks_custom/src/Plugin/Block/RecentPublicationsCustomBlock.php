<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "recent_publications_block",
 *   admin_label = @Translation("Filtros Publicaciones")
 * )
 */
class RecentPublicationsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'publicaciones')
            ->sort('created', 'DESC')
            ->range(0, 4)
            ->execute();
        $publications = [];

        if (!empty($query)) {
            foreach ($query as $publicationId) {
                $publication = Node::load($publicationId);
                $publications[] = $publication;
            }
        }

        return array(
            '#theme' => 'recent_publications_block_custom',
            '#titulo' => $this->t('Filtros Publicaciones'),
            '#recents' => $publications
        );
    }

}
