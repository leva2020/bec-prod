<?php
namespace Drupal\bec_ws\Extensions;
use Drupal\bec_ws\Controller\ServiciosController;

/**
 * Class DefaultService.
 *
 * @package Drupal\bec_ws
 */
class SendMethodTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   * This function must return the name of the extension. It must be unique.
   */
  public function getName() {
    return 'enviar_post_ws';
  }

  /**
   * In this function we can declare the extension function
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('enviar_post_ws',
        array($this, 'enviar_post_ws'),
        array('is_safe' => array('html'))));
  }

  /**
   * The php function to load a given block
   */  
  public function enviar_post_ws($method,$param,$formatear,$fechaCompleta,$filtro,$campos,$cantidad) {
    $servicio = new ServiciosController;
    $result = $servicio->enviarPeticionPost($method,$param,$formatear,$fechaCompleta,$filtro,$campos,$cantidad);
    return $result;
  }

}
