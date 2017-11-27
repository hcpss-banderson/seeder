<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;

/**
 * Value generator from another seed.
 * 
 * @ValueGenerator(
 *   id = "seed",
 *   title = @Translation("Seed"),
 *   description = @Translation("Value from another seed.")
 * )
 */
class Seed extends AbstractValueGenerator {

  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\AbstractValueGenerator::generate()
   */
  public function generate() {
    // $pluginType = \Drupal::service('plugin.manager.entity_generator');
    
    $directories   = \Drupal::moduleHandler()->getModuleDirectories();
    $yamlDiscovery = new YamlDiscovery('seed', $directories);
    $discovery     = new ContainerDerivativeDiscoveryDecorator($yamlDiscovery);
    $pluginType    = \Drupal::service('plugin.manager.seeder');
    $definition   = $discovery->getDefinition($this->value);
    $plugin = $pluginType->createInstance($definition['type'], $definition);
    
    /** @var \Drupal\seeder\EntityGeneratorResultInterface $result */
    $result = $plugin->seed();
    
    return $result->getEntityIds()[0];
  }
}
