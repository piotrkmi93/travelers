<?php

namespace App\Repositories;

interface FriendsPairRepositoryInterface {
    public function checkIsUserYourFriend($friend1_id, $friend2_id);
    public function sendInvitation($from_user_id, $to_user_id);
    public function acceptInvitation($friend1_id, $friend2_id);
    public function deleteFromFriends($friend1_id, $friend2_id);
    public function getById($id);
    public function getUserFriends($user_id);
    public function getFriendsByPhrase($user_id, $phrase);
}