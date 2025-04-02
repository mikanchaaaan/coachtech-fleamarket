<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use Billable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function purchaseItems()
    {
        return $this->belongsToMany(Exhibition::class, 'purchases', 'user_id', 'exhibition_id');
    }

    public function sellItems()
    {
        return $this->belongsToMany(Exhibition::class, 'sales', 'user_id', 'exhibition_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Exhibition::class, 'likes', 'user_id', 'exhibition_id');
    }

    // 追加要件用に追記
    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating'); // 平均評価を算出
    }

    public function transactionItems()
    {
        return $this->belongsToMany(Exhibition::class, 'transactions', 'receiver_id', 'exhibition_id')
            ->orWhere('transactions.seller_id', $this->id);
    }
}
