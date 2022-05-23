<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase
 * 
 * @property int $id
 * @property Carbon $dateCreation
 * @property int $idUser
 * @property int|null $idEmployee
 * @property int $status
 * @property int $amount
 * @property int|null $toPay
 * @property int $itemsNumber
 * @property int $missingNumber
 * @property int|null $idTimeslot
 * 
 * @property User $user
 * @property Employee|null $employee
 * @property Timeslot|null $timeslot
 * @property Collection|Purchasedetail[] $purchasedetails
 *
 * @package App\Models
 */
class Purchase extends Model
{
	protected $table = 'purchase';
	public $timestamps = false;

	protected $casts = [
		'idUser' => 'int',
		'idEmployee' => 'int',
		'status' => 'int',
		'amount' => 'int',
		'toPay' => 'int',
		'itemsNumber' => 'int',
		'missingNumber' => 'int',
		'idTimeslot' => 'int'
	];

	protected $dates = [
		'dateCreation'
	];

	protected $fillable = [
		'dateCreation',
		'idUser',
		'idEmployee',
		'status',
		'amount',
		'toPay',
		'itemsNumber',
		'missingNumber',
		'idTimeslot'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'idUser');
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class, 'idEmployee');
	}

	public function timeslot()
	{
		return $this->belongsTo(Timeslot::class, 'idTimeslot');
	}

	public function purchasedetails()
	{
		return $this->hasMany(Purchasedetail::class, 'idOrder');
	}

	public const STATUS_CREATED = 0;
	public const STATUS_PREPARED = 1;
	public const STATUS_CANCELED = 2;

	public const STATUSES = [
		self::STATUS_CREATED => 'CREATED',
		self::STATUS_PREPARED => 'PREPARED',
		self::STATUS_CANCELED => 'CANCELED'
	];

	public function getStatusLabel(): ?string
	{
		return self::STATUSES[$this->status];
	}
}
