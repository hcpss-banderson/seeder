<?php

namespace Drupal\seeder;

use Drupal\Core\Entity\ContentEntityType;

/**
 * Abstract entitiy generator.
 */
abstract class AbstractEntityGenerator implements EntityGeneratorInterface {
  
  /**
   * FQCN for entity.
   * 
   * @var string
   */
  protected $entityType;
  
  /**
   * Bundle
   * 
   * @var string
   */
  protected $entitySubtype;
  
  /**
   * The definition for this entity
   * 
   * @var ContentEntityType
   */
  protected $entityTypeDefinition;
  
  /**
   * Values calculated beforehand so that they can be used multiple times in
   * the seed.
   * 
   * @var array
   */
  protected $precalculatedValues = [];
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    $this->entityType = $configuration['entity_type'];
    $this->entitySubtype = $configuration['entity_subtype'];
    $this->entityTypeDefinition = \Drupal::entityTypeManager()
      ->getDefinition($this->entityType);    
    
    if (isset($configuration['precalculated_values'])) {
      $pluginType = \Drupal::service('plugin.manager.value_generator');
      
      // ValueGenerators require an entity, but we don't really have one yet
      // so we are makeing an empty one.
      $entity = $this->entityTypeDefinition->getClass()::create([
        $this->entityTypeDefinition->getKey('bundle') => $this->entitySubtype,
      ]);
      
      foreach ($configuration['precalculated_values'] as $key => $config) {
        $this->precalculatedValues[$key] = $pluginType->createInstance($config['plugin'], [
          'entity' => $entity,
          'config' => $config['value'],
          'precalculated_values' => $this->precalculatedValues,
        ])->generate();
      }
    }
  }
  
  /**
   * {@inheritDoc}
   * @see EntityGeneratorInterface::generate()
   */
  abstract public function generate() : EntityGeneratorResultInterface;
}
