<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pack
 * 
 * @property int $idProduct
 * @property int $idPack
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class Pack extends Model
{
	protected $table = 'pack';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idProduct' => 'int',
		'idPack' => 'int'
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'idPack');
	}
}
