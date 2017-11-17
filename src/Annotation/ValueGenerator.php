<?php

namespace Drupal\seeder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a value generator annotation object.
 * 
 * Plugin Namespace: Plugin/ValueGenerator
 * 
 * @Annotation
 */
class ValueGenerator extends Plugin {
  
  /**
   * The value generator plugin id.
   * 
   * @var string
   */
  public $id;
  
  /**
   * The human-readable name of the value generator plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $title;
  
  /**
   * The description of the value generator plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;
}
