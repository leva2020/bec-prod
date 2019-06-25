<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "news_block",
 *   admin_label = @Translation("Noticias")
 * )
 */
class NewsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'noticias')
            ->sort('created', 'DESC')
            ->range(0, 4)
            ->execute();
        $news = [];

        if (!empty($query)) {
            foreach ($query as $newId) {
                $new = Node::load($newId);
                $news[] = $new;
            }
        }

        return array(
            '#theme' => 'news_block_custom',
            '#titulo' => $this->t('Listado de noticias custom'),
            '#news' => $news
        );
    }

}
