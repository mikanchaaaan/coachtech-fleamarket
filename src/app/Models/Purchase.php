<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exhibition_id',
        'address_id',
    ];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
