<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "filters_categories_block",
 *   admin_label = @Translation("Filtros Publicaciones")
 * )
 */
class CategoriesCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $vid = 'publicaciones';
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        foreach ($terms as $term) {
            $term_data[] = array(
                "id" => $term->tid,
                "name" => $term->name
            );
        }
        return array(
            '#theme' => 'categories_block_custom',
            '#titulo' => $this->t('Listado Categorias'),
            '#terms' => $term_data
        );
    }

}
