<?php

namespace App\Repositories;

interface GalleryRepositoryInterface {
    public function create();
    public function delete($id);
    public function get($id);
}