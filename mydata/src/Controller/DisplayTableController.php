<?php

namespace Drupal\mydata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'mydata_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    //create table header
    $header_table = array(
     'id'=>    t('SrNo'),
      'name' => t('Name'),
        'mobilenumber' => t('MobileNumber'),
        //'email'=>t('Email'),
        'age' => t('Age'),
        'gender' => t('Gender'),
        //'website' => t('Web site'),
        'opt' => t('operations'),
        'opt1' => t('operations'),
    );

//select records from table
    $query = \Drupal::database()->select('mydata', 'm');
    $query->fields('m', ['id','name','mobilenumber','email','age','gender','website']);
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);
    $results = $pager->execute()->fetchAll();

    $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/mydata/form/delete/'.$data->id);
        $edit   = Url::fromUserInput('/mydata/form/mydata?num='.$data->id);

      //print the data from table
             $rows[] = array(
            'id' =>$data->id,
                'name' => $data->name,
                'mobilenumber' => $data->mobilenumber,
                //'email' => $data->email,
                'age' => $data->age,
                'gender' => $data->gender,
                //'website' => $data->website,

                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );

    }
    //display data in site
    $form = array(
      '#markup' => t('<div class="aaa" >List of All Users</div>')
    );
    $form['overlay_link'] = array(
     '#type' => 'link',
     '#title' => $this->t('Add User'),
     '#url' => Url::fromUserInput('/mydata/form/mydata'),
     '#attributes' => [
       'class' => ['piwikTrackContent'],
     ],
   );
    
    
 
    // Generate the table.
    $form['config_table'] = array(
      '#theme' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No users found'),
    );
 
    // Finally add the pager.
    $form['pager'] = array(
      '#type' => 'pager'
    );
    
 
    return $form;

  }

}
