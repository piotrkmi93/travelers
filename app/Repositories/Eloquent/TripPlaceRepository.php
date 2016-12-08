<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:21
 */

namespace App\Repositories\Eloquent;


use App\Repositories\TripPlaceRepositoryInterface;
use App\TripPlace;

class TripPlaceRepository implements TripPlaceRepositoryInterface
{
    private $model;

    /**
     * TripPlaceRepository constructor.
     * @param TripPlace $model
     */
    public function __construct(TripPlace $model)
    {
        $this -> model = $model;
    }

    /**
     * @param array $data
     * @return TripPlace|null
     */
    public function create(array $data)
    {
        $tripPlace = $this -> model -> newInstance();
        return $this -> save($tripPlace, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return TripPlace|null
     */
    public function update($id, array $data)
    {
        $tripPlace = $this -> get($id);
        return $this -> save($tripPlace, $data);
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
     * @return TripPlace|null
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
     * @param TripPlace $tripPlace
     * @param array $data
     * @return TripPlace|null
     */
    private function save(TripPlace $tripPlace, array $data)
    {
        if(isset($data['trip_id'])) $tripPlace -> trip_id = $data['trip_id'];
        if(isset($data['place_id'])) $tripPlace -> place_id = $data['place_id'];
        if(isset($data['position'])) $tripPlace -> position = $data['position'];
        if(isset($data['start'])) $tripPlace -> start = $data['start'];
        if(isset($data['end'])) $tripPlace -> trip_endendd = $data['end'];
        return $tripPlace -> save() ? $tripPlace : null;
    }
}