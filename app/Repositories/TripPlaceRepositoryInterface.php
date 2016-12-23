<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:19
 */

namespace App\Repositories;


interface TripPlaceRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function get($id);
    public function getByTripId($trip_id);
    public function getByPlaceId($place_id);
}