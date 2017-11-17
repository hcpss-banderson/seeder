<?php

namespace Drupal\seeder;

/**
 * Abstract entitiy generator.
 */
abstract class AbstractEntityGenerator implements EntityGeneratorInterface {
  
  /**
   * FQCN for entity.
   * 
   * @var string
   */
  protected $entityClass;
  
  /**
   * Bundle
   * 
   * @var string
   */
  protected $bundle;
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    $this->entityClass = $configuration['entity_class'];
    $this->bundle = $configuration['bundle'];
  }
  
  /**
   * {@inheritDoc}
   * @see EntityGeneratorInterface::generate()
   */
  abstract public function generate();
}
