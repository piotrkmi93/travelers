<?php

namespace App\Repositories;

interface CommentNotificationRepositoryInterface{
    public function create($comment_id, $notification_id);
    public function delete($id);
    public function getByNotificationId($notification_id);
    public function getByCommentId($comment_id);
}