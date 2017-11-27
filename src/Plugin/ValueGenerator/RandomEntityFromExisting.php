<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Defines a value generator implementation for random entities.
 * 
 * @ValueGenerator(
 *   id = "random_entity_from_existing",
 *   title = @Translation("Random entity from existing"),
 *   description = @Translation("Pick an entity at random.")
 * )
 */
class RandomEntityFromExisting extends AbstractValueGenerator {
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\AbstractValueGenerator::generate()
   */
  public function generate() {    
    $query = \Drupal::entityQuery($this->value['entity_type']);
    $entityTypeDefinition = \Drupal::entityTypeManager()
      ->getDefinition($this->value['entity_type']);  
    
    if (isset($this->value['entity_subtype']) && $this->value['entity_subtype']) {
      $query->condition($entityTypeDefinition->getKey('bundle'), $this->value['entity_subtype']);
    }
    
    if (isset($this->value['conditions'])) {
      foreach ($this->value['conditions'] as $condition) {
        $comparison = isset($condition[2]) ? $condition[2] : '=';
        $query->condition($condition[0], $condition[1], $comparison);
      }
    }
    
    $ids = $query->execute();    

    return $ids[array_rand($ids)];
  }
}
