# Seeder

A Drupal 8 module for seeding entities using developer provided parameters. It
provides no interface and is meant to be used by developers. At this time,
seed events can only be triggered via drush 9 (which has no stable release).

## Is this module production ready?

No.

## OK, I want to use it in development. How do I do that?

### Tell Seeder about your module's seeds

First you need to tell Seeder about the seeds you intend to perform. You do 
this in a file in the root of your module folder. Name it MODULE.seed.yml. 
Here's an example:

```yaml
# MYMODULE.seed.yml

# In this example, we are seeding a nodes of type client.
MODULE.client:

  # This module comes with only one seeder type. It's called yaml_file. It
  # allows you to define the seed in a yaml file.
  type: yaml_file
  
  # The yaml_file seeder takes one argument. It is the path to the yaml file,
  # relative to your module's root.
  arguments:
    - 'data/clients.yml'
    
  # Lower priority seeds are performed before higher ones.
  priority: 10  

# Seed some users.
MODULE.users:
  type: yaml_file
  arguments:
    - 'data/users.yml'
  priority: 5
  
# Seed nodes of type time_entry. These nodes rely on the clients and users
# already being seeded. So, it has a higher priority, ensuring it will be 
# seeded after those.
MODULE.times:
  type: yaml_file
  arguments:
    - 'data/times.yml'
  priority: 15 
```

You can create your own seed types by creating a Seeder plugin. You could
create one that uses JSON files, or an RSS feed or a database.

### Create your seed configuration files

The configuration files tell Seeder how to perform the seed. Do you want the
seeder to create a certain number of entities defined by you? Or maybe you
want a randome number of entities defined by loose constraints. These 2 
scenarios are implimented in this module with EntityGenerator plugins called
*itemized* and *generator*.

This is an example of an *itemized* seed:

```yaml
# data/clients.yml

# The class for the entity we are goint to create.
entity_class: \Drupal\node\Entity\Node

# The bundle of the entity we are going to create.
bundle: client

# This is where we configure our plugin.
plugin:

  # This seeder used the itemized entity generator plugin. This plugin expects
  # a list of entities that it will create.
  name: itemized
  data:
    # The list of entities to create. This example would create exactly 3 
    # clients.
    entities:
      - 
        # Here we put a list of values to use when creating the node. In this
        # case all we are setting is the title.
        title:
          # There are multiple plugins we can use to generate values for this
          # field. The static_value plugin just assigns the value of whatever
          # we put in "value".
          plugin: static_value
          value: 'ABC Company'
      - 
        title:
          plugin: static_value
          value: 'XYZ Company'
      - 
        title:
          plugin: static_value
          value: 'Yet Another Company'
```

This is an example of a *generator* seed:

```yaml
# data/times.yml

entity_class: \Drupal\node\Entity\Node
bundle: time_entry
plugin:

  # This seeder uses the generator entity generator plugin. This plugin
  # generates a number of entities based on this configuration. 
  name: generator
  
  # data is passed to the generator to configure it.
  data:
  
    # This generator will generate a random number of entities between 50 and 
    # 100.
    max: 100
    min: 50
    
    # This is where we tell the entity generator how we are going to generate
    # values for the fields of the entity.
    fields:
      title:
        
        # The random_from_list value generator plugin grabs a single value
        # from an array passed to "value".
        plugin: random_from_list
        
        # What percent of entities should have a value for this field?
        fill: 100
        
        # The list of values to randomly choose from.
        value:
          - 'Make the donuts'
          - 'Paint the living room'
          - 'Clean out the dishwasher'
          - 'Wash the car'
          - 'Shop for groceries'
          
      uid:
      
        # The random_entity_from_existing value generator plugin will pull a
        # random entity from entities that already exist.
        #
        # In this case we are relying on users being present. To ensure they
        # are seeded before now, they were assigned a lower priority in our
        # seed definition file "MODULE.seed.yml".
        plugin: random_entity_from_existing
        fill: 100
        value:
          # The entity type.
          entity_type: user
          
          # The bundle. In this case null because users have no bundles.
          bundle: ~
          
          # An optional list of conditions to use when querying the entities.
          # In this case, we don't want to use the admin or anonymous users.
          conditions:
            - ['uid', 0, '!=']
            - ['uid', 1, '!=']
        
      field_client:
        plugin: random_entity_from_existing
        
        # Setting fill to 80 means that there is a 20% chance that this field
        # will remain blank.
        fill: 80
        value:
          entity_type: node
          bundle: client
          
      field_start:
      
        # The custom plugin expects a callback to generate the value for the
        # field.
        plugin: custom
        fill: 100
        value: _MYMODULE_set_field_start

```

