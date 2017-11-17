<?php

namespace Drupal\seeder;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Component\Plugin\Factory\DefaultFactory;

/**
 * EntityGenerator plugin manager.
 */
class EntityGeneratorManager extends DefaultPluginManager {
  
  /**
   * Construct EntityGenerator plugin manager.
   *
   * @param \Traversable $namespaces
   * @param CacheBackendInterface $cache_backend
   * @param ModuleHandlerInterface $module_handler
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/EntityGenerator',
      $namespaces,
      $module_handler,
      'Drupal\seeder\EntityGeneratorInterface',
      'Drupal\seeder\Annotation\EntityGenerator'
    );
    
    $this->alterInfo('entity_generator_info');
    $this->setCacheBackend($cache_backend, 'entity_generator_info_plugins');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }
}
