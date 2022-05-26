<?php

return array(
  '#namespace' => 'models',
  '#uses' => array (
),
  '#traitMethodOverrides' => array (
  'models\\Product' => 
  array (
  ),
),
  'models\\Product' => array(
    array('#name' => 'table', '#type' => 'Ubiquity\\annotations\\items\\TableAnnotation', "name"=>"product")
  ),
  'models\\Product::$id' => array(
    array('#name' => 'id', '#type' => 'Ubiquity\\annotations\\items\\IdAnnotation', ),
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"id","dbType"=>"int"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\items\\ValidatorAnnotation', "type"=>"id","constraints"=>["autoinc"=>true])
  ),
  'models\\Product::$name' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"name","dbType"=>"varchar(255)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\items\\ValidatorAnnotation', "type"=>"length","constraints"=>["max"=>255,"notNull"=>true])
  ),
  'models\\Product::$comments' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"comments","nullable"=>true,"dbType"=>"text")
  ),
  'models\\Product::$stock' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"stock","dbType"=>"int"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\items\\ValidatorAnnotation', "type"=>"notNull")
  ),
  'models\\Product::$image' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"image","nullable"=>true,"dbType"=>"text")
  ),
  'models\\Product::$price' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"price","dbType"=>"mediumint"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\items\\ValidatorAnnotation', "type"=>"notNull")
  ),
  'models\\Product::$promotion' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\items\\ColumnAnnotation', "name"=>"promotion","dbType"=>"mediumint"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\items\\ValidatorAnnotation', "type"=>"notNull")
  ),
  'models\\Product::$basketdetails' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\items\\OneToManyAnnotation', "mappedBy"=>"product","className"=>"models\\Basketdetail")
  ),
  'models\\Product::$purchasedetails' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\items\\OneToManyAnnotation', "mappedBy"=>"product","className"=>"models\\Purchasedetail")
  ),
  'models\\Product::$section' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\items\\ManyToOneAnnotation', ),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\items\\JoinColumnAnnotation', "className"=>"models\\Section","name"=>"idSection")
  ),
  'models\\Product::$associatedproducts' => array(
    array('#name' => 'manyToMany', '#type' => 'Ubiquity\\annotations\\items\\ManyToManyAnnotation', "targetEntity"=>"models\\Product","inversedBy"=>"associatedproducts"),
    array('#name' => 'joinTable', '#type' => 'Ubiquity\\annotations\\items\\JoinTableAnnotation', "name"=>"associatedproduct","inverseJoinColumns"=>["name"=>"idAssoProduct","referencedColumnName"=>"id"])
  ),
  'models\\Product::$packs' => array(
    array('#name' => 'manyToMany', '#type' => 'Ubiquity\\annotations\\items\\ManyToManyAnnotation', "targetEntity"=>"models\\Product","inversedBy"=>"packs"),
    array('#name' => 'joinTable', '#type' => 'Ubiquity\\annotations\\items\\JoinTableAnnotation', "name"=>"pack","joinColumns"=>["name"=>"idPack","referencedColumnName"=>"id"])
  ),
);

