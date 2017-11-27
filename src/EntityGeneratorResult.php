<?php

namespace Drupal\seeder;

/**
 * Results of an entity generator.
 */
class EntityGeneratorResult implements EntityGeneratorResultInterface {
  
  /**
   * The entity type. 
   * 
   * @var string
   */
  protected $entityType;
  
  /**
   * The entity subtype (bundle).
   * 
   * @var string
   */
  protected $entitySubtype;
  
  /**
   * Entity ids that were generated.
   * 
   * @var array
   */
  protected $entityIds = [];
  
  public function __construct(string $entityType, string $entitySubtype = NULL) {
    $this->entityType = $entityType;
    $this->entitySubtype = $entitySubtype;
  }
  
  /**
   * Get the entity type that was generated.
   *
   * @return string
   */
  public function getEntityType() : string {
    return $this->entityType;
  }
  
  /**
   * Get the entity subtype (bundle) that was generated.
   *
   * @return string|null
   */
  public function getEntitySubtype() {
    return $this->entitySubtype;
  }
  
  /**
   * Get the ids of the created entities.
   *
   * @return array
   */
  public function getEntityIds() : array {
    return $this->entityIds;
  }
  
  /**
   * Add an entity id to the result.
   * 
   * @param int $entityId
   * @return \Drupal\seeder\EntityGeneratorResult
   */
  public function addEntityId(int $entityId) {
    $this->entityIds[] = $entityId;
    
    return $this;
  }
}
