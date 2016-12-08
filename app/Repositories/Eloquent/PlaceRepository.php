<?php

namespace App\Repositories\Eloquent;
use App\Place;
use App\Repositories\PlaceRepositoryInterface;

class PlaceRepository implements PlaceRepositoryInterface {

    /**
     * @var Place
     */
    private $model;

    /**
     * PlaceRepository constructor.
     * @param Place $model
     */
    public function __construct(Place $model){
        $this -> model = $model;
    }

    /**
     * @param $name
     * @param $short_description
     * @param $long_description
     * @param $gallery_id
     * @param $phone
     * @param $address
     * @param $email
     * @param $latitude
     * @param $longitude
     * @param $place_type
     * @param $author_user_id
     * @return bool
     */
    public function create($name, $slug, $short_description, $long_description, $gallery_id, $phone, $address, $email, $latitude, $longitude, $place_type, $author_user_id){
        $place = new Place();
        $place -> name = $name;
        $place -> slug = $slug;
        $place -> short_description = $short_description;
        $place -> long_description = $long_description;
        $place -> gallery_id = $gallery_id;
        $place -> phone = $phone;
        $place -> address = $address;
        $place -> email = $email;
        $place -> latitude = $latitude;
        $place -> longitude = $longitude;
        $place -> place_type = $place_type;
        $place -> author_user_id = $author_user_id;
        return $place -> save() ? $place : null;
    }

    /**
     * @param $id
     * @param $name
     * @param $short_description
     * @param $long_description
     * @param $phone
     * @param $address
     * @param $email
     * @param $latitude
     * @param $longitude
     * @return mixed
     */
    public function edit($id, $name, $slug, $short_description, $long_description, $phone, $address, $email, $latitude, $longitude){
        $place = $this -> model -> find($id);
        $place -> name = $name;
        $place -> slug = $slug;
        $place -> short_description = $short_description;
        $place -> long_description = $long_description;
        $place -> phone = $phone;
        $place -> address = $address;
        $place -> email = $email;
        $place -> latitude = $latitude;
        $place -> longitude = $longitude;
        return $place -> save() ? $place : null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this -> model -> find($id);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getBySlug($slug){
        return $this -> model -> where('slug', '=', $slug) -> first();
    }

    /**
     * @param $author_user_id
     * @return mixed
     */
    public function getByAuthorId($author_user_id){
        return $this -> model -> select('slug', 'name', 'short_description', 'place_type') -> where('author_user_id', '=', $author_user_id) -> get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(){
        return $this -> model -> all();
    }

}