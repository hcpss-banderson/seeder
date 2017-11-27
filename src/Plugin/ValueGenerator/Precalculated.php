<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Value generator for precalculated values.
 * 
 * @ValueGenerator(
 *   id = "precalculated_value",
 *   title = @Translation("Precalculated"),
 *   description = @Translation("Use a precalculated value.")
 * )
 */
class Precalculated extends AbstractValueGenerator {
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\AbstractValueGenerator::generate()
   */
  public function generate() {    
    return $this->precalculatedValues[$this->value];
  }
}
