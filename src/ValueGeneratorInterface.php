<?php

namespace Drupal\seeder;

/**
 * Interface for value Generators
 */
interface ValueGeneratorInterface {
  
  /**
   * Generate the value.
   */
  public function generate();
}
