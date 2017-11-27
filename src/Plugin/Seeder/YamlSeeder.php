<?php

namespace Drupal\seeder\Plugin\Seeder;

use Drupal\seeder\AbstractSeeder;
use Drupal\Component\Serialization\Yaml;
use Drupal\seeder\EntityGeneratorResultInterface;

/**
 * Seeder for data stored in yaml files.
 * 
 * @Seeder(
 *   id = "yaml_file",
 *   title = @Translation("YAML file"),
 *   description = @Translation("Store your info in YAML files.")
 * )
 */
class YamlSeeder extends AbstractSeeder {
  
  /**
   * The path to the YAML file
   * 
   * @var string
   */
  protected $dataPath;
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    
    $this->dataPath = vsprintf('%s/%s', [
      drupal_get_path('module', $this->provider),
      $configuration['arguments'][0]
    ]);
  }
  
  /**
   * {@inheritDoc}
   * @see AbstractSeeder::seed()
   */
  public function seed() : EntityGeneratorResultInterface {    
    $data       = Yaml::decode(file_get_contents($this->dataPath));
    $pluginType = \Drupal::service('plugin.manager.entity_generator');
    $pluginName = $data['plugin']['name'];
    $plugin     = $pluginType->createInstance($pluginName, $data);
    
    return $plugin->generate();
  }
}
