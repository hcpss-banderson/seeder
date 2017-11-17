<?php

namespace Drupal\seeder;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Component\Plugin\Factory\DefaultFactory;

/**
 * ValueGenerator plugin manager. 
 */
class ValueGeneratorManager extends DefaultPluginManager {
  
  /**
   * Construct ValueGenerator plugin manager.
   * 
   * @param \Traversable $namespaces
   * @param CacheBackendInterface $cache_backend
   * @param ModuleHandlerInterface $module_handler
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/ValueGenerator',
      $namespaces,
      $module_handler,
      'Drupal\seeder\ValueGeneratorInterface',
      'Drupal\seeder\Annotation\ValueGenerator'
    );
    
    $this->alterInfo('value_generator_info');
    $this->setCacheBackend($cache_backend, 'value_generator_info_plugins');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }
}
