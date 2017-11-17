<?php

namespace Drupal\seeder\Plugin\EntityGenerator;

use Drupal\seeder\AbstractEntityGenerator;

/**
 * Generator for simple seeders.
 * 
 * @EntityGenerator(
 *   id = "itemized",
 *   title = @Translation("Itemized Generator"),
 *   description = @Translation("Itemize your entities to create.")
 * )
 */
class ItemizedGenerator extends AbstractEntityGenerator {
  
  /**
   * Entity definitions.
   * 
   * @var array
   */
  protected $entityDefinitions;
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityDefinitions = $configuration['plugin']['data']['entities'];
  }

  /**
   * {@inheritDoc}
   * @see AbstractEntityGenerator::generate()
   */  
  public function generate() {
    $pluginType = \Drupal::service('plugin.manager.value_generator');
    
    foreach ($this->entityDefinitions as $entityDefinition) {
      $entity = $this->entityClass::create(['type' => $this->bundle]);
      
      foreach ($entityDefinition as $property => $config) {
        $pluginName = $config['plugin'];
        $configuration = [
          'config' => $config['value'],
          'entity' => $entity,
        ];
        
        $plugin = $pluginType->createInstance($pluginName, $configuration);
        $entity->{$property} = $plugin->generate();
      }
      
      $entity->save();
    }
  }
}
