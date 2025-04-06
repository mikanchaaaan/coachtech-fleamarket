<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'receiver_id', 'exhibition_id', 'transaction_id', 'rating'];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
