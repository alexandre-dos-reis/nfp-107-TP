<?php
namespace models;
/**
 * @table("name"=>"basketdetail")
 */
class Basketdetail{
	/**
	 * @id()
	 * @column("name"=>"idBasket","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idBasket;

	/**
	 * @id()
	 * @column("name"=>"idProduct","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idProduct;

	/**
	 * @column("name"=>"quantity","dbType"=>"int")
	 * @validator("type"=>"notNull")
	 */
	private $quantity;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Basket","name"=>"idBasket")
	 */
	private $basket;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Product","name"=>"idProduct")
	 */
	private $product;


	public function getIdBasket(){
		return $this->idBasket;
	}


	public function setIdBasket($idBasket){
		$this->idBasket=$idBasket;
	}


	public function getIdProduct(){
		return $this->idProduct;
	}


	public function setIdProduct($idProduct){
		$this->idProduct=$idProduct;
	}


	public function getQuantity(){
		return $this->quantity;
	}


	public function setQuantity($quantity){
		$this->quantity=$quantity;
	}


	public function getBasket(){
		return $this->basket;
	}


	public function setBasket($basket){
		$this->basket=$basket;
	}


	public function getProduct(){
		return $this->product;
	}


	public function setProduct($product){
		$this->product=$product;
	}


	 public function __toString(){
		return ($this->quantity??'no value').'';
	}

}