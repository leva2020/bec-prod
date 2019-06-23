<?php
namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "publications_block",
 *   admin_label = @Translation("Publicaciones")
 * )
 */
class PublicationsCustomBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        $query =    Drupal::entityQuery('node')
            ->condition('type', 'publicaciones')
            ->execute();

        if (!empty($query)) {
            foreach ($query as $empresaId) {
                $empresa = Node::load($empresaId);
                $empresas[] = $empresa;
            }
        }

        return array(
            '#theme' => 'blocks_custom',
            '#titulo' => $this->t('Listado de publicaciones'),
            '#publicaciones' => $empresas
        );
    }

}