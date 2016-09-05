<?php

namespace App\Repositories\Eloquent;
use App\CommentNotification;
use App\Repositories\CommentNotificationRepositoryInterface;

class CommentNotificationRepository implements CommentNotificationRepositoryInterface {

    private $model;

    public function __construct(CommentNotification $model){
        $this -> model = $model;
    }

    public function create($comment_id, $notification_id){
        $commentNotification = new CommentNotification();
        $commentNotification -> comment_id = $comment_id;
        $commentNotification -> notification_id = $notification_id;
        $commentNotification -> save();
        return $commentNotification -> id;
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    public function getByNotificationId($notification_id){
        return $this->model->where('notification_id', '=', $notification_id)->first();
    }
}