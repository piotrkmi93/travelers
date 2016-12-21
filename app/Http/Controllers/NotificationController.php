<?php

namespace App\Http\Controllers;

use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\InvitationNotificationRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PlaceCommentRepositoryInterface;
use App\Repositories\PlaceLikeNotificationRepositoryInterface;
use App\Repositories\PlaceLikeRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\PostCommentRepositoryInterface;
use App\Repositories\PostLikeNotificationRepositoryInterface;
use App\Repositories\PostLikeRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\TripCommentRepositoryInterface;
use App\Repositories\TripInvitationNotificationRepositoryInterface;
use App\Repositories\TripRepositoryInterface;
use App\Repositories\TripUserRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $notificationRepository,
            $invitationNotificationRepository,
            $likeNotificationRepository,
            $postLikeNotificationRepository,

            $friendsPairRepository,
            $userRepository,
            $photoRepository,
            $postRepository,
            $placeRepository,

            $likeRepository,
            $postLikeRepository,
            $placeLikeRepository,

            $commentRepository,
            $commentLikeRepository,
            $commentLikeNotificationRepository,
            $commentNotificationRepository,
            $placeLikeNotificationRepository,

            $postCommentRepository,
            $placeCommentRepository,

            $tripInvitationNotificationRepository,
            $tripUserRepository,
            $tripRepository,
            $tripCommentRepository;

    /**
     * NotificationController constructor.
     * @param NotificationRepositoryInterface $notificationRepository
     * @param InvitationNotificationRepositoryInterface $invitationNotificationRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param FriendsPairRepositoryInterface $friendsPairRepository
     * @param PostLikeNotificationRepositoryInterface $postLikeNotificationRepository
     * @param PlaceLikeRepositoryInterface $placeLikeRepository
     * @param UserRepositoryInterface $userRepository
     * @param PhotoRepositoryInterface $photoRepository
     * @param PostRepositoryInterface $postRepository
     * @param PlaceRepositoryInterface $placeRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param PostLikeRepositoryInterface $postLikeRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     * @param PlaceLikeNotificationRepositoryInterface $placeLikeNotificationRepository
     * @param PostCommentRepositoryInterface $postCommentRepository
     * @param PlaceCommentRepositoryInterface $placeCommentRepository
     * @param TripInvitationNotificationRepositoryInterface $tripInvitationNotificationRepository
     * @param TripUserRepositoryInterface $tripUserRepository
     * @param TripRepositoryInterface $tripRepository
     * @param TripCommentRepositoryInterface $tripCommentRepository
     */
    public function __construct(NotificationRepositoryInterface                 $notificationRepository,
                                InvitationNotificationRepositoryInterface       $invitationNotificationRepository,
                                LikeNotificationRepositoryInterface             $likeNotificationRepository,
                                FriendsPairRepositoryInterface                  $friendsPairRepository,
                                PostLikeNotificationRepositoryInterface         $postLikeNotificationRepository,
                                PlaceLikeRepositoryInterface                    $placeLikeRepository,
                                UserRepositoryInterface                         $userRepository,
                                PhotoRepositoryInterface                        $photoRepository,
                                PostRepositoryInterface                         $postRepository,
                                PlaceRepositoryInterface                        $placeRepository,
                                LikeRepositoryInterface                         $likeRepository,
                                PostLikeRepositoryInterface                     $postLikeRepository,
                                CommentRepositoryInterface                      $commentRepository,
                                CommentLikeRepositoryInterface                  $commentLikeRepository,
                                CommentLikeNotificationRepositoryInterface      $commentLikeNotificationRepository,
                                CommentNotificationRepositoryInterface          $commentNotificationRepository,
                                PlaceLikeNotificationRepositoryInterface        $placeLikeNotificationRepository,
                                PostCommentRepositoryInterface                  $postCommentRepository,
                                PlaceCommentRepositoryInterface                 $placeCommentRepository,
                                TripInvitationNotificationRepositoryInterface   $tripInvitationNotificationRepository,
                                TripUserRepositoryInterface                     $tripUserRepository,
                                TripRepositoryInterface                         $tripRepository,
                                TripCommentRepositoryInterface                  $tripCommentRepository){

        $this -> notificationRepository                 = $notificationRepository;
        $this -> invitationNotificationRepository       = $invitationNotificationRepository;
        $this -> likeNotificationRepository             = $likeNotificationRepository;
        $this -> friendsPairRepository                  = $friendsPairRepository;
        $this -> postLikeNotificationRepository         = $postLikeNotificationRepository;
        $this -> placeLikeRepository                    = $placeLikeRepository;
        $this -> userRepository                         = $userRepository;
        $this -> photoRepository                        = $photoRepository;
        $this -> postRepository                         = $postRepository;
        $this -> placeRepository                        = $placeRepository;
        $this -> likeRepository                         = $likeRepository;
        $this -> postLikeRepository                     = $postLikeRepository;

        $this -> commentRepository                      = $commentRepository;
        $this -> commentLikeRepository                  = $commentLikeRepository;
        $this -> commentLikeNotificationRepository      = $commentLikeNotificationRepository;
        $this -> commentNotificationRepository          = $commentNotificationRepository;
        $this -> placeLikeNotificationRepository        = $placeLikeNotificationRepository;

        $this -> postCommentRepository                  = $postCommentRepository;
        $this -> placeCommentRepository                 = $placeCommentRepository;

        $this -> tripInvitationNotificationRepository   = $tripInvitationNotificationRepository;
        $this -> tripUserRepository                     = $tripUserRepository;
        $this -> tripRepository                         = $tripRepository;
        $this -> tripCommentRepository                  = $tripCommentRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request) {
        $notifications = $this -> notificationRepository -> getNotifications($request -> user_id, $request -> last_id ? $request -> last_id : null);
        $notificationsCount = $notifications -> count();

        $return = array(
            'notifications' => array(),
            'notificationsCount' => $notificationsCount,
            'lastId' => $request -> last_id,
        );

        if(count($notifications)) {

            foreach ($notifications as &$notification) {
                $sender = null;
                $item = array(
                    'id' => $notification->id,
                    'date' => Carbon::parse($notification -> created_at) -> toDateString(),
                    'time' => Carbon::parse($notification -> created_at) -> format('H:i'),
                );

                switch ($notification->type) {

                    case 'trip-invitation':
                        $tripInvitationNotification = $this -> tripInvitationNotificationRepository -> getByNotificationId($notification->id);
                        $tripUser = $this -> tripUserRepository -> get($tripInvitationNotification -> trip_user_id);
                        $trip = $this -> tripRepository -> get($tripUser -> trip_id);
                        if($tripUser){
                            $sender = $this -> userRepository -> getById($trip -> user_id);
                            $item['type'] = $notification -> type;
                            $item['name'] = $trip -> name;
                            $item['trip_user_id'] = $tripUser -> id;
                            $item['url'] = asset('trips/' . $trip -> slug);
                        } else {
                            $this -> tripInvitationNotificationRepository -> delete($tripInvitationNotification->id);
                            $this -> notificationRepository -> delete($notification->id);
                        }
                        break;

                    case 'invitation':
                        $invitationNotification = $this->invitationNotificationRepository->getByNotificationId($notification->id);
                        $friendsPair = $this->friendsPairRepository->getById($invitationNotification->friends_pair_id);
                        if ($friendsPair){
                            $sender = $this->userRepository->getById($friendsPair->from_user_id);
                            $item['type'] = $notification->type;
                        } else {
                            $this -> invitationNotificationRepository -> delete($invitationNotification->friends_pair_id);
                            $this -> notificationRepository -> delete($notification->id);
                        }
                        break;

                    case 'like':
                        $likeNotification = $this->likeNotificationRepository->getByNotificationId($notification->id);
                        $like = $this->likeRepository->getById($likeNotification->like_id);
                        if($like){
                            $sender = $this->userRepository->getById($like->user_id);
                            $item['type'] = $like->type . '-' .$notification->type;

                            switch ($like->type){
                                case 'post':
                                    $postLike = $this->postLikeNotificationRepository->getByLikeNotificationId($likeNotification->id);
                                    $item['post_id'] = $postLike -> post_id;
                                    $text = $this->postRepository->getById($postLike->post_id)->text;
                                    $item['post_text'] = strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text;
                                    break;

                                case 'comment':
                                    $commentLike = $this->commentLikeNotificationRepository->getByLikeNotificationId($likeNotification->id);
                                    $item['comment_id'] = $commentLike -> comment_id;
                                    $text = $this -> commentRepository -> getById($commentLike->comment_id)->text;
                                    $item['comment_text'] = strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text;
                                    break;

                                case 'place':
                                    $placeLike = $this -> placeLikeNotificationRepository -> getByLikeNotificationId($likeNotification->id);
                                    $item['place_id'] = $placeLike -> place_id;
                                    $place = $this -> placeRepository -> getById($placeLike -> place_id);
                                    $item['name'] = $place -> name;
                                    $item['url'] = asset('places/' . $place -> slug);
                                    break;
                            }
                        } else {
                            $this -> likeNotificationRepository -> delete($likeNotification->id);
                            $this -> notificationRepository -> delete($notification->id);
                        }
                        break;

                    case 'comment':
                        $commentNotification = $this -> commentNotificationRepository -> getByNotificationId($notification->id);
                        $comment = $this -> commentRepository -> getById($commentNotification->comment_id);
                        if($comment){
                            $sender = $this -> userRepository -> getById($comment->author_user_id);
                            $text = $comment -> text;
                            $item['comment_text'] = str_replace('<br>', '', strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text);
                            $item['type'] = $comment->type . '-' . $notification->type;

                            if($comment->type == 'post') {
                                $postComment = $this -> postCommentRepository -> getByCommentId($comment->id);
                                $item['post_id'] = $postComment -> post_id;
                            }

                            if($comment->type == 'place') {
                                $placeComment = $this -> placeCommentRepository -> getByCommentId($comment->id);
                                $place = $this -> placeRepository -> getById($placeComment -> place_id);
                                $item['place_id'] = $place -> id;
                                $item['name'] = $place -> name;
                                $item['url'] = asset('places/' . $place -> slug);
                            }

                            if($comment->type == 'trip'){
                                $tripComment = $this -> tripCommentRepository -> getByCommentId($comment->id);
                                $trip = $this -> tripRepository -> get($tripComment -> trip_id);
                                $item['trip_id'] = $trip -> id;
                                $item['name'] = $trip -> name;
                                $item['url'] = asset('trips/' . $trip -> slug);
                            }

                        } else {
                            $this -> commentNotificationRepository -> delete($commentNotification->id);
                            $this -> notificationRepository -> delete($notification->id);
                        }
                        break;
                }

                if ($sender){
                    $item['senderUrl'] = asset('user/' . $sender->username . '#/board');
                    $item['senderName'] = $sender->first_name . ' ' . $sender->last_name;
                    $item['senderId'] = $sender->id;
                    $item['senderUsername'] = $sender->username;
                    $item['senderAvatar'] = $sender->avatar_photo_id ? asset($this->photoRepository->getById($sender->avatar_photo_id)->thumb_url) : asset('images/avatar_min_' . $sender -> sex . '.png');

                    array_push($return['notifications'], $item);
                }
            }

            if(!empty($return['notifications'])) $return['lastId'] = $return['notifications'][$return['notificationsCount'] - 1]['id'];
        }

        return response() -> json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(Request $request){
        $notification_id = $request -> notification_id;
        $type = $request -> type;

        switch($type){

            // comments
            case 'post-comment':
                $commentNotification = $this -> commentNotificationRepository -> getByNotificationId($notification_id);
                $this -> commentNotificationRepository ->delete($commentNotification->id);
                $this -> notificationRepository -> delete($commentNotification->notification_id);
                break;

            case 'place-comment':
                $commentNotification = $this -> commentNotificationRepository -> getByNotificationId($notification_id);
                $this -> commentNotificationRepository -> delete( $commentNotification -> id);
                $this -> notificationRepository -> delete($commentNotification -> notification_id);
                break;

            case 'trip-comment':
                $commentNotification = $this -> commentNotificationRepository -> getByNotificationId($notification_id);
                $this -> commentNotificationRepository -> delete($commentNotification->id);
                $this -> notificationRepository -> delete($commentNotification -> notification_id);
                break;

            // likes
            case 'post-like':
                $find = $this->postLikeNotificationRepository->find($notification_id);
                $this ->notificationRepository->delete($find->notification_id);
                $this ->likeNotificationRepository->delete($find->like_notification_id);
                $this ->postLikeNotificationRepository->delete($find->post_like_notification_id);
                break;

            case 'comment-like':
                $find = $this -> commentLikeNotificationRepository -> find($notification_id);
                $this ->notificationRepository->delete($find->notification_id);
                $this ->likeNotificationRepository->delete($find->like_notification_id);
                $this -> commentLikeNotificationRepository -> delete($find->comment_like_notification_id);
                break;

            case 'place-like':
                $find = $this -> placeLikeNotificationRepository -> find($notification_id);
                $this -> notificationRepository -> delete($find -> notification_id);
                $this -> likeNotificationRepository -> delete($find -> like_notification_id);
                $this -> placeLikeNotificationRepository -> delete($find -> place_like_notification_id);
                break;
        }

        return response()->json(true);
    }
}
