<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Defines a value generator implementation for a random value from a list 
 * of values.
 * 
 * @ValueGenerator(
 *   id = "random_from_list",
 *   title = @Translation("Random From List"),
 *   description = @Translation("Use a random value from a list of values.")
 * )
 */
class RandomFromList extends AbstractValueGenerator {
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\AbstractValueGenerator::generate()
   */
  public function generate() {
    return $this->value[array_rand($this->value)];
  }
}
