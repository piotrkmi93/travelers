<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:28
 */

namespace App\Repositories\Eloquent;


use App\Repositories\TripUserRepositoryInterface;
use App\TripUser;

class TripUserRepository implements TripUserRepositoryInterface
{
    private $model;

    /**
     * TripUserRepository constructor.
     * @param TripUser $model
     */
    public function __construct(TripUser $model)
    {
        $this -> model = $model;
    }

    /**
     * @param array $data
     * @return TripUser|null
     */
    public function create(array $data)
    {
        $tripUser = $this -> model -> newInstance();
        return $this -> save($tripUser, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return TripUser|null
     */
    public function update($id, array $data)
    {
        $tripUser = $this -> get($id);
        return $this -> save($tripUser, $data);
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete($id)
    {
        return $this -> get($id) -> delete();
    }

    /**
     * @param int $id
     * @return TripUser|null
     */
    public function get($id)
    {
        return $this -> model -> find($id);
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
     * @param TripUser $tripUser
     * @param array $data
     * @return TripUser|null
     */
    private function save(TripUser $tripUser, array $data)
    {
        if(isset($data['trip_id'])) $tripUser -> trip_id = $data['trip_id'];
        if(isset($data['user_id'])) $tripUser -> user_id = $data['user_id'];
        if(isset($data['status'])) $tripUser -> status = $data['status'];
        return $tripUser -> save() ? $tripUser : null;
    }
}