<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::get("/user", [AccountController::class, 'getUser']);
    Route::get("/toggle-follow/{userId}", [AccountController::class, "follow"]);
    Route::get('/user/followers', [AccountController::class, 'getFollowers']);
    Route::get('/user/following', [AccountController::class, 'getFollowing']);
    Route::get('/search-users/{searchItem}', [AccountController::class, 'searchUsers']);
    
    Route::post("/create-post", [PostController::class, 'createPost']);
    Route::get('/user/following/posts', [PostController::class, 'getFollowingPosts']);
    Route::get("/posts/{postId}/toggle-like", [PostController::class, 'toggleLike']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
