<?php

namespace App\Repositories\Eloquent;
use App\PlaceComment;
use App\Repositories\PlaceCommentRepositoryInterface;

class PlaceCommentRepository implements PlaceCommentRepositoryInterface {

    private $model;

    public function __construct(PlaceComment $model)
    {
        $this -> model = $model;
    }

    public function create($place_id, $comment_id)
    {
        $placeComment = new PlaceComment();
        $placeComment -> place_id = $place_id;
        $placeComment -> comment_id = $comment_id;
        return $placeComment -> save();
    }

    public function delete($id)
    {
        return $this -> model -> find($id) -> delete();
    }

    public function getByPlaceId($place_id)
    {
        return $this -> model -> where('place_id', '=', $place_id) -> get();
    }

    public function getByCommentId($comment_id)
    {
        return $this -> model -> whereCommentId($comment_id) -> first();
    }


}