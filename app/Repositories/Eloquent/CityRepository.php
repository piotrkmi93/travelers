<?php

namespace App\Repositories\Eloquent;
use App\City;
use App\Repositories\CityRepositoryInterface;


class CityRepository implements CityRepositoryInterface {

    private $model;

    public function getByPhrase($phrase) {
        return $this -> model -> where('name', 'like', $phrase.'%') -> limit(50) -> get();
    }

    public function __construct(City $model){
        $this -> model = $model;
    }

    public function getById($id) {
        return $this -> model -> find($id);
    }
}