<?php

namespace Drupal\mydata\Plugin\rest\resource;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "example_get_rest_resource",
 *   label = @Translation("Example get rest resource"),
 *   uri_paths = {
 *     "canonical" = "/rest/id/{id}"
 *   }
 * )
 */
class ExampleGetRestResource extends ResourceBase {
  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;
  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentUser = $current_user;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('mydata'),
      $container->get('current_user')
    );
  }
  
  public function get($id) { 
    
    if (!$this->currentUser->hasPermission('access content') && isset($id)) {
      throw new AccessDeniedHttpException();
    }
    
    
    $siteapikey = \Drupal::state()->get('mydata.settings.siteapikey');
    
   
    if(empty($siteapikey) || $siteapikey == "No API Key yet"){
        
        $result['message'] = "access denied.";
    }else{
        $result = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'page','nid'=>$id]);
        if(empty($result)){
            $result['message'] = "no record found.";
            }
    }
    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  }
}