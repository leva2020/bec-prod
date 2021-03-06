<?php

use Drupal\Core\Form\FormStateInterface;

/**
 * Does the string overrides unconditional on what other changes are pending for
 * the form.
 * @param $form
 * @param $form_state
 */
function _search_config_set_string_overrides(&$form, $form_state) {
  $labels = search_config_string_overrides('labels');

  // The two submit buttons.
  if (isset($form['basic']['submit']) && !empty($labels['basic_submit'])) {
    $form['basic']['submit']['#value'] = t('!search_config:basic_submit', ['!search_config:basic_submit' => $labels['basic_submit']]);
  }
  if (isset($form['advanced']['submit']) && !empty($labels['advanced_submit'])) {
    $form['advanced']['submit']['#value'] = t('!search_config:advanced_submit', ['!search_config:advanced_submit' => $labels['advanced_submit']]);
  }

  // Other form elements. Map into an array to iterate over each element.
  $title_display = search_config_string_overrides('title_display');
  $label_elements = [];
  if (isset($form['basic'])) {
    $label_elements['basic'] = &$form['basic']['keys'];
  }
  if (isset($form['advanced'])) {
    $label_elements['advanced_fieldset'] = &$form['advanced'];
    $label_elements['advanced_type'] = &$form['advanced']['type'];

    $fkeywords = &$form['advanced']['keywords'];
    $label_elements['advanced_any'] = &$fkeywords['or'];
    $label_elements['advanced_phrase'] = &$fkeywords['phrase'];
    $label_elements['advanced_none'] = &$fkeywords['negative'];

    if (isset($form['advanced']['language'])) {
      $label_elements['advanced_language'] = &$form['advanced']['language'];
    }
  }

  foreach ($label_elements as $key => $element) {
    if (empty($element)) {
      continue;
    }
    if (!empty($fkeys['#default_value']) && !empty($labels[$key . '_with_keys'])) {
      $label_elements[$key]['#title'] = t('!search_config:' . $key, ['!search_config:' . $key => $labels[$key . '_with_keys']]);
    }
    elseif (!empty($labels[$key])) {
      $label_elements[$key]['#title'] = t('!search_config:' . $key, ['!search_config:' . $key => $labels[$key]]);
    }
    if (!empty($title_display[$key])) {
      switch ($title_display[$key]) {
        case 'description':
          $label_elements[$key]['#description'] = $label_elements[$key]['#title'];
          $label_elements[$key]['#title_display'] = 'invisible';
          break;

        case 'invisible':
          $label_elements[$key]['#title_display'] = 'invisible';
          break;

        case 'default':
        default:

      }
    }
  }
}

/**
 * This function implements the options to configure the default Drupal search
 * form, including type filter, field visibility, form visibility, etc.
 * @param $form
 * @param $form_state
 * @return mixed
 */
