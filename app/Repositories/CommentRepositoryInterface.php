<?php

namespace App\Repositories;

interface CommentRepositoryInterface {
    public function create($text, $author_user_id, $type);
    public function delete($id);
    public function getById($id);
}