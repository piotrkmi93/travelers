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
     * @param string $slug
     * @return Trip|null
     */
    public function getBySlug($slug)
    {
        return $this -> model -> whereSlug($slug) -> first();
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
        if(isset($data['slug'])) $trip -> slug = $data['slug'];
        if(isset($data['description'])) $trip -> description = $data['description'];
        if(isset($data['start_time'])) $trip -> start_time = $data['start_time'];
        if(isset($data['start_address'])) $trip -> start_address = $data['start_address'];
        if(isset($data['start_latitude'])) $trip -> start_latitude = $data['start_latitude'];
        if(isset($data['start_longitude'])) $trip -> start_longitude = $data['start_longitude'];
        if(isset($data['end_time'])) $trip -> end_time = $data['end_time'];
        if(isset($data['end_address'])) $trip -> end_address = $data['end_address'];
        if(isset($data['end_latitude'])) $trip -> end_latitude = $data['end_latitude'];
        if(isset($data['end_longitude'])) $trip -> end_longitude = $data['end_longitude'];
        return $trip -> save() ? $trip : null;
    }
}