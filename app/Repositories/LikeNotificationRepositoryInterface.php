<?php

namespace App\Repositories;

interface LikeNotificationRepositoryInterface {
    public function create($like_id, $notification_id, $type);
    public function delete($id);
    public function getByNotificationId($notification_id);
    public function get($id);
}