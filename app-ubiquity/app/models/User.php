<?php
namespace models;
/**
 * @table("name"=>"user")
 */
class User{
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
	 * @column("name"=>"email","dbType"=>"varchar(255)")
	 * @validator("type"=>"email","constraints"=>["notNull"=>true])
	 * @validator("type"=>"length","constraints"=>["max"=>255])
	 */
	private $email;

	/**
	 * @column("name"=>"password","dbType"=>"varchar(255)")
	 * @validator("type"=>"length","constraints"=>["max"=>255,"notNull"=>true])
	 * @transformer("name"=>"password")
	 */
	private $password;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Basket")
	 */
	private $baskets;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Purchase")
	 */
	private $purchases;


	 public function __construct(){
		$this->baskets = [];
		$this->purchases = [];
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


	public function getEmail(){
		return $this->email;
	}


	public function setEmail($email){
		$this->email=$email;
	}


	public function getPassword(){
		return $this->password;
	}


	public function setPassword($password){
		$this->password=$password;
	}


	public function getBaskets(){
		return $this->baskets;
	}


	public function setBaskets($baskets){
		$this->baskets=$baskets;
	}


	 public function addToBaskets($basket){
		$this->baskets[]=$basket;
		$basket->setUser($this);
	}


	public function getPurchases(){
		return $this->purchases;
	}


	public function setPurchases($purchases){
		$this->purchases=$purchases;
	}


	 public function addToPurchases($purchase){
		$this->purchases[]=$purchase;
		$purchase->setUser($this);
	}


	 public function __toString(){
		return ($this->email??'no value').'';
	}

}