function _search_config_advanced_form(&$form, $form_state) {
  global $user, $search_config_node_results;

  // Settings
  $settings = search_config_node_settings();
  $fs_phrase = $settings['fields']['containing_phrase'];
  $fs_any = $settings['fields']['containing_any'];
  $fs_none = $settings['fields']['containing_none'];
  $fs_types = $settings['fields']['types'];

  // Return the form for super admin unchanged
  if ($user->id() == 1 && !empty($settings['restrictions']['admin_bypass'])) {
    return $form;
  }

  // Form elements
  $fkeys = &$form['basic']['keys'];
  $fkeywords = &$form['advanced']['keywords'];
  $ftype = &$form['advanced']['type'];
  if (isset($form['advanced']['language'])) {
    $fs_language = $settings['fields']['language'];
    $form['advanced']['language']['#access'] = search_config_get_access($fs_language['remove'], $fs_language['roles']);
  }

  // Change the form fieldset.
  switch ($settings['forms']['advanced_expand']) {
    case 'remove':
      $form['advanced']['#type'] = 'item';
      $form['advanced']['#prefix'] = '<div class="search-advanced clearfix">';
      $form['advanced']['#suffix'] = '</div>';
      unset($form['advanced']['#title']);
      break;

    case 'expand_always':
      $form['advanced']['#collapsible'] = FALSE;
      break;

    case 'expand_if_empty':
      $form['advanced']['#collapsed'] = !empty($search_config_node_results);
      break;

    case 'expand_on_first':
    case 'default':
    default:
      // Do nothing!

  }

  // Set form element access rights.
  if ($settings['forms']['toggle_forms']) {
    $form['basic']['#access'] = FALSE;
  }
  $fkeywords['phrase']['#access'] = search_config_get_access($fs_phrase['remove'], $fs_phrase['roles']);
  $fkeywords['or']['#access'] = search_config_get_access($fs_any['remove'], $fs_any['roles']);
  $fkeywords['negative']['#access'] = search_config_get_access($fs_none['remove'], $fs_none['roles']);
  $ftype['#access'] = search_config_get_access($fs_types['remove'], $fs_types['roles']);

  switch ($settings['forms']['remove_containing_wrapper']) {
    case 'remove':
      $fkeywords['phrase']['#access'] = FALSE;
      $fkeywords['or']['#access'] = FALSE;
      $fkeywords['negative']['#access'] = FALSE;
      $fkeywords['#access'] = FALSE;
      break;

    case 'empty':
      // Check the 3 fields and also if we are going to move the basic form here.
      if (!($fkeywords['phrase']['#access'] || $fkeywords['or']['#access'] || $fkeywords['negative']['#access']
        || $settings['forms']['move_keyword_search'])
      ) {
        $fkeywords['#access'] = FALSE;
      }
      break;

    case 'default':
    default:
      // Do nothing.
  }

  // Set the advanced forms values, (as best we can).
  if ($settings['forms']['advanced_populate']) {
    $basic_values = [];
    $values = _parse_search_expression($fkeys['#default_value']);
    $sections = _parse_search_expression($fkeys['#default_value']);

    // Apply any negitive values.
    if (count($sections['negative'])) {
      foreach ($sections['negative'] as $index => $value) {
        if (is_array($value)) {
          $sections['negative'][$index] = implode(' OR ', $value);
        }
      }
      if ($fkeywords['negative']['#access']) {
        $fkeywords['negative']['#value'] = implode(' ', $sections['negative']);
      }
      else {
        foreach ($sections['negative'] as $negative) {
          $basic_values[] = '-' . $negative;
        }
      }
    }

    if (count($sections['positive'])) {
      // Try and find an OR set.
      $no_or = $fkeywords['or']['#access'];
      $no_phrase = $fkeywords['phrase']['#access'];
      foreach ($sections['positive'] as $index => $value) {
        if (is_array($value)) {
          if ($no_or) {
            $fkeywords['or']['#value'] = implode(' ', $value);
            $no_or = TRUE;
          }
          else {
            $basic_values[] = implode(' OR ', $value);
          }
        }
        else {
          if (strpos($value, ' ')) {
            if ($no_phrase) {
              $fkeywords['phrase']['#value'] = $value;
              $no_phrase = FALSE;
            }
            else {
              $value = '"' . $value . '"';
              $basic_values[] = $value;
            }
          }
          else {
            $basic_values[] = $value;
          }
        }
      }
    }

    if (isset($sections['options']['type']) && $ftype['#access']) {
      $types = explode(',', $sections['options']['type']);
      $ftype['#default_value'] = array_combine($types, $types);
      unset($sections['options']['type']);
    }

    // @todo: figure out how these work: 'language' & 'term'
    foreach (['language', 'term', 'type'] as $key) {
      if (isset($sections['options'][$key])) {
        $basic_values[] = $key . ':' . $sections['options'][$key];
      }
    }

    // Lose the values if we have no access
    $access = empty($fkeys['#access']) ? 1 : $fkeys['#access'];
    if ($access) {
      $fkeys['#default_value'] = implode(' ', $basic_values);
    }
  }

  if ($ftype['#access']) {
    // Remove these ones from the display.
    $base_types = array_filter($fs_types['filter']);
    $access = \Drupal::currentUser()->hasPermission('search all content');
    $allowed_types = [];
    foreach (search_config_content_types() as $key => $type) {
      if (in_array($key, $base_types) || !($access || \Drupal::currentUser()
            ->hasPermission("search $key content"))
      ) {
        unset($ftype['#options'][$key]);
      }
      else {
        $allowed_types [$key] = $key;
      }
    }
    if (!empty($fs_types['groupings'])) {
      // Parse the groupings for used types.
      $selected_types = empty($ftype['#default_value']) ? [] : $ftype['#default_value'];
      $grouping_selected_types = [];
      $used_types = [];
      foreach ($fs_types['groupings'] as $gtypes => $glabel) {
        foreach (explode(',', $gtypes) as $gtype) {
          if (isset($allowed_types[$gtype])) {
            $used_types [$gtype] = $gtype;
          }
        }
      }
      // And again to set the values
      $filtered_groupings = [];
      foreach ($fs_types['groupings'] as $gtypes => $glabel) {
        $filtered_gtypes = [];
        foreach (explode(',', $gtypes) as $gtype) {
          if (isset($allowed_types[$gtype])) {
            $filtered_gtypes [$gtype] = $gtype;
          }
          elseif ($gtype == '<all-types>') {
            $filtered_gtypes += $allowed_types;
          }
          elseif ($gtype == '<other-types>') {
            $filtered_gtypes += array_diff_key($allowed_types, $used_types);
          }
        }

        $is_selected = array_intersect_key($selected_types, $filtered_gtypes);
        $gkey = implode(',', $filtered_gtypes);
        // Potentially, filtering will cause key overlaps. Keep the first label.
        if (!empty($gkey) && !isset($filtered_groupings[$gkey])) {
          $filtered_groupings[$gkey] = t($glabel);
          if ($is_selected) {
            $grouping_selected_types[$gkey] = $gkey;
          }
        }
      }

      $ftype['#options'] = $filtered_groupings;
      $ftype['#default_value'] = $grouping_selected_types;
      if (empty($ftype['#element_validate'])) {
        $ftype['#element_validate'] = [];
      }
      $ftype['#element_validate'] = ['search_config_type_element_validate'] + $ftype['#element_validate'];
    }
  }

  // TODO - Categories et al

  // This moves the keywords search element into the advanced form.
  if ($settings['forms']['move_keyword_search']) {
    $fkeys['#size'] = $fkeywords['or']['#size'];
    $fkeywords = ['keys' => $fkeys] + $fkeywords;
    unset($form['basic']['keys']);
    $form['basic']['#access'] = FALSE;
  }

  return $form;
}

