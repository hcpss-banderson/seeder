<?php

namespace Drupal\seeder;

/**
 * Abstract seeder.
 */
abstract class AbstractSeeder implements SeederInterface {
  
  /**
   * The module using the seeder.
   * 
   * @var string
   */
  protected $provider;
  
  /**
   * The id of the seed.
   * 
   * @var string
   */
  protected $id;
  
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    $this->provider = $configuration['provider'];
    $this->id = $configuration['id'];
  }
  
  /**
   * {@inheritDoc}
   * @see \Drupal\seeder\SeederInterface::seed()
   */
  abstract public function seed() : EntityGeneratorResultInterface;
}