<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "title_filters_categories_block",
 *   admin_label = @Translation("Titulo Filtro Publicaciones")
 * )
 */
class TitleFiltersCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return array(
            '#theme' => 'title_categories_block_custom',
            '#titulo' => $this->t('Titulo Categorias'),
            '#title' => $_GET['categoria']
        );
    }
}
