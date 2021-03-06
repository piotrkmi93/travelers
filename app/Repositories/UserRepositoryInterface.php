<?php

namespace App\Repositories;

interface UserRepositoryInterface {
    public function isExistUsername($username);
    public function generateUniqueUsername($email);
    public function getByUsername($username);
    public function changeAvatar($id, $image_id);
    public function changeBackground($id, $image_id);
    public function getById($id);
    public function getUserBasicsById($id);
    public function extendActiveTimestamp($id);
    public function isActive($id);
    public function searchByPhrase($phrase);
    public function passwordCorrect($id, $password);
    public function updatePassword($id, $password);
	public function updateFirstAndLastName($id, $first_name, $last_name);
}