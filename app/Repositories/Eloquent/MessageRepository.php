<?php

namespace App\Repositories\Eloquent;
use App\Message;
use App\Repositories\MessageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{

    private $model;

    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function create($from_user_id, $to_user_id, $text){
        $message = new Message();
        $message -> from_user_id = $from_user_id;
        $message -> to_user_id = $to_user_id;
        $message -> text = $text;
        return $message -> save() ? $message : null;
    }

    public function read($id){
        $message = $this -> model -> find($id);
        $message -> is_read = 1;
        return $message -> save();
    }

    public function getConversation($user_one_id, $user_two_id, $offset){
        return DB::select("SELECT * FROM `messages` 
                    WHERE (from_user_id = $user_one_id AND to_user_id = $user_two_id) 
                    OR (from_user_id = $user_two_id AND to_user_id = $user_one_id) 
                    ORDER BY created_at DESC LIMIT 20 OFFSET $offset");
    } /* LIMIT 10 OFFSET $offset */

    public function getUnread($to_user_id, $last_id){
        return $this -> model
            -> where('id', '>', $last_id)
            -> where('to_user_id' ,'=', $to_user_id)
            -> where('is_read', '=', 0)
            -> get();
    }

    public function getLastMessage($user_one_id, $user_two_id){
        $lastMessage = DB::select("SELECT * FROM `messages` 
                    WHERE (from_user_id = $user_one_id AND to_user_id = $user_two_id) 
                    OR (from_user_id = $user_two_id AND to_user_id = $user_one_id) 
                    ORDER BY created_at DESC LIMIT 1");
        return $lastMessage ? $lastMessage[0] : null;
    }


}