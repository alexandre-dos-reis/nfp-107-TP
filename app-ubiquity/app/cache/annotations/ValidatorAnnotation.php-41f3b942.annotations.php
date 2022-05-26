<?php

return array(
  '#namespace' => 'Ubiquity\\annotations\\items',
  '#uses' => array (
  'ValidatorsManager' => 'Ubiquity\\contents\\validation\\ValidatorsManager',
  'UArray' => 'Ubiquity\\utils\\base\\UArray',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\annotations\\items\\ValidatorAnnotation' => 
  array (
  ),
),
  'Ubiquity\\annotations\\items\\ValidatorAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'property'=>true, 'inherited'=>true, 'multiple'=>true)
  ),
);

