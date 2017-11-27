<?php

namespace Drupal\seeder\Plugin\EntityGenerator;

use Drupal\seeder\AbstractEntityGenerator;
use Drupal\seeder\EntityGeneratorResult;
use Drupal\seeder\EntityGeneratorResultInterface;

/**
 * Generator for generating entities.
 * 
 * @EntityGenerator(
 *   id = "generator",
 *   title = @Translation("Entity Generator"),
 *   description = @Translation("Generate entities from parameters.")
 * )
 */
class GeneratorGenerator extends AbstractEntityGenerator {
  
  /**
   * Minimum number of entities to generate.
   * 
   * @var int
   */
  protected $min = 10;
  
  /**
   * Maximum number of entities to generate.
   * 
   * @var int
   */
  protected $max = 20;
  
  /**
   * Configuration of the fields.
   * 
   * @var array
   */
  protected $fieldConfig = [];
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    
    $data = $configuration['plugin']['data'];
    
    if (isset($data['min'])) {
      $this->min = $data['min'];
    }
    
    if (isset($data['max'])) {
      $this->min = $data['max'];
    }
    
    foreach ($data['fields'] as $field => $config) {
      $this->fieldConfig[$field] = $config;
    }
  }
  
  /**
   * Figure out if we should seed this value based on the "fill" value.
   * 
   * @param array $config
   * @return boolean
   */
  protected function hasValue($config) {
    $fill = isset($config['fill']) ? $config['fill'] : 100;
    return $fill >= rand(1, 100);
  }
  
  /**
   * {@inheritDoc}
   * @see AbstractEntityGenerator::generate()
   */
  public function generate() : EntityGeneratorResultInterface {
    $numEntities = rand($this->min, $this->max);
    $pluginType = \Drupal::service('plugin.manager.value_generator');
    $key = $this->entityTypeDefinition->getKey('bundle');
    
    $result = new EntityGeneratorResult($this->entityType, $this->entitySubtype);
    for ($i = 0; $i <= $numEntities; $i++) {
      $entity = $this->entityTypeDefinition->getClass()::create([
        $key => $this->entitySubtype,
      ]);
      
      foreach ($this->fieldConfig as $field => $config) {
        if (!$this->hasValue($config)) {
          continue;
        }
        
        $value = $pluginType->createInstance($config['plugin'], [
          'entity' => $entity,
          'config' => $config['value'],
          'precalculated_values' => $this->precalculatedValues,
        ])->generate();
        
        $entity->set($field, $value);
      }
      
      $entity->save();
      $result->addEntityId($entity->id());
    }
    
    return $result;
  }
}
