<?php
namespace models;
/**
 * @table("name"=>"purchasedetail")
 */
class Purchasedetail{
	/**
	 * @id()
	 * @column("name"=>"idOrder","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idOrder;

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
	 * @column("name"=>"prepared","dbType"=>"tinyint(1)")
	 * @validator("type"=>"isBool","constraints"=>["notNull"=>true])
	 */
	private $prepared;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Product","name"=>"idProduct")
	 */
	private $product;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Purchase","name"=>"idOrder")
	 */
	private $purchase;


	public function getIdOrder(){
		return $this->idOrder;
	}


	public function setIdOrder($idOrder){
		$this->idOrder=$idOrder;
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


	public function getPrepared(){
		return $this->prepared;
	}


	public function setPrepared($prepared){
		$this->prepared=$prepared;
	}


	public function getProduct(){
		return $this->product;
	}


	public function setProduct($product){
		$this->product=$product;
	}


	public function getPurchase(){
		return $this->purchase;
	}


	public function setPurchase($purchase){
		$this->purchase=$purchase;
	}


	 public function __toString(){
		return ($this->prepared??'no value').'';
	}

}