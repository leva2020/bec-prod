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
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, 0, 1, true);

        /*$tree = [];
        foreach ($terms as $tree_object) {
            $this->buildTree($tree, $tree_object, $vid);
        }*/

        foreach ($terms as $term) {
            $term_data[] = array(
                "id" => $term->tid,
                "name" => $term->name,
            );
        }
        return array(
            '#theme' => 'categories_block_custom',
            '#titulo' => $this->t('Listado Categorias'),
            '#terms' => $term_data
        );
    }

    protected function buildTree(&$tree, $object, $vocabulary) {
        if ($object->depth != 0) {
            return;
        }
        $tree[$object->tid] = $object;
        $tree[$object->tid]->children = [];
        $object_children = &$tree[$object->tid]->children;

        $children = $this->entityTypeManager->getStorage('taxonomy_term')->loadChildren($object->tid);
        if (!$children) {
            return;
        }

        $child_tree_objects = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary, $object->tid);

        foreach ($children as $child) {
            foreach ($child_tree_objects as $child_tree_object) {
                if ($child_tree_object->tid == $child->id()) {
                    $this->buildTree($object_children, $child_tree_object, $vocabulary);
                }
            }
        }
    }

}
