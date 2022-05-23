<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Section extends Model
{
	protected $table = 'section';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

	public function products()
	{
		return $this->hasMany(Product::class, 'idSection');
	}
}
