<?php
namespace App\Repositories;

interface PostLikeRepositoryInterface {
    public function create($post_id, $like_id);
    public function delete($id);
    public function getAllByPostId($post_id);
    public function getByLikeId($like_id);
    public function count($post_id);
    public function exists($user_id, $post_id);
    public function find($user_id, $post_id);
}