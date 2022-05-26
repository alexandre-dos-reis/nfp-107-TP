<?php

return array(
  '#namespace' => 'Ubiquity\\annotations\\items\\router',
  '#uses' => array (
  'BaseAnnotation' => 'Ubiquity\\annotations\\items\\BaseAnnotation',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\annotations\\items\\router\\RouteAnnotation' => 
  array (
  ),
),
  'Ubiquity\\annotations\\items\\router\\RouteAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'method'=>true,'class'=>true,'multiple'=>true, 'inherited'=>true)
  ),
);

