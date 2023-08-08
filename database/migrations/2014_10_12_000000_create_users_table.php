<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
        });

        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->integer('followed_id');
            $table->integer('following_id');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->integer('user_id');
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('post_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