/**
 * @param $element
 * @param $form_state
 */
function search_config_type_element_validate($element, FormStateInterface &$form_state) {
  $types = [];
  foreach ($element['#value'] as $opt_types) {
    foreach (explode(',', $opt_types) as $type) {
      $types[$type] = $type;
    }
  }
  $form_state->setValueForElement($element, $types);
}

/**
 * Cloned from the search module to parse the query string.
 */
function _parse_search_expression($expression) {
  $sections = [
    'negative' => [],
    'positive' => [],
    'options' => [],
  ];

  // Pull out known option selectors
  foreach (['type', 'language', 'term'] as $option) {
    if (preg_match('/(^| )' . $option . ':([^ ]*)( |$)/i', $expression, $matches)) {
      $sections['options'][$option] = $matches[2];
      $expression = str_replace($matches[0], ' ', $expression);
    }
  }

  // Matchs words optionally prefixed by a dash. A word in this case is
  // something between two spaces, optionally quoted.
  preg_match_all('/ (-?)("[^"]+"|[^" ]+)/i', ' ' . $expression, $keywords, PREG_SET_ORDER);

  if (!empty($keywords)) {
    // Classify tokens.
    $or = FALSE;
    foreach ($keywords as $match) {
      $phrase = FALSE;
      // Strip off phrase quotes.
      if ($match[2]{0} == '"') {
        $match[2] = substr($match[2], 1, -1);
        $phrase = TRUE;
      }
      // Simplify keyword according to indexing rules and external
      // preprocessors. Use same process as during search indexing, so it
      // will match search index.
      $words = search_simplify($match[2]);
      // Re-explode in case simplification added more words, except when
      // matching a phrase.
      $words = $phrase ? [$words] : preg_split('/ /', $words, -1, PREG_SPLIT_NO_EMPTY);
      // Negative matches.
      if ($match[1] == '-') {
        $sections['negative'] = array_merge($sections['negative'], $words);
      }
      // OR operator: instead of a single keyword, we store an array of all
      // OR'd keywords.
      elseif ($match[2] == 'OR' && count($sections['positive'])) {
        $last = array_pop($sections['positive']);
        // Starting a new OR?
        if (!is_array($last)) {
          $last = [$last];
        }
        $sections['positive'][] = $last;
        $or = TRUE;
        continue;
      }
      // AND operator: implied, so just ignore it.
      elseif ($match[2] == 'AND' || $match[2] == 'and') {
        continue;
      }

      // Plain keyword.
      else {
        if ($or) {
          // Add to last element (which is an array).
          $sections['positive'][count($sections['positive']) - 1] = array_merge($sections['positive'][count($sections['positive']) - 1], $words);
        }
        else {
          $sections['positive'] = array_merge($sections['positive'], $words);
        }
      }
      $or = FALSE;
    }
  }
  return $sections;
}
