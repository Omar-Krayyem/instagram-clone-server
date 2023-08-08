<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function post_like(){
        return $this->belongsTo(Post::class,'post_id');
    }

    public function user_like(){
        return $this->belongsTo(User::class,'user_id');
    }
}
