<?php

namespace App\Repositories;

interface CityRepositoryInterface {
    public function getByPhrase($phrase);
    public function getById($id);
}