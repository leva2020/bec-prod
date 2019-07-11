<?php

namespace Drupal\blocks_custom\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "list_documents_block",
 *   admin_label = @Translation("Lista Documentos")
 * )
 */
class ListDocumentsCustomBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $query = Drupal::entityQuery('node')
            ->condition('type', 'documentos')
            ->sort('created', 'DESC')
            ->range(0, 4)
            ->execute();
        $publications = [];
        $nid = 0;
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface) {
            $nid = $node->id();
        }

        if (!empty($query)) {
            foreach ($query as $publicationId) {
                $publication = Node::load($publicationId);
                $publications[] = $publication;
            }
        }

        return array(
            '#theme' => 'list_documents_block_custom',
            '#titulo' => $this->t('Filtros Publicaciones'),
            '#recents' => $publications,
            '#nid' => $nid
        );
    }

}
