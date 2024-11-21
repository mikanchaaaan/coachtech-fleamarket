<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;

    // Categoriesテーブルとの結合
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products', 'exhibition_id', 'category_id');
    }

    // Purchaseテーブルとの結合
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'exhibition_id');
    }

    // Saleテーブルとの結合
    public function sales()
    {
        return $this->hasMany(Sale::class, 'exhibition_id');
    }

    protected $fillable = [
        'name',
        'image',
        'brand_name',
        'price',
        'condition',
        'description',
    ];
}
