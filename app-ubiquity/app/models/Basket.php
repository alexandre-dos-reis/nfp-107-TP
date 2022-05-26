<?php
namespace models;
/**
 * @table("name"=>"basket")
 */
class Basket{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"name","dbType"=>"varchar(60)")
	 * @validator("type"=>"length","constraints"=>["max"=>60,"notNull"=>true])
	 */
	private $name;

	/**
	 * @column("name"=>"dateCreation","dbType"=>"timestamp")
	 * @validator("type"=>"notNull")
	 */
	private $dateCreation;

	/**
	 * @oneToMany("mappedBy"=>"basket","className"=>"models\\Basketdetail")
	 */
	private $basketdetails;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;


	 public function __construct(){
		$this->basketdetails = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getName(){
		return $this->name;
	}


	public function setName($name){
		$this->name=$name;
	}


	public function getDateCreation(){
		return $this->dateCreation;
	}


	public function setDateCreation($dateCreation){
		$this->dateCreation=$dateCreation;
	}


	public function getBasketdetails(){
		return $this->basketdetails;
	}


	public function setBasketdetails($basketdetails){
		$this->basketdetails=$basketdetails;
	}


	 public function addToBasketdetails($basketdetail){
		$this->basketdetails[]=$basketdetail;
		$basketdetail->setBasket($this);
	}


	public function getUser(){
		return $this->user;
	}


	public function setUser($user){
		$this->user=$user;
	}


	 public function __toString(){
		return ($this->dateCreation??'no value').'';
	}

}