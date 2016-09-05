<?php

namespace App\Http\Controllers;

use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\InvitationNotificationRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;

class FriendsController extends Controller
{
    private $friendsPairRepository,
            $notificationRepository,
            $invitationNotificationRepository,
            $userRepository,
            $photoRepository;

    /**
     * FriendsController constructor.
     * @param FriendsPairRepositoryInterface $friendsPairRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param UserRepositoryInterface $userRepository
     * @param PhotoRepositoryInterface $photoRepository
     */
    public function __construct(FriendsPairRepositoryInterface $friendsPairRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                InvitationNotificationRepositoryInterface $invitationNotificationRepository,
                                UserRepositoryInterface $userRepository,
                                PhotoRepositoryInterface $photoRepository){
        $this -> friendsPairRepository = $friendsPairRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> invitationNotificationRepository = $invitationNotificationRepository;
        $this -> userRepository = $userRepository;
        $this -> photoRepository = $photoRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIsUserYourFriend(Request $request) {
        return response() -> json($this -> friendsPairRepository -> checkIsUserYourFriend($request -> from_user_id, $request -> to_user_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvitation(Request $request) {
        $friends_pair_id = $this -> friendsPairRepository -> sendInvitation($request -> from_user_id, $request -> to_user_id);
        $notification_id = $this -> notificationRepository -> create($request -> to_user_id, 'invitation');
        $this -> invitationNotificationRepository -> create($friends_pair_id, $notification_id);
        return response() -> json($friends_pair_id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptInvitation(Request $request) {
        $friends_pair_id = $this -> friendsPairRepository -> acceptInvitation($request -> from_user_id, $request -> to_user_id);
        $notification_id = $this -> invitationNotificationRepository -> delete($friends_pair_id);
        return response() -> json($this -> notificationRepository -> delete($notification_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFromFriends(Request $request) {
        $friends_pair_id = $this -> friendsPairRepository -> deleteFromFriends($request -> from_user_id, $request -> to_user_id);
        $notification_id = $this -> invitationNotificationRepository -> delete($friends_pair_id);
        return response() -> json($this -> notificationRepository -> delete($notification_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserFriends(Request $request){
        $user_id = $request -> user_id;
        $friends_pairs = $this -> friendsPairRepository -> getUserFriends($user_id);
        $friends = array();
        foreach ($friends_pairs as $friends_pair) {
            $friend = null;
            if ($friends_pair -> from_user_id == $user_id) $friend = $this -> userRepository -> getById($friends_pair -> to_user_id);
            else $friend = $this -> userRepository -> getById($friends_pair -> from_user_id);

            array_push($friends, array(
                'id' => $friend -> id,
                'name' => $friend -> first_name . ' ' . $friend -> last_name,
                'username' => $friend -> username,
                'avatar' => $friend->avatar_photo_id ? asset($this->photoRepository->getById($friend->avatar_photo_id)['image_url']) : asset('images/avatar_' . $friend -> sex . '.png'),
                'avatar_thumb' => $friend->avatar_photo_id ? asset($this->photoRepository->getById($friend->avatar_photo_id)['thumb_url']) : asset('images/avatar_min_' . $friend -> sex . '.png'),
                'is_active' => $this -> userRepository -> isActive($friend->id),
            ));
        }

        return response() -> json(array(
            'friends' => $friends,
        ));
    }
}
