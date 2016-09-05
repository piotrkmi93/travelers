<?php
namespace App\Repositories\Eloquent;
use App\PostLike;
use App\Repositories\PostLikeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostLikeRepository implements PostLikeRepositoryInterface  {

    private $model;

    public function __construct(PostLike $model){
        $this -> model = $model;
    }

    public function create($post_id, $like_id){
        $postLike = new PostLike();
        $postLike -> post_id = $post_id;
        $postLike -> like_id = $like_id;
        $postLike -> save();
        return $postLike -> id;
    }

    public function delete($id){
        $postLike = $this -> model -> find($id) -> delete();
    }

    public function getAllByPostId($post_id){
        return $this -> model -> where('post_id', '=', $post_id) -> get();
    }

    public function getByLikeId($like_id){
        return $this -> model -> where('like_id', '=', $like_id) -> first();
    }

    public function count($post_id){
        return $this -> model -> where('post_id', '=', $post_id) -> count();
    }

    public function exists($user_id, $post_id){
        return count($this->find($user_id, $post_id)) ? true : false;
    }

    public function find($user_id, $post_id){
        return DB::select("SELECT a.id AS 'like_id', b.id AS 'post_like_id' FROM `likes` a INNER JOIN `post_likes` b ON a.id = b.like_id WHERE a.user_id = ". $user_id ." AND b.post_id =". $post_id);
    }
}