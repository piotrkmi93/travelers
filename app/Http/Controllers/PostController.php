<?php

namespace App\Http\Controllers;

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
            $postCommentRepository;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PhotoRepositoryInterface $photoRepository
     * @param UserRepositoryInterface $userRepository
     * @param FriendsPairRepository $friendsPairRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param PostLikeRepositoryInterface $postLikeRepository
     * @param NotificationRepositoryInterface $notificationRepository
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
                                PostCommentRepositoryInterface $postCommentRepository) {
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

        $postLikes = $this -> postLikeRepository -> getAllByPostId($post_id);
        foreach ($postLikes as $postLike){
            $like_id = $postLike -> like_id;
            $this -> likeRepository -> delete($like_id);
            $this -> postLikeRepository -> delete($like_id);
        }

        // usuwanie komentarzy

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
}