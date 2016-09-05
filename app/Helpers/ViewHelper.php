<?php

if (!function_exists('isCurrentUser')){
    function isCurrentUser($id) {
        return Auth::user()->id == $id;
    }
}

if (!function_exists('getImage')){
    function getImage($id){
        if($id) return \App\Photo::find($id) -> image_url;
        else return 'images/avatar_min_' . Auth::user()->sex . '.png';
    }
}

if (!function_exists('getThumb')){
    function getThumb($id){
        if($id) return \App\Photo::find($id) -> thumb_url;
        else return 'images/avatar_min_' . Auth::user()->sex . '.png';
    }
}

if (!function_exists('getCity')){
    function getCity($id){
        return \App\City::find($id) -> name;
    }
}

if (!function_exists('isActive')){
    function isActive($user){
        return $user -> active_to > \Carbon\Carbon::now();
    }
}