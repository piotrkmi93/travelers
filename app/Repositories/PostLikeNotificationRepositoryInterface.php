<?php

namespace App\Repositories;

interface PostLikeNotificationRepositoryInterface{
    public function create($like_notification_id, $post_id);
    public function delete($id);
    public function getByLikeNotificationId($like_notification_id);
    public function find($notification_id);
}