This is an example of the callback for the *custom* value generator plugin:

```php
<?php
// MYMODULE.module

use Drupal\Core\Entity\EntityInterface;

/**
 * Callback for calculating the start time of a generated Time Entry.
 * 
 * @param EntityInterface $entity
 * @return string
 */
function _MYMODULE_set_field_start(EntityInterface $entity) {
  // Do something with $entity and return the value to assign to the field.
}
```

### Execute the seed.

```
$ drush seeder:seed
```

### Extend this module.

This module defines 3 plugin types:

#### Seeder

The Seeder is responsible for fetching the data to use for the seed. Here is
a simple example of a new plugin you could create in your module. It loads
configuration from JSON files:

```php
<?php
// MYMODULE/src/Plugin/Seeder/JsonSeeder.php

namespace Drupal\MYMODULE\Plugin\Seeder;

use Drupal\seeder\AbstractSeeder;

/**
 * Seeder for data stored in json files.
 * 
 * @Seeder(
 *   id = "json_file",
 *   title = @Translation("JSON file"),
 *   description = @Translation("Store your info in JSON files.")
 * )
 */
class JsonSeeder extends AbstractSeeder {
  
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
  public function seed() {
    $data       = json_decode(file_get_contents($this->dataPath), TRUE);
    $pluginType = \Drupal::service('plugin.manager.entity_generator');
    $pluginName = $data['plugin']['name'];
    $plugin     = $pluginType->createInstance($pluginName, $data);
    
    $plugin->generate();
  }
}
```

#### EntityGenerator

The EntityGenerator is responsible for generating the entities. Here is an
example of an entity generator plugin that creates an entity for each of a 
corosponding entity:

