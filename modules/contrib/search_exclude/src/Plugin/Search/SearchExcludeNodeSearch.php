<?php

namespace Drupal\search_exclude\Plugin\Search;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\node\Plugin\Search\NodeSearch;

/**
 * Search plugin to exclude node bundles from the Search module index.
 *
 * @SearchPlugin(
 *   id = "search_exclude_node_search",
 *   title = @Translation("Content (Exclude)")
 * )
 */
class SearchExcludeNodeSearch extends NodeSearch {

  /**
   * {@inheritdoc}
   */
  public function updateIndex() {
    // Interpret the cron limit setting as the maximum number of nodes to index
    // per cron run.
    $limit = (int) $this->searchSettings->get('index.cron_limit');

    $query = db_select('node', 'n', array('target' => 'replica'));
    $query->addField('n', 'nid');
    $query->leftJoin('search_dataset', 'sd', 'sd.sid = n.nid AND sd.type = :type', array(':type' => $this->getPluginId()));
    $query->addExpression('CASE MAX(sd.reindex) WHEN NULL THEN 0 ELSE 1 END', 'ex');
    $query->addExpression('MAX(sd.reindex)', 'ex2');
    $query->condition('n.type', $this->configuration['excluded_bundles'], 'NOT IN');
    $query->condition(
        $query->orConditionGroup()
        ->where('sd.sid IS NULL')
        ->condition('sd.reindex', 0, '<>')
      );
    $query->orderBy('ex', 'DESC')
      ->orderBy('ex2')
      ->orderBy('n.nid')
      ->groupBy('n.nid')
      ->range(0, $limit);

    $nids = $query->execute()->fetchCol();
    if (!$nids) {
      return;
    }

    $node_storage = $this->entityManager->getStorage('node');
    foreach ($node_storage->loadMultiple($nids) as $node) {
      $this->indexNode($node);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function indexStatus() {
    if (!count($this->configuration['excluded_bundles'])) {
      return parent::indexStatus();
    }

    $total = $this->database->query('SELECT COUNT(*) FROM {node} WHERE type NOT IN (:excluded_bundles[])', array(':excluded_bundles[]' => $this->configuration['excluded_bundles']))->fetchField();
    $remaining = $this->database->query("SELECT COUNT(DISTINCT n.nid) FROM {node} n LEFT JOIN {search_dataset} sd ON sd.sid = n.nid AND sd.type = :type WHERE (sd.sid IS NULL OR sd.reindex <> 0) AND n.type NOT IN (:excluded_bundles[])", array(':type' => $this->getPluginId(), ':excluded_bundles[]' => $this->configuration['excluded_bundles']))->fetchField();

    return array('remaining' => $remaining, 'total' => $total);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();
    $configuration['excluded_bundles'] = [];
    return $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function searchFormAlter(array &$form, FormStateInterface $form_state) {
    parent::searchFormAlter($form, $form_state);

    // Remove excluded bundles from search form.
    $options = $form['advanced']['types-fieldset']['type']['#options'];
    $bundles = array_diff_key($options, $this->configuration['excluded_bundles']);
    $form['advanced']['types-fieldset']['type']['#options'] = $bundles;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Get node bundles.
    $bundles = array_map(array('\Drupal\Component\Utility\Html', 'escape'), node_type_get_names());

    // Only show the form if we have node bundles.
    if (!count($bundles)) {
      return $form;
    }

    $form['exclude_bundles'] = [
      '#type' => 'details',
      '#title' => t('Exclude content types'),
      '#open' => TRUE,
    ];

    $form['exclude_bundles']['info'] = [
      '#markup' => '<p><em>' . $this->t('Select the content types to exclude from the search index.') . '</em></p>'
    ];

    $form['exclude_bundles']['excluded_bundles'] = [
      '#type' => 'checkboxes',
      '#options' => $bundles,
      '#default_value' => $this->configuration['excluded_bundles'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['excluded_bundles'] = array_filter($form_state->getValue('excluded_bundles'));
    parent::submitConfigurationForm($form, $form_state);
  }
}
