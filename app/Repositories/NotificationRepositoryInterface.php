<?php

namespace App\Repositories;

interface NotificationRepositoryInterface {
    public function create($user_id, $type);
    public function delete($id);
    public function getNotifications($user_id, $last_id);
    public function getById($id);
}