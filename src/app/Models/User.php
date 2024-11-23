<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    // addressテーブルとの結合
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    // likeテーブルとの結合
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // purchaseテーブルとの結合
    public function purchaseItems()
    {
        return $this->belongsToMany(Exhibition::class, 'purchases', 'user_id', 'exhibition_id');
    }

    // salesテーブルとの結合
    public function sellItems()
    {
        return $this->belongsToMany(Exhibition::class, 'sales', 'user_id', 'exhibition_id');
    }

    // commentテーブルとの結合
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Exhibition::class, 'likes', 'user_id', 'exhibition_id');
    }
}
