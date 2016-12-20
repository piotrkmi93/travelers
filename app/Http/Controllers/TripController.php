<?php

namespace App\Http\Controllers;

use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\TripCommentRepositoryInterface;
use App\Repositories\TripInvitationNotificationRepositoryInterface;
use App\Repositories\TripPlaceRepositoryInterface;
use App\Repositories\TripRepositoryInterface;
use App\Repositories\TripUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class TripController extends Controller
{
    private $tripRepository,
            $friendsPairRepository,
            $tripPlaceRepository,
            $tripUserRepository,
            $tripCommentRepository,
            $commentRepository,
            $commentLikeRepository,
            $likeRepository,
            $commentNotificationRepository,
            $commentLikeNotificationRepository,
            $likeNotificationRepository,
            $notificationRepository,
            $tripInvitationNotificationRepository;

    /**
     * TripController constructor.
     * @param TripRepositoryInterface $tripRepository
     * @param FriendsPairRepositoryInterface $friendsPairRepository
     * @param TripPlaceRepositoryInterface $tripPlaceRepository
     * @param TripUserRepositoryInterface $tripUserRepository
     * @param TripCommentRepositoryInterface $tripCommentRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param TripInvitationNotificationRepositoryInterface $tripInvitationNotificationRepository
     */
    public function __construct(TripRepositoryInterface $tripRepository,
                                FriendsPairRepositoryInterface $friendsPairRepository,
                                TripPlaceRepositoryInterface $tripPlaceRepository,
                                TripUserRepositoryInterface $tripUserRepository,
                                TripCommentRepositoryInterface $tripCommentRepository,
                                CommentRepositoryInterface $commentRepository,
                                CommentLikeRepositoryInterface $commentLikeRepository,
                                LikeRepositoryInterface $likeRepository,
                                CommentNotificationRepositoryInterface $commentNotificationRepository,
                                CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository,
                                LikeNotificationRepositoryInterface $likeNotificationRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                TripInvitationNotificationRepositoryInterface $tripInvitationNotificationRepository)
    {
        $this -> tripRepository = $tripRepository;
        $this -> friendsPairRepository = $friendsPairRepository;
        $this -> tripPlaceRepository = $tripPlaceRepository;
        $this -> tripUserRepository = $tripUserRepository;
        $this -> tripCommentRepository = $tripCommentRepository;
        $this -> commentRepository = $commentRepository;
        $this -> commentLikeRepository = $commentLikeRepository;
        $this -> likeRepository = $likeRepository;
        $this -> commentNotificationRepository = $commentNotificationRepository;
        $this -> commentLikeNotificationRepository = $commentLikeNotificationRepository;
        $this -> likeNotificationRepository = $likeNotificationRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> tripInvitationNotificationRepository = $tripInvitationNotificationRepository;
    }

    /**
     * Zwraca formularz do tworzenia lub edytowania wycieczki
     *
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form($slug = null)
    {
        if(!$slug)
        {
            return view('trips.form');
        }

        return null;
    }

    /**
     * Sprawdza czy wycieczka o podanym slugu już nie istnieje
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exists(Request $request)
    {
        $slug = $request -> slug;
        $trip = $this -> tripRepository -> getBySlug($slug);
        return response() -> json(
            [
                'exists' => isset($trip),
            ]
        );
    }

    /**
     * Wyszukuje znajomych
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFriendsByPhrase(Request $request)
    {
        $user_id = $request -> user_id;
        $phrase = $request -> phrase;

        $friends = $this -> friendsPairRepository -> getFriendsByPhrase($user_id, $phrase);

        foreach ($friends as &$friend)
            $friend -> thumb_url = asset($friend -> thumb_url);

        return response() -> json([
            'friends' => $friends
        ]);
    }

    /**
     * Tworzy nową wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request -> all();
//        dd($data);

        $trip = $this -> tripRepository -> create([
            'name' => $data['name'],
            'description' => $data['description'],
            'slug' => $data['slug'],

            'start_time' => new Carbon($data['start_date']),

            'start_address' => $data['start_address'],
            'start_latitude' => $data['start_marker']['coords']['latitude'],
            'start_longitude' => $data['start_marker']['coords']['longitude'],

            'end_time' => new Carbon($data['end_date']),

            'end_address' => ($data['same_address'] ? $data['start_address'] : $data['end_address']),
            'end_latitude' => ($data['same_address'] ? $data['start_marker']['coords']['latitude'] : $data['end_marker']['coords']['latitude']),
            'end_longitude' => ($data['same_address'] ? $data['start_marker']['coords']['longitude'] : $data['end_marker']['coords']['longitude']),
        ]);

        if( isset($trip) ) {
            foreach ($data['places'] as $place) {
                $this -> tripPlaceRepository -> create([
                    'trip_id' => $trip -> id,
                    'place_id' => $place['id'],
                    'start' => $place['start'],
                    'end' => $place['end'],
                ]);
            }

            foreach($data['users'] as $user){
                $tripUser = $this -> tripUserRepository -> create([
                    'trip_id' => $trip -> id,
                    'user_id' => $user['id'],
                ]);

                $notification_id = $this -> notificationRepository -> create($user['id'], 'trip-invitation');
                $this -> tripInvitationNotificationRepository -> create($tripUser->id, $notification_id);
            }

            $this -> tripUserRepository -> create([
                'trip_id' => $trip -> id,
                'user_id' => $data['user_id'],
                'status'  => 1,
            ]);
        }

        return response() -> json([
            'success' => isset($trip)
        ]);
    }

    /**
     * Akceptuje zaproszenie na wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Request $request)
    {
        $trip_user_id = $request -> trip_user_id;
        $trip = $this -> tripUserRepository -> update($trip_user_id, ['status' => 1]);
        return response() -> json(['success' => isset($trip)]);
    }

    /**
     * Odrzuca i jednocześnie usuwa zaproszenie na wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Request $request)
    {
        $trip_user_id = $request -> trip_user_id;
        return response() -> json(['success' => $this -> tripUserRepository -> delete($trip_user_id)]);
    }
}
