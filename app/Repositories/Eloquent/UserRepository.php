<?php

namespace App\Repositories\Eloquent;
use App\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;


class UserRepository implements UserRepositoryInterface {

    private $model;


    public function __construct(User $model){
        $this -> model = $model;
    }

    public function isExistUsername($username){
        return $this -> model -> where('username', 'like', $username.'%') -> count();
    }

    public function generateUniqueUsername($email) {
        $username = substr($email, 0, strpos($email, '@'));
        if ( $this->isExistUsername($username) > 0 )  $username .= $this->isExistUsername($username);
        return $username;
    }

    public function getByUsername($username) {
        return $this -> model -> where('username', '=', $username) -> first();
    }

    public function changeAvatar($id, $image_id) {
        $user = $this -> model -> find($id);
        $user -> avatar_photo_id = $image_id;
        return $user -> save();
    }

    public function changeBackground($id, $image_id) {
        $user = $this -> model -> find($id);
        $user -> background_photo_id = $image_id;
        return $user -> save();
    }

    public function getById($id) {
        return $this -> model -> find($id);
    }

    public function getUserBasicsById($id){
        $user = $this -> model -> find($id);
        return array(
            'id' => $user -> id,
            'name' => $user -> first_name . ' ' . $user -> last_name,
            'link' => asset('user/' . $user -> username),
            'avatar' => asset( getThumb( $user -> avatar_photo_id ) ),
            'is_active' => $this -> isActive($id),
        );
    }

    public function extendActiveTimestamp($id){
        $user = $this -> model -> find($id);
        $user -> active_to = Carbon::now()->addMinute(5)->addSecond(); //(new Carbon('now'))->addMinute(5)->timestamp;
        return $user -> save();
    }

    public function isActive($id){
        return $this->model->find($id)->active_to > Carbon::now();
    }
}