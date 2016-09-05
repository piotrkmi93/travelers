<?php

namespace App\Repositories;

interface PostRepositoryInterface {
    public function create($text, $photo_id, $author_user_id, $type);
    public function edit($id, $text);
    public function delete($id);
    public function getById($id);
    public function getUserPosts($author_user_id, $offset);
    public function getUsersPosts($users, $offset);
}