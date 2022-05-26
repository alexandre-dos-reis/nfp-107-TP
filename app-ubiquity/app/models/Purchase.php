<?php

namespace models;

/**
 * @table("name"=>"purchase")
 */
class Purchase
{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"dateCreation","dbType"=>"timestamp")
	 * @validator("type"=>"notNull")
	 */
	private $dateCreation;

	/**
	 * @column("name"=>"status","dbType"=>"tinyint")
	 * @validator("type"=>"notNull")
	 */
	private $status;

	/**
	 * @column("name"=>"amount","dbType"=>"mediumint")
	 * @validator("type"=>"notNull")
	 */
	private $amount;

	/**
	 * @column("name"=>"toPay","nullable"=>true,"dbType"=>"mediumint")
	 */
	private $toPay;

	/**
	 * @column("name"=>"itemsNumber","dbType"=>"int")
	 * @validator("type"=>"notNull")
	 */
	private $itemsNumber;

	/**
	 * @column("name"=>"missingNumber","dbType"=>"int")
	 * @validator("type"=>"notNull")
	 */
	private $missingNumber;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Employee","name"=>"idEmployee","nullable"=>true)
	 */
	private $employee;

	/**
	 * @oneToMany("mappedBy"=>"purchase","className"=>"models\\Purchasedetail")
	 */
	private $purchasedetails;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Timeslot","name"=>"idTimeslot","nullable"=>true)
	 */
	private $timeslot;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	public const STATUS_CREATED = 0;
	public const STATUS_PREPARED = 1;
	public const STATUS_CANCELED = 2;

	public const STATUSES = [
		self::STATUS_CREATED => 'CREATED',
		self::STATUS_PREPARED => 'PREPARED',
		self::STATUS_CANCELED => 'CANCELED'
	];


	public function __construct()
	{
		$this->purchasedetails = [];
	}


	public function getId()
	{
		return $this->id;
	}


	public function setId($id)
	{
		$this->id = $id;
	}


	public function getDateCreation()
	{
		return $this->dateCreation;
	}


	public function setDateCreation($dateCreation)
	{
		$this->dateCreation = $dateCreation;
	}


	public function getStatus()
	{
		return $this->status;
	}

	public function getStatusLabel()
	{
		return self::STATUSES[$this->status];
	}

	public function setStatus(int $status)
	{
		if (array_key_exists($status, self::STATUSES)) {
			$this->status = $status;
		} else {
			throw new \Exception("This status doesn't exists !");
		}
	}


	public function getAmount()
	{
		return $this->amount;
	}


	public function setAmount($amount)
	{
		$this->amount = $amount;
	}


	public function getToPay()
	{
		return $this->toPay;
	}


	public function setToPay($toPay)
	{
		$this->toPay = $toPay;
	}


	public function getItemsNumber()
	{
		return $this->itemsNumber;
	}


	public function setItemsNumber($itemsNumber)
	{
		$this->itemsNumber = $itemsNumber;
	}


	public function getMissingNumber()
	{
		return $this->missingNumber;
	}


	public function setMissingNumber($missingNumber)
	{
		$this->missingNumber = $missingNumber;
	}


	public function getEmployee()
	{
		return $this->employee;
	}


	public function setEmployee($employee)
	{
		$this->employee = $employee;
	}


	public function getPurchasedetails()
	{
		return $this->purchasedetails;
	}


	public function setPurchasedetails($purchasedetails)
	{
		$this->purchasedetails = $purchasedetails;
	}


	public function addToPurchasedetails($purchasedetail)
	{
		$this->purchasedetails[] = $purchasedetail;
		$purchasedetail->setPurchase($this);
	}


	public function getTimeslot()
	{
		return $this->timeslot;
	}


	public function setTimeslot($timeslot)
	{
		$this->timeslot = $timeslot;
	}


	public function getUser()
	{
		return $this->user;
	}


	public function setUser($user)
	{
		$this->user = $user;
	}


	public function __toString()
	{
		return ($this->missingNumber ?? 'no value') . '';
	}
}
