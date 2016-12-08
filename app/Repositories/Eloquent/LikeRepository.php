<?php

namespace App\Repositories\Eloquent;
use App\Like;
use App\PostLike;
use App\Repositories\LikeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LikeRepository implements LikeRepositoryInterface{

    private $model;

    /**
     * LikeRepository constructor.
     * @param Like $model
     */
    public function __construct(Like $model){
        $this -> model = $model;
    }

    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    public function create($user_id, $type) {
        $like = new Like();
        $like -> user_id = $user_id;
        $like -> type = $type;
        $like -> save();

        return $like -> id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id) {
        return $this -> model -> find($id);
    }

    public function countUserLikes($user_id){
        return $this -> model -> where('user_id', '=', $user_id) -> count();
    }


}