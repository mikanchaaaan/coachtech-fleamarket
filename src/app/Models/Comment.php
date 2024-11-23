<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // userテーブルとの結合
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // exhibitionテーブルとの結合
    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }

    protected $fillable = [
        'exhibition_id',
        'user_id',
        'content',
    ];
}
