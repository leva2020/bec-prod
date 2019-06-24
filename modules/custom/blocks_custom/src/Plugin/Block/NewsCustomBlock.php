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
            '#theme' => 'blocks_custom',
            '#titulo' => $this->t('Listado de noticias custom'),
            '#publicaciones' => $empresas
        );
    }

}
