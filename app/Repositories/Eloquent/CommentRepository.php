<?php

namespace App\Repositories\Eloquent;
use App\Comment;
use App\Repositories\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface {

    private $model;

    public function __construct(Comment $model){
        $this -> model = $model;
    }

    public function create($text, $author_user_id, $type){
        $comment = new Comment();
        $comment -> text = $text;
        $comment -> author_user_id = $author_user_id;
        $comment -> type = $type;
        $comment -> save();
        return $comment -> id;
    }

    public function delete($id){
        $comment = $this -> model -> find($id);
        return $comment -> delete();
    }

    public function getById($id){
        return $this -> model -> find($id);
    }
}