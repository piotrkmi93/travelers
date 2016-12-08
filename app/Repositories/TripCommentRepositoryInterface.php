<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:41
 */

namespace App\Repositories;


interface TripCommentRepositoryInterface
{
    public function create($trip_id, $comment_id);
    public function delete($id);
    public function getByTripId($trip_id);
}