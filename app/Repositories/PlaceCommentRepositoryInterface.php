<?php

namespace App\Repositories;

interface PlaceCommentRepositoryInterface {
    public function create($place_id, $comment_id);
    public function delete($id);
    public function getByPlaceId($place_id);
}