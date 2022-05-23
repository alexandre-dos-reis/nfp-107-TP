<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property string|null $comments
 * @property int $stock
 * @property string|null $image
 * @property int $price
 * @property int $promotion
 * @property int $idSection
 * 
 * @property Section $section
 * @property Collection|Associatedproduct[] $associatedproducts
 * @property Collection|Basketdetail[] $basketdetails
 * @property Collection|Pack[] $packs
 * @property Collection|Purchasedetail[] $purchasedetails
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'product';
	public $timestamps = false;

	 /**
     * The primary key associated with the table.
     *
     * @var int
     */
	protected $primaryKey = 'id';

	protected $casts = [
		'stock' => 'int',
		'price' => 'int',
		'promotion' => 'int',
		'idSection' => 'int'
	];

	protected $fillable = [
		'name',
		'comments',
		'stock',
		'image',
		'price',
		'promotion',
		'idSection'
	];

	public function section()
	{
		return $this->belongsTo(Section::class, 'idSection');
	}

	public function associatedproducts()
	{
		return $this->hasMany(Associatedproduct::class, 'idProduct');
	}

	public function basketdetails()
	{
		return $this->hasMany(Basketdetail::class, 'idProduct');
	}

	public function packs()
	{
		return $this->hasMany(Pack::class, 'idPack');
	}

	public function purchasedetails()
	{
		return $this->hasMany(Purchasedetail::class, 'idProduct');
	}
}
