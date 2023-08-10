<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function follow(){
        return $this->belongsToMany(User::class,'followed_id');
    }

    public function isFollowing($user) {
        return $this->follow->contains($user);
    }
}
