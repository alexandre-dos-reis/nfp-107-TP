<?php

return array(
  '#namespace' => 'Ubiquity\\annotations\\items',
  '#uses' => array (
  'Annotation' => 'mindplay\\annotations\\Annotation',
  'BaseAnnotationTrait' => 'Ubiquity\\annotations\\BaseAnnotationTrait',
  'UArray' => 'Ubiquity\\utils\\base\\UArray',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\annotations\\items\\BaseAnnotation' => 
  array (
  ),
),
  'Ubiquity\\annotations\\items\\BaseAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'property'=>true, 'inherited'=>true)
  ),
);

