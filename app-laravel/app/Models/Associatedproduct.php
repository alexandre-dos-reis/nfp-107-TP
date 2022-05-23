<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Associatedproduct
 * 
 * @property int $idProduct
 * @property int $idAssoProduct
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class Associatedproduct extends Model
{
	protected $table = 'associatedproduct';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idProduct' => 'int',
		'idAssoProduct' => 'int'
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'idProduct');
	}
}
