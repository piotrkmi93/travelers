<?php

namespace App\Repositories;

interface InvitationNotificationRepositoryInterface {
    public function create($friends_pair_id, $notification_id);
    public function delete($friends_pair_id);
    public function getByNotificationId($notification_id);
}