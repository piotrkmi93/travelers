<?php

namespace App\Http\Controllers;

use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PlaceCommentRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\PostCommentRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommentController extends Controller
{
    private $commentRepository,
            $postCommentRepository,
            $notificationRepository,
            $commentNotificationRepository,
            $likeRepository,
            $commentLikeRepository,
            $likeNotificationRepository,
            $commentLikeNotificationRepository,
            $postRepository,
            $userRepository,
            $placeCommentRepository,
            $placeRepository;

    /**
     * CommentController constructor.
     * @param CommentRepositoryInterface $commentRepository
     * @param PostCommentRepositoryInterface $postCommentRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository,
                                PostCommentRepositoryInterface $postCommentRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                CommentNotificationRepositoryInterface $commentNotificationRepository,
                                LikeRepositoryInterface $likeRepository,
                                CommentLikeRepositoryInterface $commentLikeRepository,
                                LikeNotificationRepositoryInterface $likeNotificationRepository,
                                CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository,
                                PostRepositoryInterface $postRepository,
                                UserRepositoryInterface $userRepository,
                                PlaceCommentRepositoryInterface $placeCommentRepository,
                                PlaceRepositoryInterface $placeRepository){
        $this -> commentRepository = $commentRepository;
        $this -> postCommentRepository = $postCommentRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> commentNotificationRepository = $commentNotificationRepository;
        $this -> likeRepository = $likeRepository;
        $this -> commentLikeRepository = $commentLikeRepository;
        $this -> likeNotificationRepository = $likeNotificationRepository;
        $this -> commentLikeNotificationRepository = $commentLikeNotificationRepository;
        $this -> postRepository = $postRepository;
        $this -> userRepository = $userRepository;
        $this -> placeCommentRepository = $placeCommentRepository;
        $this -> placeRepository = $placeRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request){
        $type = $request -> type;
        $text = $request -> text;
        $user_id = $request -> user_id; // autor komentarza

        $comment_id = $this -> commentRepository -> create($text, $user_id, $type);

        switch ($type){
            case 'post':
                $post_id = $request -> post_id;

                $post_comment_id = $this -> postCommentRepository -> create($post_id, $comment_id);
                $post = $this -> postRepository -> getById($post_id);

                if ($post -> author_user_id != $user_id){
                    $notification_id = $this -> notificationRepository -> create($post -> author_user_id, 'comment');
                    $comment_notification_id = $this -> commentNotificationRepository -> create($comment_id, $notification_id);
                }

                break;

            case 'place':
                $place_id = $request -> place_id;

                $place_comment_id = $this -> placeCommentRepository -> create($place_id, $comment_id);
                $place = $this -> placeRepository -> getById($place_id);

                if ($place -> author_user_id != $user_id){
                    $notification_id = $this -> notificationRepository -> create($place -> author_user_id, 'comment');
                    $comment_notification_id = $this -> commentNotificationRepository -> create($comment_id, $notification_id);
                }

                break;
        }

        return response() -> json(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request){
        $comment_id = $request -> comment_id; // id komentarza
        $type = $this -> commentRepository -> getById($comment_id) -> type; // typ komentarza
        switch($type){
            case 'post':
                $post_comment_id = $this -> postCommentRepository -> getByCommentId($comment_id) -> id;
                $this -> postCommentRepository -> delete($post_comment_id);
                break;

            case 'place':
                $place_comment_id = $this -> placeCommentRepository -> getByCommentId($comment_id) -> id;
                $this -> placeCommentRepository -> delete($place_comment_id);
                break;
        }

        $commentLikes = $this -> commentLikeRepository -> getByCommentId($comment_id); // wszystkie lajki tego komentarza
        foreach ($commentLikes as $commentLike){
            $this -> likeRepository -> delete($commentLike -> like_id); // usuń lajka
            $this -> commentLikeRepository -> delete($commentLike->id); // usuń lajka komentarza
        }

        $commentLikeNotifications = $this -> commentLikeNotificationRepository -> getByCommentId($comment_id); // notyfikacje odnośnie lajków tego komentarza
        foreach ($commentLikeNotifications as $commentLikeNotification){
            $likeNotification = $this -> likeNotificationRepository -> get($commentLikeNotification->like_notification_id); // notyfikacja polubienia
            $this -> notificationRepository -> delete($likeNotification -> notification_id); // usuń notyfikację
            $this -> likeNotificationRepository -> delete($likeNotification -> id); // usuń notyfikację polubienia
            $this -> commentLikeNotificationRepository -> delete($commentLikeNotification->id); // usuń notyfikację polubienia komentarza
        }

        $commentNotifications = $this -> commentNotificationRepository -> getByCommentId($comment_id); // notyfikacje odnośnie komentowania
        foreach ($commentNotifications as $commentNotification){
            $this -> notificationRepository -> delete($commentNotification -> notification_id);
            $this -> commentNotificationRepository -> delete($commentNotification -> id);
        }

        return response() -> json($this -> commentRepository -> delete($comment_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request){
        $comment_id = $request -> comment_id;
        $user_id = $request -> user_id; // id osoby lajkującej
        $like_id = $this -> likeRepository -> create($user_id, 'comment');
        $comment_like_id = $this -> commentLikeRepository -> create($comment_id, $like_id);

        $comment_author_user_id = $this -> commentRepository -> getById($comment_id) -> author_user_id;
        if ($user_id != $comment_author_user_id){
            $notification_id = $this -> notificationRepository -> create($comment_author_user_id, 'like');
            $like_notification_id = $this -> likeNotificationRepository -> create($like_id, $notification_id, 'comment');
            $this -> commentLikeNotificationRepository -> create($like_notification_id, $comment_id);
        }

        return response() -> json($comment_like_id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike(Request $request){
        $comment_id = $request -> comment_id;
        $user_id = $request -> user_id;

        $find = $this -> commentLikeRepository -> find($user_id, $comment_id)[0];
        $this -> commentLikeRepository -> delete($find -> comment_like_id);
        return response() -> json($this -> likeRepository -> delete($find->like_id));
    }

    public function getPostComments(Request $request){
        $post_id = $request -> post_id;
        $user_id = $request -> user_id;
        $post_comments = $this -> postCommentRepository -> getByPostId($post_id);
        $comments = array();
        foreach ($post_comments as $post_comment) {
            $comment = $this -> commentRepository -> getById($post_comment -> comment_id);
            array_push($comments, array(
                'id' => $comment -> id,
                'text' => $comment -> text,
                'user' => $this->userRepository -> getUserBasicsById($comment->author_user_id),
                'date' => Carbon::parse($comment -> created_at) -> toDateString(),
                'time' => Carbon::parse($comment -> created_at) -> format('H:i'),

                'likes_count' => $this -> commentLikeRepository -> count($comment->id),
                'liked' => $this -> commentLikeRepository -> exists($user_id, $comment->id),
            ));
        }

        return response() -> json($comments);
    }

    public function getPlaceComments(Request $request){
        $place_id = $request -> place_id;
        $user_id = $request -> user_id;
        $place_comments = $this -> placeCommentRepository -> getByPlaceId($place_id);
        $comments = array();
        foreach ($place_comments as $index => $place_comment) {
            $comment = $this -> commentRepository -> getById($place_comment -> comment_id);
            array_push($comments, array(
                'id' => $comment -> id,
                'text' => $comment -> text,
                'user' => $this->userRepository -> getUserBasicsById($comment->author_user_id),
                'date' => Carbon::parse($comment -> created_at) -> toDateString(),
                'time' => Carbon::parse($comment -> created_at) -> format('H:i'),

                'likes_count' => $this -> commentLikeRepository -> count($comment->id),
                'liked' => $this -> commentLikeRepository -> exists($user_id, $comment->id),
            ));
        }

        return response() -> json($comments);
    }

    public function getUpdatedCommentStatistics(Request $request){
        $comment_id = $request -> comment_id;
        $likes_count = $this -> commentLikeRepository -> count($comment_id);
        return response() -> json(array(
            'likes_count' => $likes_count,
        ));
    }
}
