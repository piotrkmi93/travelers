<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:42
 */

namespace App\Repositories\Eloquent;


use App\Repositories\TripCommentRepositoryInterface;
use App\TripComment;

class TripCommentRepository implements TripCommentRepositoryInterface
{
    private $model;

    /**
     * TripCommentRepository constructor.
     * @param TripComment $model
     */
    public function __construct(TripComment $model)
    {
        $this -> model = $model;
    }

    /**
     * @param int $trip_id
     * @param int $comment_id
     * @return null|TripComment
     */
    public function create($trip_id, $comment_id)
    {
        $tripComment = $this -> model -> newInstance();
        $tripComment -> trip_id = $trip_id;
        $tripComment -> comment_id = $comment_id;
        return $tripComment -> save() ? $tripComment -> id : null;
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete($id)
    {
        return $this -> model -> find($id) -> delete();
    }

    /**
     * @param int $trip_id
     * @return array
     */
    public function getByTripId($trip_id)
    {
        return $this -> model -> whereTripId($trip_id) -> get();
    }

    /**
     * @param int $comment_id
     * @return TripComment|null
     */
    public function getByCommentId($comment_id)
    {
        return $this -> model -> whereCommentId($comment_id) -> first();
    }


}