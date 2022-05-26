<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Product' => 'models\\Product',
  'DAO' => 'Ubiquity\\orm\\DAO',
),
  '#traitMethodOverrides' => array (
  'controllers\\ProductController' => 
  array (
  ),
),
  'controllers\\ProductController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\items\\router\\RouteAnnotation', "products", "name"=>"products.index")
  ),
  'controllers\\ProductController::detail' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\items\\router\\RouteAnnotation', "products/{id}", "name"=>"products.detail")
  ),
);

