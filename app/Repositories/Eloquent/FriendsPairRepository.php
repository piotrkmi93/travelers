<?php

namespace App\Repositories\Eloquent;
use App\FriendsPair;
use App\Repositories\FriendsPairRepositoryInterface;
use Illuminate\Support\Facades\DB;


class FriendsPairRepository implements FriendsPairRepositoryInterface {

    private $model;

    public function __construct(FriendsPair $model){
        $this -> model = $model;
    }

    public function checkIsUserYourFriend($friend1_id, $friend2_id){
        $return = array(
            'isUserYourFriend' => false,
            'isInvitationAccepted' => false,
        );

        $friendsPair = $this -> findFriends($friend1_id, $friend2_id);
        if ( $friendsPair ) {
            $return['isUserYourFriend'] = true;
            $return['isInvitationAccepted'] = $friendsPair[0] -> status == 1 ? true : false;
            if($return['isUserYourFriend'] == true && $return['isInvitationAccepted'] == false) $return['invitationFrom'] = $friendsPair[0] -> from_user_id;
        }

        return $return;
    }

    public function sendInvitation($from_user_id, $to_user_id){
        $friendsPair = new FriendsPair();
        $friendsPair -> from_user_id = $from_user_id;
        $friendsPair -> to_user_id = $to_user_id;
        $friendsPair -> save();
        return $friendsPair -> id;
    }

    public function acceptInvitation($friend1_id, $friend2_id){
        $id = $this -> findFriends($friend1_id, $friend2_id)[0]->id;
        $friendsPair = $this -> model -> find($id);
        $friendsPair -> status = 1;
        $friendsPair -> save();
        return $friendsPair -> id;
    }

    public function deleteFromFriends($friend1_id, $friend2_id){
        $id = $this -> findFriends($friend1_id, $friend2_id)[0]->id;
        $this -> model -> find($id) -> delete();
        return $id;
    }

    private function findFriends($friend1_id, $friend2_id){
        return DB::select("SELECT * FROM `friends_pairs` WHERE (from_user_id = ".$friend1_id." AND to_user_id = ".$friend2_id.") OR (from_user_id = ".$friend2_id." AND to_user_id = ".$friend1_id.") LIMIT 1");
    }

    public function getById($id){
        return $this -> model -> find($id);
    }

    public function getUserFriends($user_id){
        return DB::select("SELECT from_user_id, to_user_id FROM `friends_pairs` WHERE (from_user_id = ".$user_id." OR to_user_id = ".$user_id.") AND status = 1");
    }

    public function getFriendsByPhrase($user_id, $phrase){
        return DB::select("SELECT b.id, b.first_name, b.last_name, b.avatar_photo_id, b.sex from friends_pairs a JOIN users b ON ((a.from_user_id = b.id AND a.to_user_id = $user_id) OR (a.to_user_id = b.id AND a.from_user_id = $user_id)) AND (b.first_name LIKE '%".$phrase."%' OR b.last_name LIKE '%".$phrase."%' OR b.username LIKE '%".$phrase."%')");
		// , c.thumb_url JOIN photos c ON c.id = b.avatar_photo_id WHERE a.status = 1
    }
}