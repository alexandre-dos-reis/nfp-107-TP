<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

	protected $searchable = [
        'columns' => [
            'Product.name' => 10,
            'users.email' => 5,
            'users.id' => 3,
        ]
    ];

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

	public static function searchFullText(string $searchedString)
	{
		$sql = "SELECT p.id, p.name, p.comments, p.promotion, p.image, p.price, p.updated_at, p.stock, s.id as section_id, s.name as section_name " . 
		"FROM product as p " . 
		"JOIN section as s ON s.id = p.idSection ".
		"WHERE MATCH (comments) AGAINST ( ? );";

		return DB::select($sql, [$searchedString]);
	}
}
