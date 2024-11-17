<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function exhibitions()
    {
        return $this->belongsToMany(Exhibition::class, 'products', 'category_id', 'exhibition_id');
    }
}
