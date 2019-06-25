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
            '#theme' => 'categories_block_custom',
            '#titulo' => $this->t('Filtros Publicaciones'),
            '#news' => $empresas
        );
    }

}
