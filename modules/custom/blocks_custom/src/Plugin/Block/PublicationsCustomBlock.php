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
class PublicationsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {

        $entity_subqueue = \Drupal::entityManager()->getStorage('entity_subqueue')->load(1);
        $items = $entity_subqueue->get('items')->getValue();

        if (!empty($items)) {
            foreach ($items as $empresaId) {
                $empresa = Node::load($empresaId);
                $empresas[] = $empresa;
            }
        }

        /*$query = Drupal::entityQuery('node')
            ->condition('type', 'publicaciones')
            ->sort('created', 'DESC')
            ->range(0, 4)
            ->execute();
        $empresas = [];

        if (!empty($query)) {
            foreach ($query as $empresaId) {
                $empresa = Node::load($empresaId);
                $empresas[] = $empresa;
            }
        }*/

        return array(
            '#theme' => 'blocks_custom',
            '#titulo' => $this->t('Listado de publicaciones custom'),
            '#publicaciones' => $empresas
        );
    }

}
