<?php

namespace Drupal\seeder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a seeder annotation object.
 * 
 * Plugin Namespace: Plugin/Seeder
 * 
 * @Annotation
 */
class Seeder extends Plugin {
  
  /**
   * The value generator plugin id.
   * 
   * @var string
   */
  public $id;
  
  /**
   * The human-readable name of the seeder plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $title;
  
  /**
   * The description of the seeder plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;
}
