<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timeslot
 * 
 * @property int $id
 * @property Carbon $slotDate
 * @property bool $full
 * @property bool $expired
 * 
 * @property Collection|Purchase[] $purchases
 *
 * @package App\Models
 */
class Timeslot extends Model
{
	protected $table = 'timeslot';
	public $timestamps = false;

	protected $casts = [
		'full' => 'bool',
		'expired' => 'bool'
	];

	protected $dates = [
		'slotDate'
	];

	protected $fillable = [
		'slotDate',
		'full',
		'expired'
	];

	public function purchases()
	{
		return $this->hasMany(Purchase::class, 'idTimeslot');
	}
}
