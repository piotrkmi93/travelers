<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 13:52
 */

namespace App\Repositories;


interface TripRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function get($id);
    public function getBySlug($slug);
    public function getByUserId($user_id);
}