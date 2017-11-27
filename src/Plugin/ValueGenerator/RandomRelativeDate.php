<?php

namespace Drupal\seeder\Plugin\ValueGenerator;

use Drupal\seeder\AbstractValueGenerator;

/**
 * Value generator for random relative dates.
 * 
 * @ValueGenerator(
 *   id = "random_relative_date",
 *   title = @Translation("Random relative date"),
 *   description = @Translation("Random relative date.")
 * )
 */
class RandomRelativeDate extends AbstractValueGenerator {
  public function generate() {
    $min = strtotime($this->value['min']);
    $max = strtotime($this->value['max']);
    $return = rand($min, $max);
    
    echo "Min for {$this->value['min']} is $min\n";
    echo "Max for {$this->value['max']} is $max\n";
    echo "Returning $return\n";    
    
    return $return;
  }
}
