<?php

namespace App\Repositories\Eloquent;
use App\PostLikeNotification;
use App\Repositories\PostLikeNotificationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostLikeNotificationRepository implements PostLikeNotificationRepositoryInterface{

    private $model;

    public function __construct(PostLikeNotification $model){
        $this -> model = $model;
    }

    public function create($like_notification_id, $post_id){
        $postLikeNotification = new PostLikeNotification();
        $postLikeNotification -> like_notification_id = $like_notification_id;
        $postLikeNotification -> post_id = $post_id;
        return $postLikeNotification -> save();
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    public function getByLikeNotificationId($like_notification_id){
        return $this -> model -> where('like_notification_id', '=', $like_notification_id) -> first();
    }

    public function find($notification_id){
        return DB::select("SELECT a.id AS 'notification_id', b.id AS 'like_notification_id', c.id AS 'post_like_notification_id' FROM `notifications` a INNER JOIN `like_notifications` b ON a.id = b.notification_id INNER JOIN `post_like_notifications` c ON b.id = c.like_notification_id WHERE a.id = ". $notification_id)[0];
    }

    public function getByPostId($post_id)
    {
        return $this -> model -> wherePostId($post_id) -> get();
    }


}