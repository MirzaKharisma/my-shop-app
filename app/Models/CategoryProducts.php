<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products;

class CategoryProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_products';

    public $timestamps = true;

    protected $fillable=[
        'name'
    ];

    public function product(){
        return $this->hasMany(Products::class);
    }
}
