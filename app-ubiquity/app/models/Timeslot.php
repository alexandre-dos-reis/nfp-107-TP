<?php
namespace models;
/**
 * @table("name"=>"timeslot")
 */
class Timeslot{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"slotDate","dbType"=>"datetime")
	 * @validator("type"=>"type","constraints"=>["ref"=>"dateTime","notNull"=>true])
	 * @transformer("name"=>"datetime")
	 */
	private $slotDate;

	/**
	 * @column("name"=>"full","dbType"=>"tinyint(1)")
	 * @validator("type"=>"isBool","constraints"=>["notNull"=>true])
	 */
	private $full;

	/**
	 * @column("name"=>"expired","dbType"=>"tinyint(1)")
	 * @validator("type"=>"isBool","constraints"=>["notNull"=>true])
	 */
	private $expired;

	/**
	 * @oneToMany("mappedBy"=>"timeslot","className"=>"models\\Purchase")
	 */
	private $purchases;


	 public function __construct(){
		$this->purchases = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getSlotDate(){
		return $this->slotDate;
	}


	public function setSlotDate($slotDate){
		$this->slotDate=$slotDate;
	}


	public function getFull(){
		return $this->full;
	}


	public function setFull($full){
		$this->full=$full;
	}


	public function getExpired(){
		return $this->expired;
	}


	public function setExpired($expired){
		$this->expired=$expired;
	}


	public function getPurchases(){
		return $this->purchases;
	}


	public function setPurchases($purchases){
		$this->purchases=$purchases;
	}


	 public function addToPurchases($purchase){
		$this->purchases[]=$purchase;
		$purchase->setTimeslot($this);
	}


	 public function __toString(){
		return ($this->expired??'no value').'';
	}

}