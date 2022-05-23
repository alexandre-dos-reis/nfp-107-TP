<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchasedetail
 * 
 * @property int $idOrder
 * @property int $idProduct
 * @property int $quantity
 * @property bool $prepared
 * 
 * @property Purchase $purchase
 * @property Product $product
 *
 * @package App\Models
 */
class Purchasedetail extends Model
{
	protected $table = 'purchasedetail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idOrder' => 'int',
		'idProduct' => 'int',
		'quantity' => 'int',
		'prepared' => 'bool'
	];

	protected $fillable = [
		'quantity',
		'prepared'
	];

	public function purchase()
	{
		return $this->belongsTo(Purchase::class, 'idOrder');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'idProduct');
	}
}
