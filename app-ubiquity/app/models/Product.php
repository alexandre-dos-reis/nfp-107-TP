<?php
namespace models;
/**
 * @table("name"=>"product")
 */
class Product{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"name","dbType"=>"varchar(255)")
	 * @validator("type"=>"length","constraints"=>["max"=>255,"notNull"=>true])
	 */
	private $name;

	/**
	 * @column("name"=>"comments","nullable"=>true,"dbType"=>"text")
	 */
	private $comments;

	/**
	 * @column("name"=>"stock","dbType"=>"int")
	 * @validator("type"=>"notNull")
	 */
	private $stock;

	/**
	 * @column("name"=>"image","nullable"=>true,"dbType"=>"text")
	 */
	private $image;

	/**
	 * @column("name"=>"price","dbType"=>"mediumint")
	 * @validator("type"=>"notNull")
	 */
	private $price;

	/**
	 * @column("name"=>"promotion","dbType"=>"mediumint")
	 * @validator("type"=>"notNull")
	 */
	private $promotion;

	/**
	 * @oneToMany("mappedBy"=>"product","className"=>"models\\Basketdetail")
	 */
	private $basketdetails;

	/**
	 * @oneToMany("mappedBy"=>"product","className"=>"models\\Purchasedetail")
	 */
	private $purchasedetails;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Section","name"=>"idSection")
	 */
	private $section;

	/**
	 * @manyToMany("targetEntity"=>"models\\Product","inversedBy"=>"associatedproducts")
	 * @joinTable("name"=>"associatedproduct","inverseJoinColumns"=>["name"=>"idAssoProduct","referencedColumnName"=>"id"])
	 */
	private $associatedproducts;

	/**
	 * @manyToMany("targetEntity"=>"models\\Product","inversedBy"=>"packs")
	 * @joinTable("name"=>"pack","joinColumns"=>["name"=>"idPack","referencedColumnName"=>"id"])
	 */
	private $packs;


	 public function __construct(){
		$this->basketdetails = [];
		$this->purchasedetails = [];
		$this->associatedproducts = [];
		$this->packs = [];
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


	public function getComments(){
		return $this->comments;
	}


	public function setComments($comments){
		$this->comments=$comments;
	}


	public function getStock(){
		return $this->stock;
	}


	public function setStock($stock){
		$this->stock=$stock;
	}


	public function getImage(){
		return $this->image;
	}


	public function setImage($image){
		$this->image=$image;
	}


	public function getPrice(){
		return $this->price;
	}


	public function setPrice($price){
		$this->price=$price;
	}


	public function getPromotion(){
		return $this->promotion;
	}


	public function setPromotion($promotion){
		$this->promotion=$promotion;
	}


	public function getBasketdetails(){
		return $this->basketdetails;
	}


	public function setBasketdetails($basketdetails){
		$this->basketdetails=$basketdetails;
	}


	 public function addToBasketdetails($basketdetail){
		$this->basketdetails[]=$basketdetail;
		$basketdetail->setProduct($this);
	}


	public function getPurchasedetails(){
		return $this->purchasedetails;
	}


	public function setPurchasedetails($purchasedetails){
		$this->purchasedetails=$purchasedetails;
	}


	 public function addToPurchasedetails($purchasedetail){
		$this->purchasedetails[]=$purchasedetail;
		$purchasedetail->setProduct($this);
	}


	public function getSection(){
		return $this->section;
	}


	public function setSection($section){
		$this->section=$section;
	}


	public function getAssociatedproducts(){
		return $this->associatedproducts;
	}


	public function setAssociatedproducts($associatedproducts){
		$this->associatedproducts=$associatedproducts;
	}


	 public function addAssociatedproduct($associatedproduct){
		$this->associatedproducts[]=$associatedproduct;
	}


	public function getPacks(){
		return $this->packs;
	}


	public function setPacks($packs){
		$this->packs=$packs;
	}


	 public function addPack($pack){
		$this->packs[]=$pack;
	}


	 public function __toString(){
		return ($this->promotion??'no value').'';
	}

}