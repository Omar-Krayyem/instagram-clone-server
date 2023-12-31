<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follower;
use App\Models\User;
use Exception;
use Psy\Readline\Hoa\Console;

class AccountController extends Controller
{
    public function getUser() {
        try{    
            $user = Auth::user();
            return $this->customResponse($user);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }
    }
    
    public function deleteFollowing($userId){
        try{
            $user = Auth::user();
            $ufollow = Follower::where('followed_id', $userId)->where('following_id', $user->id)->first()->delete();
            return $this->customResponse($ufollow , 'unfollow this user');
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }
    }

    public function getFollowing() {
        try{
            $user = Auth::user();
            
            $followings = $user->followers;

            return $this->customResponse($followings);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }    
    }

    public function follow($userId) {
        try{    
            $user = Auth::user();
            $isFollowed = Follower::where('followed_id', $user->id)->where('following_id', $userId)->first();
            if($isFollowed) {
                $isFollowed->delete();
                return $this->customResponse($isFollowed , 'unfollow this user');
            } else {
                $follow = Follower::create([
                    'follower_id' => $user->id,
                    'following_id' => $userId,
                ]);
                return $this->customResponse($follow , 'Follow this user');
            }
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }
    }

    public function getFollowers() {
        try{
            $user = Auth::user();
            $followers = $user->followers;

            return $this->customResponse($followers);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        }        
    }

    public function searchUsers($searchItem) {
        try{
            $main_user = Auth::user();

            $users = User::where('username', 'LIKE', "%$searchItem%")->get();

            return $this->customResponse($users);
        }catch(Exception $e){
            return self::customResponse($e->getMessage(),'error',500);
        } 
    }

    function customResponse($data, $status = 'success', $code = 200){
        $response = ['status' => $status,'data' => $data];
        return response()->json($response,$code);
    }
}
