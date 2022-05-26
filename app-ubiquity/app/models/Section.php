<?php
namespace models;
/**
 * @table("name"=>"section")
 */
class Section{
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
	 * @column("name"=>"description","nullable"=>true,"dbType"=>"text")
	 */
	private $description;

	/**
	 * @oneToMany("mappedBy"=>"section","className"=>"models\\Product")
	 */
	private $products;


	 public function __construct(){
		$this->products = [];
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


	public function getDescription(){
		return $this->description;
	}


	public function setDescription($description){
		$this->description=$description;
	}


	public function getProducts(){
		return $this->products;
	}


	public function setProducts($products){
		$this->products=$products;
	}


	 public function addToProducts($product){
		$this->products[]=$product;
		$product->setSection($this);
	}


	 public function __toString(){
		return ($this->name??'no value').'';
	}

}