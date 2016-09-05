<?php

namespace App\Repositories;

interface CommentLikeRepositoryInterface {
    public function create($comment_id, $like_id);
    public function delete($id);
    public function getById($id);
    public function count($comment_id);
    public function exists($user_id, $comment_id);
    public function find($user_id, $comment_id);
}