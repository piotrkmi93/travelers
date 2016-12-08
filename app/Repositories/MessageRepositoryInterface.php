<?php

namespace App\Repositories;

interface MessageRepositoryInterface {
    public function create($from_user_id, $to_user_id, $text);
    public function read($id);
    public function getConversation($user_one_id, $user_two_id, $offset);
    public function getUnread($to_user_id, $last_id);
    public function getLastMessage($user_one_id, $user_two_id);
}