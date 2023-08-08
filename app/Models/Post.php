<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function post(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function post_likes(){
        return $this->hasMany(Like::class, 'post_id');
    }
}
