<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Like;
use App\Models\Post;
use Exception;

class PostController extends Controller
{
    public function createPost(Request $request) {

        try{
            $user = Auth::user();

            $request->validate([
                'image' => 'required|string',
            ]);

            $base64Image = $request->image;
            echo $base64Image;
            $decodedImage = base64_decode($base64Image);

            $filename = 'post_' . time() . '.jpg';
            $path = Storage::disk('public')->put('post_images/' . $filename, $decodedImage);

            $post = Post::create([
                'user_id' => $user->id,
                'image_url' => 'post_images/' . $filename
            ]);

            $imageUrl = Storage::url('post_images/' . $filename);

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'post' => $post,
                'image_url' => $imageUrl
            ]);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }
    }

    public function getFollowingPosts() {
        try{
            $user = Auth::user();
            $followingPosts = [];

            foreach ($user->following as $followedUser) {
                $posts = $followedUser->posts()->with('likes')->with('user')->latest()->get();

                foreach($posts as $post) {
                    $post->liked_by_me = $post->likes->contains('user_id', $user->id);
                }

                $followingPosts = array_merge($followingPosts, $posts->toArray());
            }

            usort($followingPosts, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            
            return response()->json(['posts' => $followingPosts]);   
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }
    }

    public function toggleLike($postId) {
        try{
            $user = Auth::user();
            $post = Post::find($postId);
            $likeExists = $post->likes()->where('user_id', $user->id)->exists();

            if($likeExists) {
                $post->likes()->where('user_id', $user->id)->delete();
            } else {
                $like = new Like(['user_id' => $user->id]);
                $post->likes()->save($like);
            }

            return response()->json(['status' => 'success', 'message' => 'post has been liked/disliked successfully']);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        } 
    }

    function customResponse($data, $status = 'success', $code = 200){
        $response = ['status' => $status,'data' => $data];
        return response()->json($response,$code);
    }
}
