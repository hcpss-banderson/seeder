<?php

namespace Drupal\seeder;

/**
 * An interface for Entity Generators.
 */
interface EntityGeneratorInterface {
  
  /**
   * Generate an entity.
   */
  public function generate();
}
