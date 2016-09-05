<?php

namespace App\Repositories;

interface LikeRepositoryInterface{
    public function create($user_id, $type);
    public function delete($id);
    public function getById($id);
}