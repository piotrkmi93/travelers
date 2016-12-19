<?php

namespace App\Http\Controllers;

use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\Eloquent\FriendsPairRepository;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PostCommentRepositoryInterface;
use App\Repositories\PostLikeNotificationRepositoryInterface;
use App\Repositories\PostLikeRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $postRepository,
            $photoRepository,
            $userRepository,
            $friendsPairRepository,
            $likeRepository,
            $postLikeRepository,
            $notificationRepository,
            $likeNotificationRepository,
            $postLikeNotificationRepository,
            $postCommentRepository,
            $commentRepository,
            $commentLikeRepository,
            $commentLikeNotificationRepository,
            $commentNotificationRepository;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PhotoRepositoryInterface $photoRepository
     * @param UserRepositoryInterface $userRepository
     * @param FriendsPairRepository $friendsPairRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param PostLikeRepositoryInterface $postLikeRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param PostLikeNotificationRepositoryInterface $postLikeNotificationRepository
     * @param PostCommentRepositoryInterface $postCommentRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     */
    public function __construct(PostRepositoryInterface $postRepository,
                                PhotoRepositoryInterface $photoRepository,
                                UserRepositoryInterface $userRepository,
                                FriendsPairRepository $friendsPairRepository,
                                LikeRepositoryInterface $likeRepository,
                                PostLikeRepositoryInterface $postLikeRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                LikeNotificationRepositoryInterface $likeNotificationRepository,
                                PostLikeNotificationRepositoryInterface $postLikeNotificationRepository,
                                PostCommentRepositoryInterface $postCommentRepository,
                                CommentRepositoryInterface $commentRepository,
                                CommentLikeRepositoryInterface $commentLikeRepository,
                                CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository,
                                CommentNotificationRepositoryInterface $commentNotificationRepository) {
        $this -> postRepository = $postRepository;
        $this -> photoRepository = $photoRepository;
        $this -> userRepository = $userRepository;
        $this -> friendsPairRepository = $friendsPairRepository;
        $this -> likeRepository = $likeRepository;
        $this -> postLikeRepository = $postLikeRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> likeNotificationRepository = $likeNotificationRepository;
        $this -> postLikeNotificationRepository = $postLikeNotificationRepository;
        $this -> postCommentRepository = $postCommentRepository;
        $this -> commentRepository = $commentRepository;

        $this -> commentLikeRepository = $commentLikeRepository;
        $this -> commentLikeNotificationRepository = $commentLikeNotificationRepository;
        $this -> commentNotificationRepository = $commentNotificationRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view(Auth::user() ? 'board' : 'home');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPost(Request $request){
        $user = $this->userRepository->getById($request->user_id);

        $photo_id = null;
        if ($request -> hasFile('photo')){
            $photo_id = $this->photoRepository->add($request->photo, $user->gallery_id, 'normal');
        }

        $post_id = $this -> postRepository ->create($request->text,$photo_id,$user->id,'post');

        return response() -> json(array(
            'post_id' => $post_id,
        ));
    }

    public function getUserPosts(Request $request){
        $user_id = $request -> user_id;
        $offset = $request -> offset;

        $posts = $this -> postRepository -> getUserPosts($user_id, $offset) -> toArray();

        foreach ($posts as &$post) {
            $post['user'] = $this -> userRepository -> getUserBasicsById($post['author_user_id']);
            if($post['photo_id']) $post['photo'] = asset( getImage($post['photo_id']) );
            $post['date'] = Carbon::parse($post['created_at']) -> toDateString();
            $post['time'] = Carbon::parse($post['created_at']) -> format('H:i');

            $post['likes_count'] = $this -> postLikeRepository -> count($post['id']);
            $post['liked'] = $this -> postLikeRepository -> exists($user_id, $post['id']);

            $post['comments_count'] = $this -> postCommentRepository -> count($post['id']);
        }

        return response() -> json(array(
            'posts' => $posts,
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersPosts(Request $request){
        $user_id = $request -> user_id;
        $offset = $request -> offset;

        $friends_pairs = $this -> friendsPairRepository -> getUserFriends($user_id);

        $users = array();
        array_push($users, $this -> userRepository -> getById($user_id));
        foreach ($friends_pairs as $friends_pair){
            if ($friends_pair -> from_user_id == $user_id) $friend = $this -> userRepository -> getById($friends_pair -> to_user_id);
            else $friend = $this -> userRepository -> getById($friends_pair -> from_user_id);
            array_push($users, $friend);
        }

        $posts = $this -> postRepository -> getUsersPosts($users, $offset) -> toArray();

        foreach ($posts as &$post) {
            $post['user'] = $this -> userRepository -> getUserBasicsById($post['author_user_id']);
            if($post['photo_id']) $post['photo'] = asset( getImage($post['photo_id']) );
            $post['date'] = Carbon::parse($post['created_at']) -> toDateString();
            $post['time'] = Carbon::parse($post['created_at']) -> format('H:i');

            $post['likes_count'] = $this -> postLikeRepository -> count($post['id']);
            $post['liked'] = $this -> postLikeRepository -> exists($user_id, $post['id']);

            $post['comments_count'] = $this -> postCommentRepository -> count($post['id']);
        }

        return response() -> json(array(
            'posts' => $posts,
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request){
        $post_id = $request -> post_id;
        $post = $this -> postRepository -> getById($post_id);
        if ($post -> photo_id) $this -> photoRepository -> delete($post -> photo_id);

        $postLikes = $this -> postLikeRepository -> getAllByPostId($post_id); // wszystkie lajki posta
        foreach ($postLikes as $postLike){
            $this -> likeRepository -> delete($postLike -> like_id); // usuń lajk posta
            $this -> postLikeRepository -> delete($postLike->id); // usuń lajk
        }

        $postLikeNotifications = $this -> postLikeNotificationRepository -> getByPostId($post_id); // notyfikacje o lajku posta
        foreach ($postLikeNotifications as $postLikeNotification){
            $likeNotification = $this -> likeNotificationRepository -> get($postLikeNotification -> like_notification_id); // notyfikacja o lajku
            $this -> notificationRepository -> delete($likeNotification -> notification_id); // usuń notyfikację
            $this -> likeNotificationRepository -> delete($likeNotification -> id); // usuń notyfikację o lajku
            $this -> postLikeNotificationRepository -> delete($postLikeNotification -> id); // usuń notyfikację o lajku posta
        }

        $postComments = $this -> postCommentRepository -> getByPostId($post_id); // wszystkie komentarze posta
        foreach ($postComments as $postComment){
            $comment_id = $this -> commentRepository -> getById($postComment -> comment_id) -> id;

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

            $this -> commentRepository -> delete($comment_id);
            $this -> postCommentRepository -> delete($postComment -> id);
        }

        return response() -> json($this -> postRepository -> delete($post_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request){
        $post_id = $request -> post_id; // id postu
        $user_id = $request -> user_id; // id osoby lajkującej
        $like_id = $this -> likeRepository -> create($user_id, 'post'); // tworzymy lajka
        $post_like_id = $this -> postLikeRepository -> create($post_id, $like_id);  // tworzymy lajka dla postu

        $post_author_user_id = $this -> postRepository -> getById($post_id) -> author_user_id;  // pobieramy id twórcy postu
        if ($user_id != $post_author_user_id){ // sprawdzamy czy nie lajkujemy własnego posta
            $notification_id = $this -> notificationRepository -> create($post_author_user_id, 'like'); // nowa notyfikacja
            $like_notification_id = $this -> likeNotificationRepository -> create($like_id, $notification_id, 'post'); // nowa notyfikacja o lajku
            $this -> postLikeNotificationRepository -> create($like_notification_id, $post_id); // nowa notyfikacja o lajku posta
        }

        return response() -> json($post_like_id); // jak się nie wyjebie po drodze to będzie true
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike(Request $request){
        $post_id = $request -> post_id; // id postu
        $user_id = $request -> user_id; // id osoby lajkującej

        $find = $this -> postLikeRepository -> find($user_id, $post_id)[0];
        $this -> postLikeRepository -> delete($find->post_like_id);
        return response() -> json(
            $this -> likeRepository -> delete($find->like_id)
        );
    }

    public function getUpdatedPostStatistics(Request $request){
        $post_id = $request -> post_id;
        $likes_count = $this -> postLikeRepository -> count($post_id);
        $comments_count = $this -> postCommentRepository -> count($post_id);
        return response() -> json(array(
            'likes_count' => $likes_count,
            'comments_count' => $comments_count,
        ));
    }
}