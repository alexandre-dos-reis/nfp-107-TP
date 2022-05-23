<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Basketdetail
 * 
 * @property int $idBasket
 * @property int $idProduct
 * @property int $quantity
 * 
 * @property Basket $basket
 * @property Product $product
 *
 * @package App\Models
 */
class Basketdetail extends Model
{
	protected $table = 'basketdetail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idBasket' => 'int',
		'idProduct' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'quantity'
	];

	public function basket()
	{
		return $this->belongsTo(Basket::class, 'idBasket');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'idProduct');
	}
}
