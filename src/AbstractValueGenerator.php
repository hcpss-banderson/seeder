<?php

namespace Drupal\seeder;

use Drupal\Core\Entity\EntityInterface;

/**
 * Abstract value generator.
 */
abstract class AbstractValueGenerator implements ValueGeneratorInterface {
  
  /**
   * The entity we are generating a value for.
   *
   * @var EntityInterface
   */
  protected $entity;
  
  /**
   * The static value to return.
   *
   * @var string
   */
  protected $value;
  
  /**
   * Constructor implementation.
   * 
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    $this->entity = $configuration['entity'];
    $this->value = $configuration['config'];
  }
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\ValueGeneratorInterface::generate()
   */
  abstract public function generate();
}
