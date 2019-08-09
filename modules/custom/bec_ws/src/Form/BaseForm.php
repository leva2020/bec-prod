<?php
/**
 * @file
 * Contains Drupal\bec_ws\Form\BaseForm.
 */
namespace Drupal\bec_ws\Form;
use Drupal\Core\Form\FormBase ;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\Html;

class BaseForm extends FormBase  {

  /**
   * Retorna un id único para el formulario
   */
  public function getFormId() {
    return 'base_form';
  }

  /**
   * Retorna la tabla de resultados con el botón exportar
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //Se obtiene variable de configuracion
    $config = \Drupal::config('bec_ws.settings');
    //Se genera form
    $form['#prefix'] = '<br>';
      //Generate form filter
    $form['f1'] = array(
      '#type' => 'details',
      '#open' => FALSE,
      '#title' => t('URL Base')
    );
    $form['f1']['url_base'] = array(
      '#type' => 'textfield',
      '#default_value' => $config->get('url_base'),
      '#title' => t('URL Base del WS a consumir'),
      '#required' => TRUE,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Guardar'),
    );

    return $form;
  }


  /**
   * Form submission handler.
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   */
   public function submitForm(array &$form, FormStateInterface $form_state) {
     //Se obtiene variable de configuracion para editar
     try{
      $config = \Drupal::service('config.factory')->getEditable('bec_ws.settings');
      $config->set('url_base',$form['f1']['url_base']['#value'])->save();
      drupal_set_message(t("Se han actualizado los valores de configuración satisfactoriamente"));
     } catch (Exception $e){
      drupal_set_message(t("Se ha producido un error al actualizar los valores de configuración."), "error");
     }
    }
}
