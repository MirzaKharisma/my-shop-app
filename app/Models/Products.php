<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CategoryProducts;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    public $timestamps = true;

    protected $fillable=[
        'product_category_id',
        'name',
        'price',
        'image'
    ];

    public function category(){
        return $this->hasOne(CategoryProducts::class, 'id', 'product_category_id');
    }
}
