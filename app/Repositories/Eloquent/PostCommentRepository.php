<?php

namespace App\Repositories\Eloquent;
use App\PostComment;
use App\Repositories\PostCommentRepositoryInterface;

class PostCommentRepository implements PostCommentRepositoryInterface {

    private $model;

    public function __construct(PostComment $model){
        $this -> model = $model;
    }

    public function create($post_id, $comment_id){
        $postComment = new PostComment();
        $postComment -> post_id = $post_id;
        $postComment -> comment_id = $comment_id;
        $postComment -> save();
        return $postComment -> id;
    }

    public function delete($id){
        $postCommnet = $this -> model -> find($id);
        return $postCommnet -> delete();
    }

    public function getById($id){
        return $this -> model -> find($id);
    }

    public function getByCommentId($comment_id){
        return $this -> model -> where('comment_id', '=', $comment_id) -> first();
    }

    public function getByPostId($post_id){
        return $this -> model -> where('post_id', '=', $post_id) -> get();
    }

    public function count($post_id){
        return $this -> getByPostId($post_id) -> count();
    }
}