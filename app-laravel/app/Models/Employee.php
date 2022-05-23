<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * 
 * @property Collection|Purchase[] $purchases
 *
 * @package App\Models
 */
class Employee extends Model
{
	protected $table = 'employee';
	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'password'
	];

	public function purchases()
	{
		return $this->hasMany(Purchase::class, 'idEmployee');
	}
}
