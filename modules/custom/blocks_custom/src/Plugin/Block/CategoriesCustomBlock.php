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

        for ($i = 0; $i < count($terms); $i++) {
            $weight = $terms[$i];
            if($weight < $terms[$i + 1]->weight) {
                $child = [
                    "id" => $terms[$i + 1]->tid,
                    "name" => $terms[$i + 1]->name,
                    "weight" => $terms[$i + 1]->weight
                ];
            }
            $term_data[$terms[$i]->tid] = [
                "id" => $terms[$i]->tid,
                "name" => $terms[$i]->name,
                "weight" => $terms[$i]->weight,
                "childs" => [$child]
            ];
        }
        /*foreach ($terms as $term) {
            $auxAnt = term->weight;

            $term_data[$term->tid] = array(
                "id" => $term->tid,
                "name" => $term->name,
                "weight" => $term->weight,
            );
        }*/
        return array(
            '#theme' => 'categories_block_custom',
            '#titulo' => $this->t('Listado Categorias'),
            '#terms' => $term_data
        );
    }
}
