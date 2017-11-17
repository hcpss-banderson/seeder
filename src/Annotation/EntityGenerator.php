<?php

namespace Drupal\seeder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an entity generator annotation object.
 *
 * Plugin Namespace: Plugin/EntityGenerator
 *
 * @Annotation
 */
class EntityGenerator extends Plugin {
  
  /**
   * The entity generator plugin id.
   *
   * @var string
   */
  public $id;
  
  /**
   * The human-readable name of the entity generator plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $title;
  
  /**
   * The description of the value entity plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;
}
