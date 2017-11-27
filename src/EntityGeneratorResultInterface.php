<?php

namespace Drupal\seeder;

/**
 * An interface for the results of an entity generator generation.
 */
interface EntityGeneratorResultInterface {
  
  /**
   * Get the entity type that was generated.
   * 
   * @return string
   */
  public function getEntityType() : string;
  
  /**
   * Get the entity subtype (bundle) that was generated.
   * 
   * @return string|null
   */
  public function getEntitySubtype();
  
  /**
   * Get the ids of the created entities.
   * 
   * @return array
   */
  public function getEntityIds() : array;
}
