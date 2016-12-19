<?php

namespace App\Repositories;

interface CommentLikeNotificationRepositoryInterface {
    public function create($like_notification_id, $comment_id);
    public function delete($id);
    public function getById($id);
    public function getByLikeNotificationId($like_notification_id);
    public function find($notification_id);
    public function getByCommentId($comment_id);
}