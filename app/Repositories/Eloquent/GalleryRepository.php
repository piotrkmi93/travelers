<?php

namespace App\Repositories\Eloquent;
use App\Gallery;
use App\Repositories\GalleryRepositoryInterface;


class GalleryRepository implements GalleryRepositoryInterface {

    private $model;


    public function __construct(Gallery $model){
        $this -> model = $model;
    }

    public function create(){
        $gallery = new Gallery();
        $gallery -> save();
        return $this -> model -> orderBy('id', 'desc') -> first() -> id;
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }
}