<?php
return array("#tableName"=>"purchasedetail","#primaryKeys"=>["idOrder"=>"idOrder","idProduct"=>"idProduct"],"#manyToOne"=>["product","purchase"],"#fieldNames"=>["idOrder"=>"idOrder","idProduct"=>"idProduct","quantity"=>"quantity","prepared"=>"prepared","product"=>"idProduct","purchase"=>"idOrder"],"#memberNames"=>["idOrder"=>"purchase","idProduct"=>"product","quantity"=>"quantity","prepared"=>"prepared"],"#fieldTypes"=>["idOrder"=>"int","idProduct"=>"int","quantity"=>"int","prepared"=>"tinyint(1)","product"=>"mixed","purchase"=>"mixed"],"#nullable"=>["idOrder","idProduct"],"#notSerializable"=>["product","purchase"],"#transformers"=>[],"#accessors"=>["idOrder"=>"setIdOrder","idProduct"=>"setIdProduct","quantity"=>"setQuantity","prepared"=>"setPrepared"],"#joinColumn"=>["product"=>["className"=>"models\\Product","name"=>"idProduct"],"purchase"=>["className"=>"models\\Purchase","name"=>"idOrder"]],"#invertedJoinColumn"=>["idProduct"=>["member"=>"product","className"=>"models\\Product"],"idOrder"=>["member"=>"purchase","className"=>"models\\Purchase"]]);
