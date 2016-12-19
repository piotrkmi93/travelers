<?php

namespace App\Repositories\Eloquent;
use App\CommentLike;
use App\Repositories\CommentLikeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CommentLikeRepository implements CommentLikeRepositoryInterface {

    private $model;

    public function __construct(CommentLike $model){
        $this -> model = $model;
    }

    public function create($comment_id, $like_id){
        $commentLike = new CommentLike();
        $commentLike -> comment_id = $comment_id;
        $commentLike -> like_id = $like_id;
        $commentLike -> save();
        return $commentLike -> id;
    }

    public function delete($id){
        $commentLike = $this -> model -> find($id);
        return $commentLike -> delete();
    }

    public function getById($id){
        return $this -> model -> find($id);
    }

    public function exists($user_id, $comment_id){
        return count($this->find($user_id, $comment_id)) ? true : false;
    }

    public function count($comment_id){
        return $this -> model -> where('comment_id', '=', $comment_id) -> count();
    }

    public function find($user_id, $comment_id){
        return DB::select("SELECT a.id AS 'like_id', b.id AS 'comment_like_id' FROM `likes` a INNER JOIN `comment_likes` b ON a.id = b.like_id WHERE a.user_id = ". $user_id ." AND b.comment_id =". $comment_id);
    }

    public function getByCommentId($comment_id)
    {
        return $this -> model -> where('comment_id', '=', $comment_id) -> get();
    }


}