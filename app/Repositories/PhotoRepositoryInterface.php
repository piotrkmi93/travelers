<?php

namespace App\Repositories;

interface PhotoRepositoryInterface {
    public function add($image, $gallery_id, $type);
    public function delete($id);
    public function getById($id);
    public function getUserPhotos($user);
    public function getByGalleryId($gallery_id);
}