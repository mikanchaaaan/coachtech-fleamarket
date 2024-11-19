<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;

    // カテゴリーの結合
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products', 'exhibition_id', 'category_id');
    }

    // 商品と購入のリレーション
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'exhibition_id');
    }
}
