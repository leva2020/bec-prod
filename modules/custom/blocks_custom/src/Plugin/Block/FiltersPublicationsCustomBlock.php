<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "filters_publications_block",
 *   admin_label = @Translation("Filtros Publicaciones")
 * )
 */
class FiltersPublicationsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $year = '';
        $month = '';
        if($_GET['created']) {
            $year = $_GET['created']['min'];
            $year = explode('-', $year)[0];
            $month = explode('-', $year)[1];
        }

        return array(
            '#theme' => 'filters_publications_block_custom',
            '#titulo' => $this->t('Filtros Publicaciones'),
            '#year' => $year,
            '#month' => $month
        );
    }

}
