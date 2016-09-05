<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepository;
    private $photoRepository;
    private $cityRepository;

    public function __construct(UserRepositoryInterface $userRepository, PhotoRepositoryInterface $photoRepository, CityRepositoryInterface $cityRepository){
        $this -> userRepository = $userRepository;
        $this -> photoRepository = $photoRepository;
        $this -> cityRepository = $cityRepository;
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
        return redirect('user/' . Auth::user()->username);
    }

    public function changeBackground(Request $request) {
        if ( $request -> hasFile('background') ) {
            if (Auth::user()->background_photo_id != 0) $this -> photoRepository -> delete(Auth::user()->background_photo_id);

            $background_id = $this -> photoRepository -> add($request -> background, Auth::user()->gallery_id, 'background');
            $this -> userRepository -> changeBackground(Auth::user()->id, $background_id);
        }
        return redirect('user/' . Auth::user()->username);
    }

    public function getUserById(Request $request){
        $user = $this ->userRepository -> getById($request -> user_id);
        $return = array(
            'name' => $user -> first_name . ' ' . $user -> last_name,
            'city' => $this -> cityRepository -> getById($user -> city_id) -> name,
            'birthday' => $user -> birthday,
            'sex' => $user -> sex,
            'created' => Carbon::parse($user -> created_at) -> toDateString(),
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
}
