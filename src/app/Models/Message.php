<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'receiver_id', 'exhibition_id', 'message', 'is_read'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }
}
