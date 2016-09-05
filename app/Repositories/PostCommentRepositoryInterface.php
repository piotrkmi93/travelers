<?php

namespace App\Repositories;

interface PostCommentRepositoryInterface {
    public function create($post_id, $comment_id);
    public function delete($id);
    public function getById($id);
    public function getByCommentId($comment_id);
    public function getByPostId($post_id);
    public function count($post_id);
}