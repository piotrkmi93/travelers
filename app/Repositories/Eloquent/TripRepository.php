<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 13:53
 */

namespace App\Repositories\Eloquent;


use App\Repositories\TripRepositoryInterface;
use App\Trip;

class TripRepository implements TripRepositoryInterface
{
    private $model;

    /**
     * TripRepository constructor.
     * @param Trip $model
     */
    public function __construct(Trip $model)
    {
        $this -> model = $model;
    }

    /**
     * @param array $data
     * @return Trip|null
     */
    public function create(array $data)
    {
        $trip = $this -> model -> newInstance();
        return $this -> save($trip, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Trip|null
     */
    public function update($id, array $data)
    {
        $trip = $this -> get($id);
        return $this -> save($trip, $data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this -> get($id) -> delete();
    }

    /**
     * @param int $id
     * @return Trip|null
     */
    public function get($id)
    {
        return $this -> model -> find($id);
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function getByUserId($user_id)
    {
        return $this -> model -> whereUserId($user_id) -> get();
    }

    /**
     * @param Trip $trip
     * @param array $data
     * @return Trip|null
     */
    private function save(Trip $trip, array $data)
    {
        if(isset($data['user_id'])) $trip -> user_id = $data['user_id'];
        if(isset($data['name'])) $trip -> name = $data['name'];
        if(isset($data['description'])) $trip -> description = $data['description'];
        if(isset($data['start'])) $trip -> start = $data['start'];
        if(isset($data['end'])) $trip -> end = $data['end'];
        return $trip -> save() ? $trip : null;
    }
}