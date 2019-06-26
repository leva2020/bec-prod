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
        $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(
            'publicaciones', // This is your taxonomy term vocabulary (machine name).
            0,                 // This is "tid" of parent. Set "0" to get all.
            1,                 // Get only 1st level.
            TRUE               // Get full load of taxonomy term entity.
        );

        $result = [];

        foreach ($tree as $term) {
            $result[] = $term->getTerm();
        }

        /*$vid = 'publicaciones';
        //$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->taxonomy_get_tree($vid);

        /*$tree = [];
        foreach ($terms as $tree_object) {
            $this->buildTree($tree, $tree_object, $vid);
        }*/

        /*foreach ($terms as $term) {
            $term_data[] = array(
                "id" => $term->tid,
                "name" => $term->name,
                "weight" => $term->children,
                "parent" => $term->parents,
            );
        }*/
        return array(
            '#theme' => 'categories_block_custom',
            '#titulo' => $this->t('Listado Categorias'),
            '#terms' => $result
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
