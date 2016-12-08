<?php

namespace App\Http\Controllers;

use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\MessageRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Intervention\Image\Exception\RuntimeException;

class MessageController extends Controller
{
    private $userRepository,
            $messageRepository,
            $friendPairRepository,
            $photoRepository;

    public function __construct(UserRepositoryInterface $userRepository,
                                MessageRepositoryInterface $messageRepository,
                                FriendsPairRepositoryInterface $friendsPairRepository,
                                PhotoRepositoryInterface $photoRepository){

        $this -> userRepository = $userRepository;
        $this -> messageRepository = $messageRepository;
        $this -> friendPairRepository = $friendsPairRepository;
        $this -> photoRepository = $photoRepository;
    }

    public function index(){

        // wszyscy znajomi
        // ostatnie wiadomości od znajomych oraz info czy zostały odczytane
        // wczytywanie pełnej konwersjaci po kliknięciu na znajomego, url/messanger#username

        return view('messanger');
    }


    public function getFriends(Request $request){
        $user_id = $request -> user_id; // id zalogowanego użytkownika
        $friends_pairs = $this -> friendPairRepository -> getUserFriends($user_id);
        $friends = array();
        foreach ($friends_pairs as $friends_pair){
            $friend = null;
            if ($friends_pair -> from_user_id == $user_id) $friend = $this -> userRepository -> getById($friends_pair -> to_user_id);
            else $friend = $this -> userRepository -> getById($friends_pair -> from_user_id);

            array_push( $friends, $this -> getFriend($user_id, $friend) );
        }

        return response() -> json(array(
            'friends' => $friends,
        ));
    }

    // pobiera konwersację o ilości offset + 10
    // scrollowanie w górę będzie doczytywało kolejne starsze wiadomości w ilości 10
    public function getConversation(Request $request){
        $user_one_id = $request -> user_one_id;
        $user_two_id = $request -> user_two_id;
        $offset = $request -> offset;

        $messages = $this -> messageRepository -> getConversation($user_one_id, $user_two_id, $offset);

        return response() -> json(array(
            'messages' => $messages,
        ));
    }

    // funkcja wywoływana co 1s
    public function getMessages(Request $request){
        $user_id = $request -> user_id;
        $last_id = $request -> last_id;

        $messages = $this -> messageRepository -> getUnread($user_id, $last_id);
        $messagesCount = count($messages);
        $messageLastId = null;
        if($messagesCount) $messageLastId = $messages[$messagesCount-1]->id;

        return response() -> json(array(
            'messages' => $messages,
            'messagesCount' => $messagesCount,
            'messageLastId' => $messageLastId,
        ));
    }

    public function sendMessage(Request $request){
        $from_user_id = $request -> from_user_id;
        $to_user_id = $request -> to_user_id;
        $text = $request -> text;

        return response() -> json(array('message' => $this -> messageRepository -> create($from_user_id, $to_user_id, $text)));
    }

    public function getUserByUsername(Request $request){
        $username = $request -> username;
        $user = $this -> userRepository -> getByUsername($username);
        return response() -> json(
            array(
                'interlocutor' => array(
                    'id' => $user -> id,
                    'name' => $user -> first_name,
                    'thumb' => $user -> avatar_photo_id ? asset($this -> photoRepository -> getById($user -> avatar_photo_id) -> thumb_url) : asset('images/avatar_min_' . $user -> sex . '.png'),
        )));
    }

    // kiedy otworzymy konwersację to wszystkie nieprzeczytane wiadomości muszą zostać zmienione na przeczytane
    public function readMessages(Request $request){
        $ids = $request -> ids;
        foreach ($ids as $id){
            $this -> messageRepository -> read($id);
        }
        return response() -> json(array('success' => true));
    }












    private function getFriend($user_id, $friend){
        return array(
            'id' => $friend -> id,
            'name' => $friend -> first_name . ' ' . $friend -> last_name,
            'username' => $friend -> username,
            'avatar_thumb' => $friend->avatar_photo_id ? asset($this->photoRepository->getById($friend->avatar_photo_id)['thumb_url']) : asset('images/avatar_min_' . $friend -> sex . '.png'),
            'is_active' => $this -> userRepository -> isActive($friend->id),
            'last_message' => $this -> getLastMessage($user_id, $friend->id),
        );
    }

    private function getLastMessage($from_user_id, $to_user_id){
        $message = $this -> messageRepository -> getLastMessage($from_user_id, $to_user_id);

        if(!$message){
            return null;
        }

        if ($message -> from_user_id == $from_user_id){
            $message -> text = 'Ja: ' . $message -> text;
        }

        if ($message -> from_user_id == $to_user_id){
            $user = $this -> userRepository -> getById($to_user_id);
            $message -> text = $user->first_name . ': ' . $message -> text;
        }

        return $message;
    }
}
