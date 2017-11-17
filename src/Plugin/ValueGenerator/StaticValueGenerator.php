<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Defines a value generator implimentation for static values.
 *
 * @ValueGenerator(
 *   id = "static_value",
 *   title = @Translation("Static Value"),
 *   description = @Translation("Use a static value.")
 * )
 */
class StaticValueGenerator extends AbstractValueGenerator {  
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\AbstractValueGenerator::generate()
   */
  public function generate() {
    return $this->value;
  }
}
