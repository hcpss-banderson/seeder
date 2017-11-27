<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Define a value generator implementation for related entities.
 * 
 * @ValueGenerator(
 *   id = "relationship",
 *   title = @Translation("Relationship"),
 *   description = @Translation("Related entitiy")
 * )
 */
class Relationship extends AbstractValueGenerator {
  
  /**
   * {@inheritDoc}
   * @see AbstractValueGenerator::generate()
   */
  public function generate() {
    $query = \Drupal::entityQuery($this->value['entity_type']);
    
    foreach ($this->value['conditions'] as $condition) {
      $comparison = isset($condition[2]) ? $condition[2] : '=';
      $query->condition($condition[0], $condition[1]);
    }
    
    $ids = $query->execute();
    
    return array_shift($ids);
  }
}
