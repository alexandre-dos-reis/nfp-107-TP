<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * 
 * @property int $id
 * @property Carbon $name
 * @property array $days
 *
 * @package App\Models
 */
class Slot extends Model
{
	protected $table = 'slot';
	public $timestamps = false;

	protected $casts = [
		'days' => 'json'
	];

	protected $dates = [
		'name'
	];

	protected $fillable = [
		'name',
		'days'
	];
}
