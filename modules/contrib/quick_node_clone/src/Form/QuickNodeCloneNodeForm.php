<?php

namespace Drupal\quick_node_clone\Form;

use Drupal\node\NodeForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Quick Node Clone edit forms. We can override most of
 * the node form from here! Hooray!
 */
class QuickNodeCloneNodeForm extends NodeForm {

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $element = parent::actions($form, $form_state);

    // Brand the Publish / Unpublish buttons but first check if they are still there.
    $clone_string = t('New Clone: ');
    if (!empty($element['publish']['#value'])) {
      $element['publish']['#value'] = $clone_string . $element['publish']['#value'];
    }
    if (!empty($element['unpublish']['#value'])) {
      $element['unpublish']['#value'] = $clone_string . $element['unpublish']['#value'];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $node = $this->entity;
    $insert = $node->isNew();
    $node->save();
    $node_link = $node->link($this->t('View'));
    $context = array('@type' => $node->getType(), '%title' => $node->label(), 'link' => $node_link);
    $t_args = array('@type' => node_get_type_label($node), '%title' => $node->label());

    if ($insert) {
      $this->logger('content')->notice('@type: added %title (clone).', $context);
      drupal_set_message(t('@type %title (clone) has been created.', $t_args));
    }

    if ($node->id()) {
      $form_state->setValue('nid', $node->id());
      $form_state->set('nid', $node->id());
      if ($node->access('view')) {
        $form_state->setRedirect(
          'entity.node.canonical',
          array('node' => $node->id())
        );
      }
      else {
        $form_state->setRedirect('<front>');
      }

    }
    else {
      // In the unlikely case something went wrong on save, the node will be
      // rebuilt and node form redisplayed the same way as in preview.
      drupal_set_message(t('The cloned post could not be saved.'), 'error');
      $form_state->setRebuild();
    }
  }

}
