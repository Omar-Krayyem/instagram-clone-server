<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posts(){
        return $this->hasMany(Post::class, 'user_id');
    }

    public function user_likes(){
        return $this->hasMany(Like::class, 'user_id');
    }

    // public function followers(){
    //     return $this->hasMany(User::class, 'followed_id');
    // }

    // public function following(){
    //     return $this->hasMany(User::class, 'following_id');
    // }

    public function following() {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'following_id');
    }
    
    // users that follow this user
    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'followed_id');
    }
}
