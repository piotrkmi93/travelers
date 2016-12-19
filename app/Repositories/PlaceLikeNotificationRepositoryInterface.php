<?php

namespace App\Repositories;

interface PlaceLikeNotificationRepositoryInterface {
    public function create($like_notification_id, $place_id);
    public function delete($id);
    public function getByLikeNotificationId($like_notification_id);
    public function find($notification_id);
    public function getByPlaceId($place_id);
}