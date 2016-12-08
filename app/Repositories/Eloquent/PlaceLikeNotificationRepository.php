<?php

namespace App\Repositories\Eloquent;
use App\PlaceLikeNotification;
use App\Repositories\PlaceLikeNotificationRepositoryInterface;

class PlaceLikeNotificationRepository implements PlaceLikeNotificationRepositoryInterface {

    private $model;

    public function __construct(PlaceLikeNotification $model){
        $this -> model = $model;
    }

    public function create($like_notification_id, $place_id){
        $placeLikeNotification = new PlaceLikeNotification();
        $placeLikeNotification -> like_notification_id = $like_notification_id;
        $placeLikeNotification -> place_id = $place_id;
        return $placeLikeNotification -> save();
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    public function getByLikeNotificationId($like_notification_id){
        return $this -> model -> where('like_notification_id', '=', $like_notification_id) -> first();
    }

    public function find($notification_id){
        return DB::select("SELECT a.id AS 'notification_id', b.id AS 'like_notification_id', c.id AS 'place_like_notification_id' FROM `notifications` a INNER JOIN `like_notifications` b ON a.id = b.notification_id INNER JOIN `place_like_notifications` c ON b.id = c.like_notification_id WHERE a.id = ". $notification_id)[0];
    }


}