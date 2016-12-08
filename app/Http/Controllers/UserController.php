<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepository;
    private $photoRepository;
    private $cityRepository;
    private $postRepository;
    private $commentRepository;
    private $likeRepository;

    public function __construct(UserRepositoryInterface $userRepository,
                                PhotoRepositoryInterface $photoRepository,
                                CityRepositoryInterface $cityRepository,
                                PostRepositoryInterface $postRepository,
                                CommentRepositoryInterface $commentRepository,
                                LikeRepositoryInterface $likeRepository){
        $this -> userRepository = $userRepository;
        $this -> photoRepository = $photoRepository;
        $this -> cityRepository = $cityRepository;
        $this -> postRepository = $postRepository;
        $this -> commentRepository = $commentRepository;
        $this -> likeRepository = $likeRepository;
    }

    public function index(Request $request){
        return view('user', array(
            'user' => $this -> userRepository -> getByUsername( $request -> username )
        ));
    }

    public function changeAvatar(Request $request) {
        if ( $request -> hasFile('avatar') ) {
            if (Auth::user()->avatar_photo_id != 0) $this -> photoRepository -> delete(Auth::user()->avatar_photo_id);

            $avatar_id = $this -> photoRepository -> add($request -> avatar, Auth::user()->gallery_id, 'avatar');
            $this -> userRepository -> changeAvatar(Auth::user()->id, $avatar_id);
        }
        return redirect('user/' . Auth::user()->username . '#/board');
    }

    public function changeBackground(Request $request) {
//        dd(Auth::user()->background_photo_id);
        if ( $request -> hasFile('background') ) {
            if (Auth::user()->background_photo_id != 0) $this -> photoRepository -> delete(Auth::user()->background_photo_id);

            $background_id = $this -> photoRepository -> add($request -> background, Auth::user()->gallery_id, 'background');
            $this -> userRepository -> changeBackground(Auth::user()->id, $background_id);
        }
        return redirect('user/' . Auth::user()->username . '#/board');
    }

    public function getUserById(Request $request){
        $user = $this ->userRepository -> getById($request -> user_id);
        $return = array(
            'name' => $user -> first_name . ' ' . $user -> last_name,
            'city' => $this -> cityRepository -> getById($user -> city_id) -> name,
            'birthday' => $user -> birthday,
            'sex' => $user -> sex,
            'created' => Carbon::parse($user -> created_at) -> toDateString(),

            'posts' => $this -> postRepository -> countUserPosts($user->id),
            'comments' => $this -> commentRepository -> countUserComments($user->id),
            'likes' => $this -> likeRepository -> countUserLikes($user->id),
        );
         return response() -> json($return);
    }

    public function getUserBasicsById(Request $request){
        return response() -> json( $this -> userRepository -> getUserBasicsById($request->user_id) );
    }

    public function extendActiveTimestamp(Request $request){
        return response() -> json($this -> userRepository -> extendActiveTimestamp($request->user_id));
    }

    public function isUserActive(Request $request){
        return response() -> json($this -> userRepository -> isActive($request->user_id));
    }

    public function areUsersActive(Request $request){
        $ids = $request -> ids;
        $users = array();
        foreach ($ids as $id) {
            array_push($users, array(
                'id' => $id,
                'is_active' => $this -> userRepository -> isActive($id),
            ));
        }
        return response() -> json($users);
    }

    public function getGallery(Request $request){
        $photos = $this -> photoRepository -> getUserPhotos($this -> userRepository -> getById($request -> user_id)) -> toArray();

        foreach($photos as &$photo){
            $photo['img'] = asset($photo['image_url']);
            $photo['thumb'] = asset($photo['thumb_url']);
            unset($photo['image_url']);
            unset($photo['thumb_url']);
            unset($photo['id']);
            unset($photo['gallery_id']);
        }

        return response() -> json(array(
            'photos' => $photos,
        ));
    }
}
