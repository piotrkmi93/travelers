<?php

namespace App\Repositories;

interface PlaceRepositoryInterface {
    public function create($name, $slug, $short_description, $long_description, $gallery_id, $phone, $address, $email, $latitude, $longitude, $place_type, $author_user_id, $city_id);
    public function edit($id, $name, $slug, $short_description, $long_description, $phone, $address, $email, $latitude, $longitude, $city_id);
    public function delete($id);
    public function getById($id);
    public function getBySlug($slug);
    public function getByAuthorId($author_user_id);
    public function getByCityId($city_id);
    public function getAll();
    public function getByPhrase($phrase);
    public function getByPhraseAndCityId($phrase, $city_id);
}