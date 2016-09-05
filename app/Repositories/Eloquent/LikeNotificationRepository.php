<?php

namespace App\Repositories\Eloquent;
use App\LikeNotification;
use App\Repositories\LikeNotificationRepositoryInterface;

class LikeNotificationRepository implements LikeNotificationRepositoryInterface {

    private $model;

    public function __construct(LikeNotification $model){
        $this -> model = $model;
    }

    public function create($like_id, $notification_id, $type){
        $likeNotification = new LikeNotification();
        $likeNotification -> like_id = $like_id;
        $likeNotification -> notification_id = $notification_id;
        $likeNotification -> type = $type;
        $likeNotification -> save();
        return $likeNotification -> id;
    }

    public function delete($id){
        return $this->model->find($id)->delete();
    }

    public function getByNotificationId($notification_id){
        return $this -> model -> where('notification_id', '=', $notification_id) -> first();
    }
}