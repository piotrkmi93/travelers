<?php

namespace App\Repositories\Eloquent;
use App\CommentLikeNotification;
use App\Repositories\CommentLikeNotificationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CommentLikeNotificationRepository implements CommentLikeNotificationRepositoryInterface{

    private $model;

    public function __construct(CommentLikeNotification $model){
        $this -> model = $model;
    }

    public function create($like_notification_id, $comment_id){
        $commentLikeNotification = new CommentLikeNotification();
        $commentLikeNotification -> like_notification_id = $like_notification_id;
        $commentLikeNotification -> comment_id = $comment_id;
        $commentLikeNotification -> save();
        return $commentLikeNotification -> id;
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    public function getById($id){
        return $this -> model -> find($id);
    }

    public function getByLikeNotificationId($like_notification_id){
        return $this -> model -> where('like_notification_id', '=', $like_notification_id) -> first();
    }

    public function find($notification_id){
        return DB::select("SELECT a.id AS 'notification_id', b.id AS 'like_notification_id', c.id AS 'comment_like_notification_id' FROM `notifications` a INNER JOIN `like_notifications` b ON a.id = b.notification_id INNER JOIN `comment_like_notifications` c ON b.id = c.like_notification_id WHERE a.id = ". $notification_id)[0];
    }

    public function getByCommentId($comment_id)
    {
        return $this -> model -> whereCommentId($comment_id) -> get();
    }


}