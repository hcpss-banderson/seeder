<?php

namespace Drupal\seeder;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Component\Plugin\Factory\DefaultFactory;

/**
 * Seeder plugin manager.
 */
class SeederManager extends DefaultPluginManager {
  
  /**
   * Construct Seeder plugin manager.
   *
   * @param \Traversable $namespaces
   * @param CacheBackendInterface $cache_backend
   * @param ModuleHandlerInterface $module_handler
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/Seeder',
      $namespaces,
      $module_handler,
      'Drupal\seeder\SeederInterface',
      'Drupal\seeder\Annotation\Seeder'
    );
    
    $this->alterInfo('seeder_info');
    $this->setCacheBackend($cache_backend, 'seeder_info_plugins');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }
}
