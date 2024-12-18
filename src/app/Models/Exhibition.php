<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products', 'exhibition_id', 'category_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'exhibition_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'exhibition_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
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
