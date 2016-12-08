<?php

namespace App\Repositories;

interface PlaceLikeRepositoryInterface {
    public function create($place_id, $like_id);
    public function delete($id);
    public function getAllByPlaceId($place_id);
    public function getByLikeId($like_id);
    public function count($place_id);
    public function exists($user_id, $place_id);
    public function find($user_id, $place_id);
}