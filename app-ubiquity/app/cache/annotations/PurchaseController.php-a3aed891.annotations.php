<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Purchase' => 'models\\Purchase',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'FlashMessage' => 'Ubiquity\\utils\\flash\\FlashMessage',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
),
  '#traitMethodOverrides' => array (
  'controllers\\PurchaseController' => 
  array (
  ),
),
  'controllers\\PurchaseController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\items\\router\\RouteAnnotation', "/purchases", "name"=>"purchases.index")
  ),
  'controllers\\PurchaseController::detail' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\items\\router\\RouteAnnotation', "/purchases/{id}", "name"=>"purchases.detail")
  ),
  'controllers\\PurchaseController::updateStatus' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\items\\router\\RouteAnnotation', "/purchases/updateStatus/{id}", "name"=>"purchases.update.status", "methods"=>["post"])
  ),
);

