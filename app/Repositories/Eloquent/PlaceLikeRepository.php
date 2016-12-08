<?php

namespace App\Repositories\Eloquent;
use App\PlaceLike;
use App\Repositories\PlaceLikeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PlaceLikeRepository implements PlaceLikeRepositoryInterface {

    private $model;

    public function __construct(PlaceLike $model){
        $this -> model = $model;
    }

    public function create($place_id, $like_id){
        $placeLike = new PlaceLike();
        $placeLike -> place_id = $place_id;
        $placeLike -> like_id = $like_id;
        return $placeLike -> save() ? $placeLike -> id : null;
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    public function getAllByPlaceId($place_id){
        return $this -> model -> where('place_id', '=', $place_id) -> get();
    }

    public function getByLikeId($like_id){
        return $this -> model -> where('like_id', '=', $like_id) -> first();
    }

    public function count($place_id){
        return $this -> model -> where('place_id', '=', $place_id) -> count();
    }

    public function exists($user_id, $place_id){
        return count($this->find($user_id, $place_id)) ? true : false;
    }

    public function find($user_id, $place_id){
        return DB::select("SELECT a.id AS 'like_id', b.id AS 'place_like_id' FROM `likes` a INNER JOIN `place_likes` b ON a.id = b.like_id WHERE a.user_id = ". $user_id ." AND b.place_id =". $place_id);
    }
}