```php
<?php
// MYMODULE/src/Plugin/EntityGenerator/CorrospondingGenerator.php

namespace Drupal\MYMODULE\Plugin\EntityGenerator;

use Drupal\seeder\AbstractEntityGenerator;

/**
 * Generator for corrosponding entities.
 *
 * @EntityGenerator(
 *   id = "corrosponding",
 *   title = @Translation("Corrosponding Generator"),
 *   description = @Translation("Create an entity for each of a corosponding entity.")
 * )
 */
class CorrospondingGenerator extends AbstractEntityGenerator {
  
  /**
   * Corrosponding entity type.
   * 
   * @var string
   */
  protected $corrospondingEntityType;
  
  /**
   * Corrosponding entity bundle.
   * 
   * @var string
   */
  protected $corrospondingBundle;
  
  /**
   * Conditions to use in querying entities.
   * 
   * @var array
   */
  protected $corrospondingConditions = [];
  
  /**
   * Minimum number of entities to generate for each corrosponding entities.
   * 
   * @var int
   */
  protected $min = 1;
  
  /**
   * Maximum number of entities to generate for each corrosponding entities.
   *
   * @var int
   */
  protected $max = 1;
  
  /**
   * The field definitions.
   * 
   * @var array
   */
  protected $fieldDefinitions = [];
  
  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct($configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    
    $corrosponding = $configuration['plugin']['data']['corrosponding'];
    $this->corrospondingEntityType = $corrosponding['entity_type'];
    $this->corrospondingBundle = $corrosponding['bundle'];
    
    if (isset($corrosponding['conditions'])) {
      $this->corrospondingConditions = $corrosponding['conditions'];
    }
    
    if (isset($corrosponding['min'])) {
      $this->min = $corrosponding['min'];
    }
    
    if (isset($corrosponding['max'])) {
      $this->max = $corrosponding['max'];
    }
    
    $fieldDefinitions = $configuration['plugin']['data']['fields'];
    foreach ($fieldDefinitions as $fieldName => $fieldDefinition) {
      $this->fieldDefinitions[$fieldName] = $fieldDefinition;
    }
  }
  
  /**
   * Get corrosponging entity ids.
   * 
   * @return int[]
   */
  private function getCorrospondingEntityIds() {
    $query = \Drupal::entityQuery($this->corrospondingEntityType);
    
    if ($this->bundle) {
      $query->condition('type', $this->bundle);
    }
    
    foreach ($this->corrospondingConditions as $condition) {
      $comparison = isset($condition[2]) ? $condition[2] : '=';
      $query->andConditionGroup($condition[0], $condition[1], $comparison);
    }
    
    return $query->execute();
  }
  
  /**
   * Figure out if we should seed this value based on the "fill" value.
   *
   * @param array $config
   * @return boolean
   */
  protected function hasValue($config) {
    $fill = isset($config['fill']) ? $config['fill'] : 100;
    return $fill >= rand(1, 100);
  }
  
  /**
   * {@inheritDoc}
   * @see AbstractEntityGenerator::generate()
   */
  public function generate() {
    $pluginType = \Drupal::service('plugin.manager.value_generator');
    $entityIds = $this->getCorrospondingEntityIds();

    foreach ($entityIds as $entityId) {
      $numEntities = rand($this->min, $this->max);
      for ($i = 0; $i < $numEntities; $i++) {
        $entity = $this->entityClass::create(['type' => $this->bundle]);
        
        foreach ($this->fieldDefinitions as $field => $definition) {
          if (!$this->hasValue($definition)) {
            continue;
          }
          
          $pluginName = $definition['plugin'];
          $configuration = [
            'config' => $definition['value'],
            'entity' => $entity,
            'corrosponding_entity_id' => $entityId,
          ];
          
          $plugin = $pluginType->createInstance($pluginName, $configuration);
          $entity->set($field, $plugin->generate());
        }
        
        $entity->save();
      }
    }
  }
}
```

This entity generator might be used to generate entities based on a YAML file
like this:

```yaml
entity_class: \Drupal\node\Entity\node
bundle: project
plugin: 
  name: corosponding
  data:
    corosponding:
      entity_type: node
      bundle: client
      conditions: []
    min: 1
    max: 5
    fields:
      title:
        plugin: random_from_list
        fill: 100
        value:
          - 'Make a website'
          - 'Update a website'
          - 'Website redesign'
      field_client:
      
        # This value generator plugin does not exist. We'll create it next.
        plugin: corrosponding_entity
        fill: 100
```

#### ValueGenerator

The value generator plugin is responsible for generating the value for a 
field. Above we referenced a non-existant plugin called corrosponding_entity.
Let's create that value generator. It will set the value of the field to the
corosponging entity.

```
<?php
// MYMODULE/src/Plugin/ValueGenerator/CorrospondingEntity.php

namespace Drupal\MYMODULE\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

class CorrospondingEntity extends AbstractValueGenerator {

  /**
   * The id of the corosponding entity.
   *
   * @var int
   */
  private $corrospondingEntityId;

  public function __construct($configuration, $plugin_id, $plugin_definition) {
    parent::_construct($configuration, $plugin_id, $plugin_definition);
    
    $this->corrospondingEntityId = $configuration['corrosponding_entity_id'];
  }
    
  public function generate() {
    return $this->corrospondingEntityId;
  }  
}
```
