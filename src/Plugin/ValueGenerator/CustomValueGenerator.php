<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Defines a value generator implimentation for custom functions.
 * 
 * @ValueGenerator(
 *   id = "custom",
 *   title = @Translation("Custom"),
 *   description = @Translation("Use a custom function to generate a value")
 * )
 */
class CustomValueGenerator extends AbstractValueGenerator {
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\ValueGeneratorInterface::generate()
   */
  public function generate() {
    return call_user_func_array($this->value, [
      $this->entity,
    ]);
  }
}
