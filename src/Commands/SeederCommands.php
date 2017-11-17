<?php

namespace Drupal\seeder\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;

/**
 * Our drush commands.
 */
class SeederCommands extends DrushCommands {

  /**
   * Seed entities from seed files.
   *
   * @command seeder:seed
   * @option tier The tier to seed. Defaults to all tiers.
   * @usage drush seeder:seed
   *   Seed all entities.
   * @usage drush seeder:seed --tier=1
   *   Seed entities only from seeders marked as tier 1.
   */
  public function seed($options = ['tier' => 0]) {
    $directories   = \Drupal::moduleHandler()->getModuleDirectories();
    $yamlDiscovery = new YamlDiscovery('seed', $directories);
    $discovery     = new ContainerDerivativeDiscoveryDecorator($yamlDiscovery);
    $pluginType    = \Drupal::service('plugin.manager.seeder');
    $definitions   = $discovery->getDefinitions();
    
    uasort($definitions, function($a, $b){
      return $a['priority'] - $b['priority'];
    });
    
    foreach ($definitions as $pluginId => &$definition) {
      $tier = isset($definition['tier']) ? $definition['tier'] : 1;
      if ($options['tier'] && $options['tier'] >= $tier) {
        continue;
      }
      
      $pluginName = $definition['type'];
      $plugin = $pluginType->createInstance($pluginName, $definition);
      $plugin->seed();
    }
  }
}
