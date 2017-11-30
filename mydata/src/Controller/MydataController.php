<?php
namespace Drupal\mydata\Controller;
use Drupal\Core\Controller\ControllerBase;
 use Drupal\Core\Url;
/**
 * Class MydataController.
 *
 * @package Drupal\mydata\Controller
 */
class MydataController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
   
  public function display() {
	$url = Url::fromRoute('mydata.mydata_form');
	$internal_link = \Drupal::l(t('#'), $url);
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about my data. '.$internal_link.' ')
    ];
  }

}
