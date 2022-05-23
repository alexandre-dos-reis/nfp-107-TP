<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Basket
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $dateCreation
 * @property int $idUser
 * 
 * @property User $user
 * @property Collection|Basketdetail[] $basketdetails
 *
 * @package App\Models
 */
class Basket extends Model
{
	protected $table = 'basket';
	public $timestamps = false;

	protected $casts = [
		'idUser' => 'int'
	];

	protected $dates = [
		'dateCreation'
	];

	protected $fillable = [
		'name',
		'dateCreation',
		'idUser'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'idUser');
	}

	public function basketdetails()
	{
		return $this->hasMany(Basketdetail::class, 'idBasket');
	}
}
